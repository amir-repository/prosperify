<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRescueRequest;
use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodRescueLog;
use App\Models\FoodRescuePoint;
use App\Models\FoodRescueUser;
use App\Models\FoodVault;
use App\Models\Point;
use App\Models\PointRescueUser;
use App\Models\Rescue;
use App\Models\RescueLog;
use App\Models\RescueUser;
use App\Models\User;
use App\Models\Vault;
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
        $manager = $user->hasRole(User::ADMIN);
        $volunteer = $user->hasRole(User::VOLUNTEER);

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
            $filtered = $this->filterRescueByStatus($rescues, Rescue::SUBMITTED);

            if ($request->query('q')) {
                $filtered = $this->filterSearch($filtered, $request->query('q'));
            }

            $filtered = $this->filterPriority(request()->query('urgent'), request()->query('high-amount'), $filtered);

            return view('manager.rescues.index', ['rescues' => $filtered]);
        } else if ($volunteer) {
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
        $user = auth()->user();

        try {
            DB::beginTransaction();
            $attributes = $request->only(['donor_name', 'pickup_address', 'phone', 'email', 'title', 'description', 'rescue_date']);
            $rescue = new Rescue();
            $rescue->fill($attributes);
            $rescue->user_id = auth()->user()->id;
            $rescue->rescue_status_id = Rescue::PLANNED;
            $rescue->save();

            $rescueLog = new RescueLog();
            $rescueLog->rescue_id = $rescue->id;
            $rescueLog->rescue_status_id = $rescue->rescue_status_id;
            $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
            $rescueLog->actor_id = $user->id;
            $rescueLog->actor_name = $user->name;
            $rescueLog->donor_name = $rescue->donor_name;
            $rescueLog->pickup_address = $rescue->pickup_address;
            $rescueLog->phone = $rescue->phone;
            $rescueLog->email = $rescue->email;
            $rescueLog->title = $rescue->title;
            $rescueLog->description = $rescue->description;
            $rescueLog->rescue_date = $request->rescue_date;
            $rescueLog->user_id = $rescue->user_id;
            $rescueLog->save();

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
            $volunteers = [];
            $vaults = [];
            if ($rescue->rescue_status_id === 3) {
                $volunteers = User::role(User::VOLUNTEER)->get();
                $vaults = Vault::all();
            }
            return view('manager.rescues.show', ['rescue' => $rescue, 'volunteers' => $volunteers, 'vaults' => $vaults]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rescue $rescue)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['donor', 'admin'])) {
            abort(403);
        }

        if ($user->hasRole('donor') && $rescue->rescue_status_id === Rescue::SUBMITTED) {
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
        $user = auth()->user();
        try {
            DB::beginTransaction();

            $attributes = $request->only(['title', 'description', 'pickup_address', 'rescue_date', 'donor_name', 'phone', 'email']);
            $rescue->fill($attributes);
            $rescue->save();

            $rescueLog = new RescueLog();
            $rescueLog->rescue_id = $rescue->id;
            $rescueLog->rescue_status_id = $rescue->rescue_status_id;
            $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
            $rescueLog->actor_id = $user->id;
            $rescueLog->actor_name = $user->name;
            $rescueLog->donor_name = $rescue->donor_name;
            $rescueLog->pickup_address = $rescue->pickup_address;
            $rescueLog->phone = $rescue->phone;
            $rescueLog->email = $rescue->email;
            $rescueLog->title = $rescue->title;
            $rescueLog->description = $rescue->description;
            $rescueLog->rescue_date = $request->rescue_date;
            $rescueLog->user_id = $rescue->user_id;
            $rescueLog->save();

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
        $isPhotoInRequest = count($request->file());

        if ((int)$request->status === Rescue::REJECTED) {
            try {
                DB::beginTransaction();
                $rescue->rescue_status_id = Rescue::REJECTED;
                $rescue->save();

                // save rescue logs
                $rescueLog = new RescueLog();
                $rescueLog->rescue_id = $rescue->id;
                $rescueLog->rescue_status_id = $rescue->rescue_status_id;
                $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
                $rescueLog->actor_id = $user->id;
                $rescueLog->actor_name = $user->name;
                $rescueLog->donor_name = $rescue->donor_name;
                $rescueLog->pickup_address = $rescue->pickup_address;
                $rescueLog->phone = $rescue->phone;
                $rescueLog->email = $rescue->email;
                $rescueLog->title = $rescue->title;
                $rescueLog->description = $rescue->description;
                $rescueLog->rescue_date = Carbon::createFromFormat('d M Y H:i', $rescue->rescue_date);
                $rescueLog->user_id = $rescue->user_id;
                $rescueLog->save();

                foreach ($rescue->foods as $food) {
                    $foodRescueID = $food->pivot->id;
                    $foodRescue = FoodRescue::find($foodRescueID);
                    $foodRescue->food_rescue_status_id = Food::REJECTED;
                    $foodRescue->save();

                    $foodRescueLog = new FoodRescueLog();
                    $foodRescueLog->rescue_id = $rescue->id;
                    $foodRescueLog->food_id = $food->id;
                    $foodRescueLog->actor_id = $user->id;
                    $foodRescueLog->actor_name = $user->name;
                    $foodRescueLog->food_rescue_status_id = $foodRescue->food_rescue_status_id;
                    $foodRescueLog->food_rescue_status_name = $foodRescue->foodRescueStatus->name;
                    $foodRescueLog->amount = $food->amount;
                    $foodRescueLog->expired_date = Carbon::createFromFormat('d M Y', $food->expired_date);
                    $foodRescueLog->unit_id = $food->unit_id;
                    $foodRescueLog->unit_name = $food->unit->name;
                    $foodRescueLog->photo = !$isPhotoInRequest ? $food->photo : $this->storePhoto($request, $food->id);
                    $foodRescueLog->save();
                }
                DB::commit();
            } catch (\Exception $e) {
                throw $e;
                DB::rollBack();
            }
            return redirect()->route('rescues.show', ['rescue' => $rescue]);
        }

        try {
            DB::beginTransaction();

            $rescue->rescue_status_id = $request->status;

            if ((int)$request->status === Rescue::ASSIGNED) {
                $rescue->food_rescue_plan = count($rescue->foods);
            }

            $rescue->save();

            // save rescue logs
            $rescueLog = new RescueLog();
            $rescueLog->rescue_id = $rescue->id;
            $rescueLog->rescue_status_id = $rescue->rescue_status_id;
            $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
            $rescueLog->actor_id = $user->id;
            $rescueLog->actor_name = $user->name;
            $rescueLog->donor_name = $rescue->donor_name;
            $rescueLog->pickup_address = $rescue->pickup_address;
            $rescueLog->phone = $rescue->phone;
            $rescueLog->email = $rescue->email;
            $rescueLog->title = $rescue->title;
            $rescueLog->description = $rescue->description;
            $rescueLog->rescue_date = Carbon::createFromFormat('d M Y H:i', $rescue->rescue_date);
            $rescueLog->user_id = $rescue->user_id;
            $rescueLog->save();

            foreach ($rescue->foods as $food) {
                if ((int)$request->status === Food::TAKEN && $food->pivot->volunteer_id !== $user->id) {
                    continue;
                }

                if (in_array((int)$request->status, [Food::TAKEN, Food::STORED])) {
                    $takenorSavedPhotoID = explode('-', array_keys($request->file())[0])[0];
                    if ((int)$takenorSavedPhotoID !== $food->pivot->food_id) {
                        continue;
                    }
                }

                $foodRescueID = $food->pivot->id;
                $foodRescue = FoodRescue::find($foodRescueID);
                $foodRescue->user_id = $user->id;
                $foodRescue->food_rescue_status_id = (int)$request->status === $foodRescue->food_rescue_status_id ? Food::STORED : $request->status;

                $food = $foodRescue->food;

                if ((int)$request->status === Food::ASSIGNED) {
                    $foodRescue->assigner_id = $user->id;
                    $foodRescue->volunteer_id = $this->getVolunteerID($request, $food->id);
                    $foodRescue->vault_id = $this->getVaultID($request, $food->id);
                }

                $foodRescue->save();

                $foodRescueLog = new FoodRescueLog();
                $foodRescueLog->rescue_id = $rescue->id;
                $foodRescueLog->food_id = $food->id;
                $foodRescueLog->actor_id = $foodRescue->user_id;
                $foodRescueLog->actor_name = $foodRescue->user->name;
                $foodRescueLog->food_rescue_status_id = $foodRescue->food_rescue_status_id;
                $foodRescueLog->food_rescue_status_name = $foodRescue->foodRescueStatus->name;
                $foodRescueLog->amount = $food->amount;
                $foodRescueLog->expired_date = Carbon::createFromFormat('d M Y', $food->expired_date);
                $foodRescueLog->unit_id = $food->unit_id;
                $foodRescueLog->unit_name = $food->unit->name;
                $foodRescueLog->photo = !$isPhotoInRequest ? $food->photo : $this->storePhoto($request, $food->id);

                if ((int)$request->status === Food::ASSIGNED && (int)$request->status === Food::TAKEN) {
                    $foodRescueLog->assigner_id = $user->id;
                    $foodRescueLog->assigner_name = $user->name;
                    $foodRescueLog->volunteer_id = $this->getVolunteerID($request, $food->id);
                    $foodRescueLog->volunteer_name = User::find($foodRescueLog->volunteer_id)->name;
                    $foodRescueLog->vault_id = $this->getVaultID($request, $food->id);
                    $foodRescueLog->vault_name = Vault::find($foodRescueLog->vault_id)->name;
                }
                $foodRescueLog->save();

                // when food is stored, increment the food_rescue_result
                if ($foodRescue->food_rescue_status_id === Food::STORED) {
                    $rescue->food_rescue_result = $rescue->food_rescue_result + 1;
                    $rescue->save();

                    $foodRescue->amount_result = $foodRescueLog->amount;
                    $foodRescue->save();

                    $food->stored_at = Carbon::now();
                    $food->stored_amount = $foodRescue->amount_result;
                    $food->save();

                    // add point to user
                    $point = Point::where('user_id', $food->user_id)->first();
                    $point->point = $point->point + $food->stored_amount;
                    $point->save();

                    // save point logs
                    $fooRescuePoint = new FoodRescuePoint();
                    $fooRescuePoint->point_id = $point->id;
                    $fooRescuePoint->rescue_id = $rescue->id;
                    $fooRescuePoint->food_id = $food->id;
                    $fooRescuePoint->point = $food->stored_amount;
                    $fooRescuePoint->save();

                    // check if rescue completed
                    $rescueCompleted = $rescue->food_rescue_result === $rescue->food_rescue_plan;
                    if ($rescueCompleted) {
                        $rescue->rescue_status_id = Rescue::COMPLETED;
                        $rescue->save();
                    }
                }
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

    private function getVolunteerID($request, $foodID)
    {
        return $request["food-$foodID-volunteer_id"];
    }

    private function getVaultID($request, $foodID)
    {
        return $request["food-$foodID-vault_id"];
    }

    private function getFoodID($request)
    {
        dd($request);
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
