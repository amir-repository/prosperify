<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationFood;
use App\Models\DonationFoodUser;
use App\Models\DonationPhoto;
use App\Models\Food;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodDonationController extends Controller
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
    public function create(Donation $donation)
    {
        $foods = Food::where('in_stock', '>', 0)
            ->where('expired_date', '>=', Carbon::today())
            ->whereNotNull('stored_at')
            ->get();

        return view('donations.foods.create', ['foods' => $foods, 'donation' => $donation]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Donation $donation)
    {
        $ifFoodExist = in_array((int)$request->food_id, $donation->foods->map(fn ($food) => $food->id)->toArray());
        try {
            DB::beginTransaction();

            $donationFood = null;

            if ($ifFoodExist) {
                $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $request->food_id])->first();
                $donationFood->amount_plan = $donationFood->amount_plan + (int)$request->amount_plan;
            } else {
                $donationFood = new DonationFood();
                $donationFood->food_id = $request->food_id;
                $donationFood->donation_id = $donation->id;
                $donationFood->amount_plan = $request->amount_plan;
            }
            $donationFood->save();

            $food = Food::find($request->food_id);
            if ($ifFoodExist) {
                $food->in_stock = $food->in_stock - (int)$request->amount_plan;
            } else {
                $food->in_stock = $food->in_stock - $donationFood->amount_plan;
            }
            $food->save();

            // simpan siapa yang melakukan perubahan
            $donationFoodUser = new DonationFoodUser();
            $donationFoodUser->user_id = auth()->user()->id;
            $donationFoodUser->donation_food_id = $donationFood->id;
            $donationFoodUser->amount =
                ($ifFoodExist)
                ? $donationFood->amount_plan
                : $request->amount_plan;
            $donationFoodUser->photo = $food->photo;
            $donationFoodUser->donation_status_id = Donation::DIRENCANAKAN;
            $donationFoodUser->unit_id = $food->unit_id;
            $donationFoodUser->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        return redirect()->route('donations.show', ['donation' => $donation]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation, Food $food)
    {
        $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $food->id])->get()->first();

        $donationFoodUsers = DonationFoodUser::where('donation_food_id', $donationFood->id)->get();

        return view('donations.foods.show', ['donation' => $donation, 'food' => $food, 'donationFoodUsers' => $donationFoodUsers]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation, Food $food)
    {
        $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $food->id])->first();

        return view('donations.foods.edit', ['donation' => $donation, 'food' => $food, 'donationFood' => $donationFood]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation, Food $food)
    {
        try {
            DB::beginTransaction();
            $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $food->id])->first();
            $donationFood->amount_plan = $request->amount;
            $donationFood->save();

            $donationFoodUser = new DonationFoodUser();
            $donationFoodUser->user_id = auth()->user()->id;
            $donationFoodUser->donation_food_id = $donationFood->id;
            $donationFoodUser->amount = $request->amount;
            $donationFoodUser->photo = $this->storePhoto($request, 'photo');
            $donationFoodUser->unit_id = $food->unit_id;
            $donationFoodUser->donation_status_id = $donation->donation_status_id;
            $donationFoodUser->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e);
        }

        return redirect()->route('donations.show', ['donation' => $donation]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        //
    }

    private function storePhoto($request, $name)
    {
        $photoURL = $request->file($name)->store('donation-documentations');
        return $photoURL;
    }
}
