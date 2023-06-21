<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodVault;
use App\Models\Rescue;
use App\Models\RescuePhoto;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view('foods.create', ['rescue' => $rescue, 'foodSubCategories' => $foodSubCategories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Rescue $rescue)
    {
        $photo = $request->file('photo')->store('rescue-documentations');

        $food = new Food();
        $food->name = $request->name;
        $food->detail = $request->detail;
        $food->expired_date = $request->expired_date;
        $food->amount = $request->amount;
        $food->unit = $request->unit;
        $food->photo = $photo;
        $food->user_id = $request->user_id;
        $food->category_id = SubCategory::find($request->sub_category)->category_id;
        $food->sub_category_id = $request->sub_category;
        $food->save();

        $foodRescue = new FoodRescue();
        $foodRescue->food_id = $food->id;
        $foodRescue->rescue_id = $rescue->id;
        $foodRescue->save();

        // save rescue photo logs
        $rescue = Rescue::where('id', $rescue->id)->first();
        $rescueUserID = $rescue->user_logs->first()->pivot->id;
        $userID = auth()->user()->id;
        $rescuePhoto = new RescuePhoto();
        $rescuePhoto->photo = $photo;
        $rescuePhoto->rescue_user_id = $rescueUserID;
        $rescuePhoto->user_id = $userID;
        $rescuePhoto->save();

        // save to vaults
        $foodVault = new FoodVault();
        $foodVault->food_id = $food->id;
        $foodVault->vault_id = 1;
        $foodVault->save();

        return redirect()->route('rescues.show', ["rescue" => $rescue]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        //
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
    public function update(Request $request, Food $food)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        //
    }
}
