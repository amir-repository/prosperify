<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodRescueUser;
use App\Models\FoodVault;
use App\Models\Rescue;
use App\Models\RescuePhoto;
use App\Models\SubCategory;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function store(Request $request, Rescue $rescue)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|max:100',
            'detail' => 'required|max:255',
            'amount' => 'required|max:10',
            'unit' => 'required|max:5',
            'expired_date' => 'required|max:100',
            'sub_category' => 'required|max:5',
        ]);

        $photo = $request->file('photo')->store('rescue-documentations');

        try {
            DB::beginTransaction();
            $food = new Food();
            $food->name = $request->name;
            $food->detail = $request->detail;
            $food->expired_date = $request->expired_date;
            $food->amount = $request->amount;
            $food->unit_id = $request->unit;
            $food->photo = $photo;
            $food->user_id = $user->id;
            $food->category_id = SubCategory::find($request->sub_category)->category_id;
            $food->sub_category_id = $request->sub_category;
            $food->save();

            $foodRescue = new FoodRescue();
            $foodRescue->food_id = $food->id;
            $foodRescue->rescue_id = $rescue->id;
            $foodRescue->save();

            $foodRescueUser = new FoodRescueUser();
            $foodRescueUser->user_id = $user->id;
            $foodRescueUser->food_rescue_id = $foodRescue->id;
            $foodRescueUser->amount = $request->amount;
            $foodRescueUser->photo = $photo;
            $foodRescueUser->rescue_status_id = $rescue->rescue_status_id;
            $foodRescueUser->unit_id = $request->unit;
            $foodRescueUser->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e);
        }

        return redirect()->route('rescues.show', ["rescue" => $rescue]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rescue $rescue, Food $food)
    {
        $foodRescueID = null;
        foreach ($food->rescues as $rescue) {
            $foodRescueID = $rescue->pivot->id;
        }

        $foodRescueUsers = FoodRescueUser::where('food_rescue_id', $foodRescueID)->get();

        return view('manager.foods.show', ["food" => $food, "rescue" => $rescue, 'foodRescueUsers' => $foodRescueUsers]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rescue $rescue, Food $food)
    {
        // update amount
        if ($food->amount !== $request->amount) {
            $food->amount = $request->amount;
            $food->save();
        }

        // save rescue photo logs
        $rescueUserIDs = $rescue->user_logs;
        $rescueUserID = null;

        foreach ($rescueUserIDs as $rescueUserID) {
            if ($rescueUserID->pivot->status === $request->status) {
                $rescueUserID = $rescueUserID->pivot->id;
            }
        }

        $photo = $request->file('photo')->store('rescue-documentations');

        $rescuePhoto = new RescuePhoto();
        $rescuePhoto->photo = $photo;
        $rescuePhoto->rescue_user_id = $rescueUserID;
        $rescuePhoto->user_id = auth()->user()->id;
        $rescuePhoto->food_id = $food->id;
        $rescuePhoto->save();

        return redirect()->route('rescues.show', ['rescue' => $rescue]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        //
    }
}
