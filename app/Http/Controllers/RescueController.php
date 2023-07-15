<?php

namespace App\Http\Controllers;

use App\Models\FoodRescueUser;
use App\Models\Point;
use App\Models\PointRescueUser;
use App\Models\Rescue;
use App\Models\RescueUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PhpParser\Node\Stmt\TryCatch;

class RescueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $donor = $user->hasRole(User::DONOR);
        $manager = $user->hasAnyRole(User::VOLUNTEER, User::ADMIN);

        if ($donor) {
            $userID = auth()->user()->id;
            $rescues = User::find($userID)->rescues;
            $filtered = $this->filterRescueByStatus($rescues, Rescue::DIRENCANAKAN);

            if ($request->query('q')) {
                $filtered = $filtered->filter(function ($f) use ($request) {
                    return str_contains($f->title, $request->query('q'));
                });
            }

            // sort rescues
            $urgent = request()->query('urgent');
            $highAmount = request()->query('high-amount');
            if ($urgent === 'on' && $highAmount === 'on') {
                $filtered = $filtered->sortBy([
                    ['rescue_date', 'asc'],
                    ['score', 'desc']
                ]);
            } else if ($urgent === 'on') {
                $filtered = $filtered->sortBy('rescue_date');
            } else if ($highAmount === 'on') {
                $filtered = $filtered->sortByDesc('score');
            }

            return view('rescues.index', ['rescues' => $filtered]);
        } else if ($manager) {
            $rescues = Rescue::all();
            $filtered = $this->filterRescueByStatus($rescues, Rescue::DIAJUKAN);

            // sort rescues
            $urgent = request()->query('urgent');
            $highAmount = request()->query('high-amount');
            if ($urgent === 'on' && $highAmount === 'on') {
                $filtered = $filtered->sortBy([
                    ['rescue_date', 'asc'],
                    ['score', 'desc']
                ]);
            } else if ($urgent === 'on') {
                $filtered = $filtered->sortBy('rescue_date');
            } else if ($highAmount === 'on') {
                $filtered = $filtered->sortByDesc('score');
            }

            return view('manager.rescues.index', ['rescues' => $filtered]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->hasRole('donor')) {
            abort(403);
        }

        $user = auth()->user();
        return view('rescues.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $donor = $user->hasRole(User::DONOR);
        $manager = $user->hasAnyRole(User::VOLUNTEER, User::ADMIN);

        if (!$user->hasRole('donor')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:100',
            'description' => 'required|max:255',
            'pickup_address' => 'required|max:255',
            'rescue_date' => 'required|max:255',
            'donor_name' => 'required|max:100',
            'phone' => 'required|max:15',
            'email' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $rescue = new Rescue();
            $rescue->donor_name = $request->donor_name;
            $rescue->pickup_address = $request->pickup_address;
            $rescue->phone = $request->phone;
            $rescue->email = $request->email;
            $rescue->title = $request->title;
            $rescue->description = $request->description;
            $rescue->rescue_status_id = Rescue::DIRENCANAKAN;
            $rescue->rescue_date = $this->formatDateTime($request->rescue_date);
            $rescue->user_id = auth()->user()->id;
            $rescue->save();

            $rescue_user_log = new RescueUser();
            $rescue_user_log->user_id = auth()->user()->id;
            $rescue_user_log->rescue_id = $rescue->id;
            $rescue_user_log->rescue_status_id = Rescue::DIRENCANAKAN;
            $rescue_user_log->save();

            DB::commit();

            return redirect()->route('rescues.show', ['rescue' => $rescue]);
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rescue $rescue)
    {
        $user = Auth::user();
        $donor = $user->hasRole(User::DONOR);
        $manager = $user->hasAnyRole(User::VOLUNTEER, User::ADMIN);

        if ($donor) {
            return view('rescues.show', ['rescue' => $rescue]);
        } else if ($manager) {
            return view('manager.rescues.show', ['rescue' => $rescue]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rescue $rescue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rescue $rescue)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();
            $rescue->rescue_status_id = $request->status;
            $rescue->save();

            // save rescue log
            $rescueUser = new RescueUser();
            $rescueUser->user_id = auth()->user()->id;
            $rescueUser->rescue_id = $rescue->id;
            $rescueUser->rescue_status_id = $request->status;
            $rescueUser->save();

            // save food log
            foreach ($rescue->foods as $food) {
                $foodRescueID = $food->pivot->id;
                $foodRescueUser = new FoodRescueUser();
                $foodRescueUser->user_id = $user->id;
                $foodRescueUser->food_rescue_id = $foodRescueID;
                $foodRescueUser->amount = $food->amount;
                $foodRescueUser->photo =
                    count($request->file()) === 0
                    ? $food->photo
                    : $this->storePhoto($request, $food->id);
                $foodRescueUser->rescue_status_id = $request->status;
                $foodRescueUser->unit_id = $food->unit_id;
                $foodRescueUser->save();
            }

            if ($rescue->rescue_status_id == Rescue::DIAJUKAN) {
                // give score to rescue based on food amount
                $score = $rescue->foods()->get()->map(function ($rescue) {
                    return $rescue->amount;
                })->sum();
                $rescue->score = $score;
                $rescue->save();
            }

            // add point to donor when rescue complete
            if ($rescue->rescue_status_id == Rescue::DISIMPAN) {
                $donorPoint = Point::where('user_id', $rescue->user_id)->first();
                $donorPoint->point = $donorPoint->point + 100;
                $donorPoint->save();

                $pointRescueUser = new PointRescueUser();
                $pointRescueUser->rescue_user_id = $rescueUser->id;
                $pointRescueUser->point_id = $donorPoint->id;
                $pointRescueUser->point = 100;
                $pointRescueUser->save();

                $rescue->foods()->get()->each(function ($food) {
                    $food->stored_at = Carbon::now();
                    $food->in_stock = $food->amount;
                    $food->save();
                });
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e);
        }

        return redirect()->route("rescues.show", ['rescue' => $rescue]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rescue $rescue)
    {
        $rescue->foods->each(function ($food) {
            $foodRescue = $food->pivot;
            $foodRescue->deleted_at = Carbon::now();
            $foodRescue->save();

            $food->delete();
        });
        $rescue->delete();

        return redirect()->route('rescues.index');
    }

    private function filterRescueByStatus($rescues, $rescueStatusID)
    {
        $filtered = $rescues->filter(function ($rescues) use ($rescueStatusID) {
            $status = request()->query('status');
            if ($status === null) {
                return $rescues->rescue_status_id === $rescueStatusID;
            }
            return $rescues->rescue_status_id === (int) request()->query('status');
        });

        return $filtered;
    }

    private function storePhoto($request, $foodID)
    {
        $photoURL = $request->file("$foodID-photo")->store('rescue-documentations');
        return $photoURL;
    }

    function formatDateTime($dateTimeString)
    {
        $dateTime = explode('T', $dateTimeString);
        $date = explode('-', $dateTime[0]);
        $time = explode(':', $dateTime[1]);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];
        $hour = $time[0];
        $minute = $time[1];

        return Carbon::create($year, $month, $day, $hour, $minute, 0);
    }
}
