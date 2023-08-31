<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodRequest;
use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodRescueLog;
use App\Models\FoodRescueUser;
use App\Models\FoodVault;
use App\Models\Rescue;
use App\Models\RescueLog;
use App\Models\RescuePhoto;
use App\Models\SubCategory;
use App\Models\Unit;
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

            FoodRescueLog::Create($user, $rescue, $food);

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
        $foodRescueLogs = FoodRescueLog::where(['rescue_id' => $rescue->id, 'food_id' => $food->id])->get();
        return view('foods.show', compact('rescue', 'food', 'foodRescueLogs'));
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

            if ($food->food_rescue_status_id === Food::PLANNED) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_PLANNED;
            } else if ($food->food_rescue_status_id === Food::SUBMITTED) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_SUBMITTED;
            } else if ($food->food_rescue_status_id === Food::PROCESSED) {
                $food->food_rescue_status_id = Food::ADJUSTED_AFTER_PROCESSED;
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

            FoodRescueLog::Create($user, $rescue, $food);

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

        $foodStatusID = $food->food_rescue_status_id;
        try {
            DB::beginTransaction();
            $foodPlanned = $foodStatusID === Food::PLANNED || $foodStatusID === Food::ADJUSTED_AFTER_PLANNED;
            $foodSubmitted = $foodStatusID === Food::SUBMITTED || $foodStatusID === Food::ADJUSTED_AFTER_SUBMITTED;
            $foodProcessed = $foodStatusID === Food::PROCESSED || $foodStatusID === Food::ADJUSTED_AFTER_PROCESSED;

            if ($foodPlanned) {
                $food->delete();
            } else if ($foodSubmitted) {
                $this->rejectOrCancelFood($user, $food, $rescue);
                $this->rejectRescueWhenAllFoodAreRejectedCanceled($user, $rescue);
            } else if ($foodProcessed) {
                $this->rejectOrCancelFood($user, $food, $rescue);
                $this->rejectRescueWhenAllFoodAreRejectedCanceled($user, $rescue);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return to_route("rescues.index");
        // below it doesn't work why ?
        // return redirect()->route("rescues.index");
        // return redirect()->route('rescues.show', compact('rescue'));
    }

    private function rejectOrCancelFood($user, $food, $rescue)
    {
        $isAdmin = $user->hasRole('admin');
        if ($isAdmin) {
            $food->food_rescue_status_id = Food::REJECTED;
            $food->save();
            FoodRescueLog::Create($user, $rescue, $food);
        } else {
            $food->food_rescue_status_id = Food::CANCELED;
            $food->save();
            FoodRescueLog::Create($user, $rescue, $food);
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
}
