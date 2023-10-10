<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationFoodRequest;
use App\Models\Donation;
use App\Models\DonationFood;
use App\Models\Food;
use App\Models\FoodDonationLog;
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
        $foods = Food::where('expired_date', '>=', Carbon::now())
            ->where('amount', '>', 0)
            ->where('food_rescue_status_id', Food::STORED)
            ->orWhere('food_rescue_status_id', Food::ADJUSTED_AFTER_STORED)
            ->get();

        return view('donation.food.create', compact('foods', 'donation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDonationFoodRequest $request, Donation $donation)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();

            $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $request->food_id])->first();

            if ($donationFood) {
                $existingAmount = (int)$donationFood->amount;
                $additionalAmount = (int)$request->amount;
                $donationFood->amount = $existingAmount + $additionalAmount;
                $donationFood->save();

                $food = Food::find($request->food_id);
                $food->amount = $food->amount - $additionalAmount;
                $food->save();
            } else {
                $donationFood = new DonationFood();
                $donationFood->donation_id = $donation->id;
                $donationFood->food_id = $request->food_id;
                $donationFood->amount = $request->amount;
                $donationFood->food_donation_status_id = DonationFood::PLANNED;
                $donationFood->save();

                $food = Food::find($donationFood->food_id);
                $food->amount = $food->amount - $donationFood->amount;
                $food->save();
            }

            FoodDonationLog::Create($donationFood, $user);

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('donations.show', compact('donation'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation, Food $food, Request $request)
    {
        $donationFood = DonationFood::find($request->donationFood);
        return view('donation.food.show', compact('donation', 'food', 'donationFood'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation, Food $food, Request $request)
    {
        $donationFood = DonationFood::find($request->donationFood);
        return view('donation.food.edit', compact('donation', 'food', 'donationFood'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation, Food $food)
    {
        $user = auth()->user();
        try {
            DB::beginTransaction();

            $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $request->food_id])->first();

            if ($donationFood) {
                $existingAmount = (int)$donationFood->amount;
                $finalAmount = (int)$request->amount;
                $diffAmount = $existingAmount - $finalAmount;
                if ($diffAmount === 0) {
                    return redirect()->route('donations.show', compact('donation'));
                }
                $donationFood->amount = $finalAmount;
                $donationFood->food_donation_status_id = DonationFood::ADJUSTED_AFTER_PLANNED;
                $donationFood->save();

                $food->amount = $food->amount + $diffAmount;
                $food->save();
            }

            FoodDonationLog::Create($donationFood, $user);

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('donations.show', compact('donation'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Donation $donation, Food $food)
    {
        $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $food->id])->first();

        $planned = in_array($donationFood->food_donation_status_id, [DonationFood::PLANNED, DonationFood::ADJUSTED_AFTER_PLANNED]);

        if ($planned) {
            $this->returnFood($food, $donationFood);
            $donationFood->delete();
        }

        return redirect()->route('donations.show', compact('donation'));
    }

    private function returnFood($food, $donationFood)
    {
        $food->amount = $food->amount + $donationFood->amount;
        $food->save();
    }
}
