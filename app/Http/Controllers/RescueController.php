<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRescueRequest;
use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodRescueUser;
use App\Models\FoodVault;
use App\Models\Point;
use App\Models\PointRescueUser;
use App\Models\Rescue;
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
            $filtered = $this->filterRescueByStatus($rescues, Rescue::SUBMITTED);

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
            $attributes = $request->only(['donor_name', 'pickup_address', 'phone', 'email', 'title', 'description', 'rescue_date']);
            $rescue = new Rescue();
            $rescue->fill($attributes);
            $rescue->user_id = auth()->user()->id;
            $rescue->rescue_status_id = Rescue::PLANNED;
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
            $volunteers = [];
            $vaults = [];
            if ($rescue->rescue_status_id >= 3) {
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
        $isPhotoInRequest = count($request->file());

        try {
            DB::beginTransaction();
            $rescue->rescue_status_id = $request->status;
            $rescue->save();

            foreach ($rescue->foods as $food) {
                $foodRescueID = $food->pivot->id;
                $foodRescue = FoodRescue::find($foodRescueID);
                $foodRescue->food_rescue_status_id = $request->status;
                $foodRescue->user_id = $user->id;
                $foodRescue->doer = $user->name;
                $foodRescue->save();

                $food = Food::find($foodRescue->food_id);

                $foodRescueUser = new FoodRescueUser();
                $foodRescueUser->user_id = $user->id;
                $foodRescueUser->food_rescue_id = $foodRescue->id;
                $foodRescueUser->amount = $food->amount;
                $foodRescueUser->photo = !$isPhotoInRequest ? $food->photo : $this->storePhoto($request, $food->id);
                $foodRescueUser->food_rescue_status_id = $request->status;
                $foodRescueUser->unit_id = $food->unit_id;
                $foodRescueUser->save();

                if ((int)$request->status === Rescue::ASSIGNED) {
                    $rescueUser = new RescueUser();
                    $rescueUser->user_id = $this->getVolunteerID($request, $food->id);
                    $rescueUser->rescue_id = $rescue->id;
                    $rescueUser->assigner_id = $user->id;
                    $rescueUser->save();

                    $foodRescue->rescue_user_id = $rescueUser->id;
                    $foodRescue->save();

                    $foodVault = new FoodVault();
                    $foodVault->food_id = $food->id;
                    $foodVault->vault_id = $this->getVaultID($request, $food->id);
                    $foodVault->save();
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
