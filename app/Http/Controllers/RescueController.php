<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRescueRequest;
use App\Models\Food;
use App\Models\FoodRescue;
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
            $filtered = $this->filterRescueByStatus($rescues, Rescue::PLANNED);

            if ($request->query('q')) {
                $filtered = $this->filterSearch($filtered, $request->query('q'));
            }

            $filtered = $this->filterPriority(request()->query('urgent'), request()->query('high-amount'), $filtered);

            return view('rescues.index', ['rescues' => $filtered]);
        } else if ($manager) {
            $rescues = Rescue::all();
            $filtered = $this->filterRescueByStatus($rescues, Rescue::DIAJUKAN);

            if ($request->query('q')) {
                $filtered = $this->filterSearch($filtered, $request->query('q'));
            }

            $filtered = $this->filterPriority(request()->query('urgent'), request()->query('high-amount'), $filtered);

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
    public function store(StoreRescueRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $rescue = new Rescue();

            $attributes = $request->only(['title', 'description', 'pickup_address', 'rescue_date', 'donor_name', 'phone', 'email']);
            $rescue = new Rescue();
            $rescue->fill($attributes);
            $rescue->rescue_status_id = Rescue::PLANNED;
            $rescue->user_id = auth()->user()->id;
            $rescue->save();

            DB::commit();

            return redirect()->route('rescues.show', ['rescue' => $rescue]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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
        $user = auth()->user();
        if (!$user->hasRole('donor')) {
            abort(403);
        }

        $user = auth()->user();
        return view('rescues.edit', ['user' => $user, 'rescue' => $rescue]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rescue $rescue)
    {
        try {
            DB::beginTransaction();

            $attributes = $request->only(['title', 'description', 'pickup_address', 'rescue_date', 'donor_name', 'phone', 'email']);
            $rescue->fill($attributes);
            $rescue->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route("rescues.show", ['rescue' => $rescue]);
    }

    public function updateStatus(Request $request, Rescue $rescue)
    {

        $user = auth()->user();

        try {
            DB::beginTransaction();
            $rescue->rescue_status_id = $request->status;
            $rescue->save();

            foreach ($rescue->foods as $food) {
                $foodRescueID = $food->pivot->id;
                $foodRescue = FoodRescue::find($foodRescueID);
                $foodRescue->food_rescue_status_id = $request->status;
                $foodRescue->save();

                $food = Food::find($foodRescue->food_id);

                $foodRescueUser = new FoodRescueUser();
                $foodRescueUser->user_id = $user->id;
                $foodRescueUser->food_rescue_id = $foodRescue->id;
                $foodRescueUser->amount = $food->amount;
                $foodRescueUser->photo = $this->storePhoto($request, $food->id);
                $foodRescueUser->food_rescue_status_id = $request->status;
                $foodRescueUser->unit_id = $food->unit_id;
                $foodRescueUser->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
        }

        return redirect()->route('rescues.show', ['rescue' => $rescue]);
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

    private function filterSearch($collections, $filterValue)
    {
        $filtered = $collections->filter(function ($f) use ($filterValue) {
            return str_contains(strtolower($f->title), strtolower($filterValue));
        });

        return $filtered;
    }

    private function filterPriority($urgent, $highAmount, $collections)
    {
        $filtered = $collections;
        if ($urgent === 'on' && $highAmount === 'on') {
            $filtered = $collections->sortBy([
                ['rescue_date', 'asc'],
                ['score', 'desc']
            ]);
        } else if ($urgent === 'on') {
            $filtered = $collections->sortBy('rescue_date');
        } else if ($highAmount === 'on') {
            $filtered = $collections->sortByDesc('score');
        } else {
            return $collections;
        }
        return $filtered;
    }
}
