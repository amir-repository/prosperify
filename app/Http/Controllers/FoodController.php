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
        // jika dalam tahap perencanaan gpp dihapus
        // kalau dalam tahap submit itu canceled
        // kalau dalam tahap send itu rejected

        // kita handle delete, lalu confirmation, lalu perks-perks nya, terakhir kita buat database untuk handling food rescue

        $user = auth()->user();

        $foodRescue = FoodRescue::where(['rescue_id' => $rescue->id, 'food_id' => $food->id])->first();

        $rescueStatusID = $rescue->rescue_status_id;
        try {
            DB::beginTransaction();

            if ($rescueStatusID === Rescue::PLANNED) {
                $foodRescueLogs = FoodRescueLog::where(['rescue_id' => $rescue->id, 'food_id' => $food->id])->get();

                foreach ($foodRescueLogs as $foodRescueLog) {
                    $foodRescueLog->delete();
                }
                $foodRescue->delete();
                $food->delete();
            } else if ($rescueStatusID === Rescue::SUBMITTED) {
                $food->canceled_at = Carbon::now();
                $food->save();

                $foodRescue->food_rescue_status_id = Food::CANCELED;
                $foodRescue->user_id = $user->id;
                $foodRescue->save();

                $foodRescueLog = new FoodRescueLog();
                $foodRescueLog->rescue_id = $rescue->id;
                $foodRescueLog->food_id = $food->id;

                if ($user->hasAnyRole(['admin', 'volunteer'])) {
                    $foodRescueLog->actor_id = $user->id;
                    $foodRescueLog->actor_name = $user->name;
                } else {
                    $foodRescueLog->actor_id = $foodRescue->user_id;
                    $foodRescueLog->actor_name = $foodRescue->user->name;
                }

                $foodRescueLog->food_rescue_status_id = $foodRescue->food_rescue_status_id;
                $foodRescueLog->food_rescue_status_name = $foodRescue->foodRescueStatus->name;
                $foodRescueLog->amount = $foodRescue->amount_plan;
                $foodRescueLog->expired_date = Carbon::createFromFormat('d M Y', $food->expired_date);
                $foodRescueLog->unit_id = $food->unit_id;
                $foodRescueLog->unit_name = $food->unit->name;
                $foodRescueLog->photo = $food->photo;
                $foodRescueLog->save();

                // update food rescue status ke CAnceled
                // update food ke canceled
                // buat food logs baru canceled
            } else {
                $food->rejected_at = Carbon::now();
                $food->save();

                $foodRescue->food_rescue_status_id = Food::REJECTED;
                $foodRescue->user_id = $user->id;
                $foodRescue->save();

                $foodRescueLog = new FoodRescueLog();
                $foodRescueLog->rescue_id = $rescue->id;
                $foodRescueLog->food_id = $food->id;
                $foodRescueLog->actor_id = $user->id;
                $foodRescueLog->actor_name = $user->name;
                $foodRescueLog->food_rescue_status_id = $foodRescue->food_rescue_status_id;
                $foodRescueLog->food_rescue_status_name = $foodRescue->foodRescueStatus->name;
                $foodRescueLog->amount = $foodRescue->amount_plan;
                $foodRescueLog->expired_date = Carbon::createFromFormat('d M Y', $food->expired_date);
                $foodRescueLog->unit_id = $food->unit_id;
                $foodRescueLog->unit_name = $food->unit->name;
                $foodRescueLog->photo = $food->photo;
                $foodRescueLog->save();

                if ($rescue->rescue_status_id >= Rescue::ASSIGNED) {
                    $rescue->food_rescue_plan = $rescue->food_rescue_plan - 1;
                    $rescue->save();

                    $rescueLog = new RescueLog();
                    $rescueLog->rescue_id = $rescue->id;
                    $rescueLog->rescue_status_id = $rescue->rescue_status_id;
                    $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
                    $rescueLog->actor_id = $user->id;
                    $rescueLog->actor_name = $user->name;
                    $rescueLog->food_rescue_plan = $rescue->food_rescue_plan;
                    $rescueLog->donor_name = $rescue->donor_name;
                    $rescueLog->pickup_address = $rescue->pickup_address;
                    $rescueLog->phone = $rescue->phone;
                    $rescueLog->email = $rescue->email;
                    $rescueLog->title = $rescue->title;
                    $rescueLog->description = $rescue->description;
                    $rescueLog->rescue_date = $rescue->rescue_date;
                    $rescueLog->score = $rescue->score;
                    $rescue->user_id = $rescue->user_id;
                    $rescue->save();
                }


                // check if rescue has no food to rescue,
                // it it so, change rescue to failed;
                $ifNoFoodToRescue = $rescue->foods->filter(fn ($food) => $food->canceled_at === null && $food->rejected_at === null)->isEmpty();
                if ($ifNoFoodToRescue) {
                    $rescue->rescue_status_id = Rescue::FAILED;
                    $rescue->save();

                    $rescueLog = new RescueLog();
                    $rescueLog->rescue_id = $rescue->id;
                    $rescueLog->rescue_status_id = $rescue->rescue_status_id;
                    $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
                    $rescueLog->actor_id = $user->id;
                    $rescueLog->actor_name = $user->name;
                    $rescueLog->food_rescue_plan = $rescue->food_rescue_plan;
                    $rescueLog->donor_name = $rescue->donor_name;
                    $rescueLog->pickup_address = $rescue->pickup_address;
                    $rescueLog->phone = $rescue->phone;
                    $rescueLog->email = $rescue->email;
                    $rescueLog->title = $rescue->title;
                    $rescueLog->description = $rescue->description;
                    $rescueLog->rescue_date = $rescue->rescue_date;
                    $rescueLog->score = $rescue->score;
                    $rescue->user_id = $rescue->user_id;
                    $rescue->save();
                }

                // if after delete rescue plan is equal to rescue result
                // change to complete
                if ($rescue->food_rescue_plan === $rescue->food_rescue_result && $rescue->rescue_status_id !== Rescue::FAILED) {
                    $rescue->rescue_status_id = Rescue::COMPLETED;
                    $rescue->save();

                    $rescueLog = new RescueLog();
                    $rescueLog->rescue_id = $rescue->id;
                    $rescueLog->rescue_status_id = $rescue->rescue_status_id;
                    $rescueLog->rescue_status_name = $rescue->rescueStatus->name;
                    $rescueLog->actor_id = $user->id;
                    $rescueLog->actor_name = $user->name;
                    $rescueLog->food_rescue_plan = $rescue->food_rescue_plan;
                    $rescueLog->donor_name = $rescue->donor_name;
                    $rescueLog->pickup_address = $rescue->pickup_address;
                    $rescueLog->phone = $rescue->phone;
                    $rescueLog->email = $rescue->email;
                    $rescueLog->title = $rescue->title;
                    $rescueLog->description = $rescue->description;
                    $rescueLog->rescue_date = $rescue->rescue_date;
                    $rescueLog->score = $rescue->score;
                    $rescue->user_id = $rescue->user_id;
                    $rescue->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('rescues.show', ['rescue' => $rescue]);
    }
}
