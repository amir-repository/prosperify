<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodRequest;
use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodRescueLog;
use App\Models\FoodRescueUser;
use App\Models\FoodVault;
use App\Models\Rescue;
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
            $food->user_id = $user->id;
            $food->photo = $photo;
            $food->category_id = SubCategory::find($request->sub_category_id)->category_id;
            $food->save();

            $foodRescue = new FoodRescue();
            $foodRescue->rescue_id = $rescue->id;
            $foodRescue->food_id = $food->id;
            $foodRescue->user_id = $user->id;
            $foodRescue->food_rescue_status_id = Food::PLANNED;
            $foodRescue->amount_plan = $request->amount;
            $foodRescue->save();

            $foodRescueLog = new FoodRescueLog();
            $foodRescueLog->rescue_id = $rescue->id;
            $foodRescueLog->food_id = $food->id;
            $foodRescueLog->amount = $request->amount;
            $foodRescueLog->actor_id = $foodRescue->user_id;
            $foodRescueLog->actor_name = $foodRescue->user->name;
            $foodRescueLog->food_rescue_status_id = Food::PLANNED;
            $foodRescueLog->food_rescue_status_name = $foodRescue->foodRescueStatus->name;
            $foodRescueLog->expired_date = $request->expired_date;
            $foodRescueLog->unit_id = $request->unit_id;
            $foodRescueLog->unit_name = Unit::find($request->unit_id)->name;
            $foodRescueLog->photo = $photo;
            $foodRescueLog->save();

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
            $food->name = $request->name;
            $food->detail = $request->detail;
            $food->expired_date = $request->expired_date;
            $food->amount = $request->amount;
            $food->unit_id = $request->unit_id;
            $food->sub_category_id = $request->sub_category_id;
            $food->photo = $photo;
            $food->category_id = SubCategory::find($request->sub_category_id)->category_id;
            $food->save();

            $foodRescue = FoodRescue::where(['rescue_id' => $rescue->id, 'food_id' => $food->id])->first();
            $foodRescue->user_id = $user->id;
            $foodRescue->amount_plan = $request->amount;
            $foodRescue->save();

            $foodRescueLog = new FoodRescueLog();
            $foodRescueLog->rescue_id = $rescue->id;
            $foodRescueLog->food_id = $food->id;
            $foodRescueLog->actor_id = $foodRescue->user_id;
            $foodRescueLog->actor_name = $foodRescue->user->name;
            $foodRescueLog->food_rescue_status_id = $foodRescue->food_rescue_status_id;
            $foodRescueLog->food_rescue_status_name = $foodRescue->foodRescueStatus->name;
            $foodRescueLog->amount = $foodRescue->amount_plan;
            $foodRescueLog->expired_date = $request->expired_date;
            $foodRescueLog->unit_id = $food->unit_id;
            $foodRescueLog->unit_name = $food->unit->name;
            $foodRescueLog->photo = $food->photo;
            $foodRescueLog->save();

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
    public function destroy(Rescue $rescue, Food $food)
    {
        try {
            DB::beginTransaction();
            $food->delete();

            $foodRescue = FoodRescue::where(['rescue_id' => $rescue->id, 'food_id' => $food->id])->first();
            $foodRescue->delete();

            foreach ($foodRescue->foodRescueUsers as $foodRescueUser) {
                $foodRescueUser->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('rescues.show', ['rescue' => $rescue]);
    }
}
