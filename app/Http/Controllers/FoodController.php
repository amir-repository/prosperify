<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodRequest;
use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodRescueLog;
use App\Models\FoodRescueLogNote;
use App\Models\FoodRescueStoredReceipt;
use App\Models\FoodRescueTakenReceipt;
use App\Models\FoodRescueUser;
use App\Models\FoodVault;
use App\Models\Rescue;
use App\Models\RescueAssignment;
use App\Models\RescueLog;
use App\Models\RescuePhoto;
use App\Models\RescueSchedule;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vault;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Rescue $rescue)
    {
        $foodSubCategories = SubCategory::all();
        $units = Unit::all();
        return view('foods.create', ['rescue' => $rescue, 'foodSubCategories' => $foodSubCategories, 'units' => $units]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodRequest $request, Rescue $rescue)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $photo = $request->file('photo')->store('rescue-documentations');


        try {
            DB::beginTransaction();
            $attributes = $request->only(['name', 'detail', 'expired_date', 'amount', 'unit_id', 'sub_category_id']);
            $food = new Food();
            $food->fill($attributes);
            $food->rescue_id = $rescue->id;
            $food->photo = $photo;
            $food->category_id = SubCategory::find($request->sub_category_id)->category_id;
            $food->food_rescue_status_id = Food::PLANNED;
            $food->save();

            FoodRescueLog::Create($user, $rescue, $food, null);

            // update rescue priority
            $foodExpiredDate = Carbon::parse($food->expired_date);
            if ($rescue->priority_rescue_date === null) {
                $rescue->priority_rescue_date = Carbon::parse($food->expired_date);
                $rescue->save();
            } else if ($foodExpiredDate->lt($rescue->priority_rescue_date)) {
                $rescue->priority_rescue_date = Carbon::parse($food->expired_date);
                $rescue->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($photo);
            throw $e;
        }

        return redirect()->route('rescues.show', ["rescue" => $rescue]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rescue $rescue, Food $food)
    {
        $rescueAssignment = RescueAssignment::where(['rescue_id' => $rescue->id, 'food_id' => $food->id])->get()->last() ?? null;

        $foodRescueLogs = FoodRescueLog::where(['rescue_id' => $rescue->id, 'food_id' => $food->id])->get();
        return view('foods.show', compact('rescue', 'food', 'foodRescueLogs', 'rescueAssignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rescue $rescue, Food $food)
    {
        $foodSubCategories = SubCategory::all();
        $units = Unit::all();
        return view('foods.edit', ['rescue' => $rescue, 'food' => $food, 'units' => $units, 'foodSubCategories' => $foodSubCategories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFoodRequest $request, Rescue $rescue, Food $food)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $photo = $request->file('photo')->store('rescue-documentations');

        try {
            DB::beginTransaction();

            $foodPlanned = $food->food_rescue_status_id === Food::PLANNED;
            $foodSubmitted = $food->food_rescue_status_id === Food::SUBMITTED;
            $foodProcessed = $food->food_rescue_status_id === Food::PROCESSED;
            $foodAssigned = $food->food_rescue_status_id === Food::ASSIGNED;
            $foodTaken = $food->food_rescue_status_id === Food::TAKEN;
            $foodStored = $food->food_rescue_status_id === Food::STORED;

            if ($foodPlanned) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_PLANNED;
            } else if ($foodSubmitted) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_SUBMITTED;
            } else if ($foodProcessed) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_PROCESSED;
            } else if ($foodAssigned) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_ASSIGNED;
            } else if ($foodTaken) {
                $food->food_rescue_status_id = Food::ADJUSTED_BEFORE_STORED;
            } else if ($foodStored) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_STORED;
            }

            $food->name = $request->name;
            $food->detail = $request->detail;
            $food->expired_date = $request->expired_date;
            $food->amount = $request->amount;
            $food->unit_id = $request->unit_id;
            $food->sub_category_id = $request->sub_category_id;
            $food->photo = $photo;
            $food->category_id = SubCategory::find($request->sub_category_id)->category_id;
            $food->save();

            FoodRescueLog::Create($user, $rescue, $food, null,);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('rescues.foods.show', ['rescue' => $rescue, 'food' => $food]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Rescue $rescue, Food $food)
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $rescue = $food->rescue;

        $note = $request->note;

        $foodStatusID = $food->food_rescue_status_id;
        try {
            DB::beginTransaction();
            $foodPlanned = in_array($foodStatusID, [Food::PLANNED, Food::ADJUSTED_AFTER_PLANNED]);
            $foodSubmitted = in_array($foodStatusID, [Food::SUBMITTED, Food::ADJUSTED_AFTER_SUBMITTED]);
            $foodProcessed = in_array($foodStatusID, [Food::PROCESSED, Food::ADJUSTED_AFTER_PROCESSED]);
            $foodAssigned = in_array($foodStatusID, [Food::ASSIGNED, Food::ADJUSTED_AFTER_ASSIGNED]);
            $foodTaken = in_array($foodStatusID, [Food::TAKEN, Food::ADJUSTED_BEFORE_STORED]);
            $foodStored = in_array($foodStatusID, [Food::STORED, Food::ADJUSTED_AFTER_STORED]);

            if ($foodPlanned) {
                $food->delete();
            } else if ($foodSubmitted) {
                $this->rejectOrCancelFood($user, $food, $rescue, $note);
                $this->rejectRescueWhenAllFoodAreRejectedCanceled($user, $rescue);
            } else if ($foodProcessed) {
                $this->rejectOrCancelFood($user, $food, $rescue, $note);
                $this->rejectRescueWhenAllFoodAreRejectedCanceled($user, $rescue);
            } else if ($foodAssigned) {
                $this->rejectOrCancelFood($user, $food, $rescue, $note);
                $this->rejectRescueWhenAllFoodAreRejectedCanceled($user, $rescue);
                $this->cleanupFoodAssignmentSchedule($food);
            } else if ($foodTaken) {
                $this->rejectOrCancelFood($user, $food, $rescue, $note);
                $this->rejectRescueWhenAllFoodAreRejectedCanceled($user, $rescue);
                $this->cleanupFoodAssignmentSchedule($food);
            } else if ($foodStored) {
                $this->discardFood($user, $food, $rescue);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('rescues.show', ["rescue" => $rescue]);
    }

    public function takenReceipt(Request $request, Rescue $rescue, Food $food, $id)
    {
        $takenReceipt = FoodRescueTakenReceipt::find($id);
        $rescueAssignment = $takenReceipt->rescueAssignment;

        return view('receipt.taken.index', compact('takenReceipt', 'rescueAssignment'));
    }

    public function storedReceipt(Request $request, Rescue $rescue, Food $food, $id)
    {
        $storedReceipt = FoodRescueStoredReceipt::find($id);
        $rescueAssignment = $storedReceipt->rescueAssignment;

        return view('receipt.stored.index', compact('storedReceipt', 'rescueAssignment'));
    }

    public function assignment(Request $request, Rescue $rescue, Food $food)
    {
        $volunteers = [];
        $vaults = [];
        $rescueAssigned = in_array($rescue->rescue_status_id, [Rescue::ASSIGNED, Rescue::INCOMPLETED]);
        if ($rescueAssigned) {
            $volunteers = User::role(User::VOLUNTEER)->get();
            $volunteers = $volunteers->filter(function ($volunteer) use ($rescue) {

                // dateformat to 2023-12-23
                $rescue_date = Carbon::parse($rescue->rescue_date)->format('Y-m-d');

                // volunteer hanya bisa handle 1 food dalam suatu hari
                $maxFoodRescueInAday = 1;
                return RescueSchedule::whereDate('rescue_date', $rescue_date)->where('user_id', $volunteer->id)->count() < $maxFoodRescueInAday;
            });

            $vaults = Vault::all();
        }

        return view('foods.assignment', compact('rescue', 'food', 'volunteers', 'vaults'));
    }

    public function createAssignment(Request $request, Rescue $rescue, Food $food)
    {
        $volunteerID = $this->getVolunteerID($request, $food->id);
        $vaultID = $this->getVaultID($request, $food->id);
        $user = auth()->user();
        try {
            DB::beginTransaction();

            // we need to clear up the assigned user on schedule first then assigned a new one
            RescueSchedule::where('food_id', $food->id)->first()->delete();

            RescueAssignment::Create($food, $rescue, $user, $volunteerID, $vaultID);

            RescueSchedule::Create($rescue, $food, $volunteerID);

            $vault = Vault::find($vaultID);
            FoodRescueLog::Create($user, $rescue, $food, $vault);

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('rescues.show', ['rescue' => $rescue]);
    }

    private function rejectOrCancelFood($user, $food, $rescue, $note)
    {
        $isAdmin = $user->hasRole('admin');
        if ($isAdmin) {
            $food->food_rescue_status_id = Food::REJECTED;
            $food->save();
            $log = FoodRescueLog::Create($user, $rescue, $food, null);
            if ($note) {
                $logNote = FoodRescueLogNote::Create($log, $note);
            }
        } else {
            $food->food_rescue_status_id = Food::CANCELED;
            $food->save();
            $log = FoodRescueLog::Create($user, $rescue, $food, null);
            if ($note) {
                $logNote = FoodRescueLogNote::Create($log, $note);
            }
        }
    }

    private function discardFood($user, $food, $rescue)
    {
        $isAdmin = $user->hasRole('admin');
        if ($isAdmin) {
            $food->food_rescue_status_id = Food::DISCARDED;
            $food->save();
            $vaultId = $food->rescueAssignment->last()->vault_id;
            $vault = Vault::find($vaultId);
            FoodRescueLog::Create($user, $rescue, $food, $vault);
        }
    }

    private function rejectRescueWhenAllFoodAreRejectedCanceled($user, $rescue)
    {
        // kalau ternyata di rescue, semua food nya rejected atau pun canceled, maka set otomatis  rescuenya ke rejected
        $allFoodIsRejected = true;
        foreach ($rescue->foods as $food) {
            $theresStillFoodThatsNotRejectedNorCanceled = !in_array($food->food_rescue_status_id, [Food::REJECTED, Food::CANCELED]);
            if ($theresStillFoodThatsNotRejectedNorCanceled) {
                $allFoodIsRejected = false;
            }
        }

        if ($allFoodIsRejected) {
            $rescue->rescue_status_id = Rescue::REJECTED;
            $rescue->save();
            RescueLog::Create($user, $rescue);
        }
    }

    private function cleanupFoodAssignmentSchedule($food)
    {
        $rescueSchedule = RescueSchedule::where('food_id', $food->id)->first();
        $rescueSchedule->delete();

        // jangan delete assignment nya, untuk sekarang di comment aja dulu
        // $rescueAssignments = RescueAssignment::where('food_id', $food->id)->get();
        // foreach ($rescueAssignments as $rescueAssignment) {
        //     $rescueAssignment->delete();
        // }
    }

    private function getVolunteerID($request, $foodID)
    {
        return $request["food-$foodID-volunteer_id"];
    }

    private function getVaultID($request, $foodID)
    {
        return $request["food-$foodID-vault_id"];
    }
}
