<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationFoodRequest;
use App\Http\Requests\UpdateDonationFoodRequest;
use App\Models\Donation;
use App\Models\DonationAssignment;
use App\Models\DonationFood;
use App\Models\DonationSchedule;
use App\Models\Food;
use App\Models\FoodDonationGivenReceipt;
use App\Models\FoodDonationLog;
use App\Models\FoodDonationLogNote;
use App\Models\FoodDonationTakenReceipt;
use App\Models\User;
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
            ->get()->sortBy('expired_date');

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
                $existingAmount = (float)$donationFood->amount;
                $additionalAmount = (float)$request->amount;
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

                // update donation priority
                $foodExpiredDate = Carbon::parse($food->expired_date);
                if ($donation->priority_donation_date === null) {
                    $donation->priority_donation_date = Carbon::parse($food->expired_date);
                    $donation->save();
                } else if ($foodExpiredDate->lt($donation->priority_donation_date)) {
                    $donation->priority_donation_date = Carbon::parse($food->expired_date);
                    $donation->save();
                }
            }

            FoodDonationLog::Create($donationFood, $user, $food->photo);

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

        $donationAssignment = DonationAssignment::where(['donation_id' => $donation->id, 'food_id' => $food->id])->get()->last() ?? null;

        return view('donation.food.show', compact('donation', 'food', 'donationFood', 'donationAssignment'));
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
    public function update(UpdateDonationFoodRequest $request, Donation $donation, Food $food)
    {
        $validated = $request->validated();
        $user = auth()->user();
        try {
            DB::beginTransaction();

            $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $request->food_id])->first();

            if ($donationFood) {
                $existingAmount = (float)$donationFood->amount;
                $finalAmount = (float)$request->amount;
                $diffAmount = $existingAmount - $finalAmount;
                if ($diffAmount === 0) {
                    return redirect()->route('donations.show', compact('donation'));
                }
                $donationFood->amount = $finalAmount;

                $donationFoodPlanned = in_array($donationFood->food_donation_status_id, [DonationFood::PLANNED, DonationFood::ADJUSTED_AFTER_PLANNED]);

                $donationFoodAssigned = in_array($donationFood->food_donation_status_id, [DonationFood::ASSIGNED, DonationFood::ADJUSTED_AFTER_ASSIGNED]);

                $donationFoodTaken = in_array($donationFood->food_donation_status_id, [DonationFood::TAKEN, DonationFood::ADJUSTED_AFTER_TAKEN]);

                if ($donationFoodPlanned) {
                    $donationFood->food_donation_status_id = DonationFood::ADJUSTED_AFTER_PLANNED;

                    $donationFood->save();

                    $food->amount = $food->amount + $diffAmount;
                    $food->save();
                } else if ($donationFoodAssigned) {
                    $donationFood->food_donation_status_id = DonationFood::ADJUSTED_AFTER_ASSIGNED;

                    $donationFood->save();

                    $food->amount = $food->amount + $diffAmount;
                    $food->save();
                } else if ($donationFoodTaken) {
                    $donationFood->food_donation_status_id = DonationFood::ADJUSTED_AFTER_TAKEN;

                    $donationFood->save();
                }
            }

            $foodDonationLog = FoodDonationLog::Create($donationFood, $user, $food->photo);
            FoodDonationLogNote::Create($foodDonationLog, $request->note);

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
        $user = auth()->user();

        $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $food->id])->first();

        $planned = in_array($donationFood->food_donation_status_id, [DonationFood::PLANNED, DonationFood::ADJUSTED_AFTER_PLANNED]);

        $assigned = in_array($donationFood->food_donation_status_id, [DonationFood::ASSIGNED, DonationFood::ADJUSTED_AFTER_ASSIGNED]);

        $taken = in_array($donationFood->food_donation_status_id, [DonationFood::TAKEN, DonationFood::ADJUSTED_AFTER_TAKEN]);

        try {
            DB::beginTransaction();
            if ($planned) {
                $this->returnFood($food, $donationFood);
                $donationFood->delete();
            } else if ($assigned) {
                $this->returnFood($food, $donationFood);
                $donationFood->delete();
            } else if ($taken) {
                $donationFood->food_donation_status_id = DonationFood::CANCELED;
                $donationFood->save();

                $foodDonationLog = FoodDonationLog::Create($donationFood, $user, $food->photo);
                FoodDonationLogNote::Create($foodDonationLog, $request->note);

                // delete schedule nya mir
                $donationSchedule = DonationSchedule::where(['donation_id' => $donation->id, 'food_id' => $food->id])->first();
                $donationSchedule->delete();

                $donationHasNoFood = $donation->donationFoods->filter(fn ($donationFood) => $donationFood->food_donation_status_id !== DonationFood::CANCELED)->count() === 0;
                if ($donationHasNoFood) {
                    $donation->donation_status_id = Donation::FAILED;
                    $donation->save();
                }
            }
            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('donations.show', compact('donation'));
    }

    public function takenReceipt(Request $request, Donation $donation, Food $food, $id)
    {
        $takenReceipt = FoodDonationTakenReceipt::find($id);
        $donationAssignment = $takenReceipt->donationAssignment;

        return view('donation.food.receipt.taken', compact('donation', 'food', 'takenReceipt', 'donationAssignment'));
    }

    public function givenReceipt(Request $request, Donation $donation, Food $food, $id)
    {
        $givenReceipt = FoodDonationGivenReceipt::find($id);
        $donationAssignment = $givenReceipt->donationAssignment;

        return view('donation.food.receipt.given', compact('donation', 'food', 'givenReceipt', 'donationAssignment'));
    }

    public function history(Donation $donation, Food $food)
    {
        $foodDonationLogs = FoodDonationLog::where(['donation_id' => $donation->id, 'food_id' => $food->id])->get();
        return view('donation.food.history', compact('foodDonationLogs'));
    }

    public function assignment(Request $request, Donation $donation, Food $food)
    {
        $donationAssignment = DonationAssignment::where(['donation_id' => $donation->id, 'food_id' => $food->id])->get();

        $volunteers = [];
        $donationAssigned = in_array($donation->donation_status_id, [Donation::ASSIGNED, Donation::INCOMPLETED]);

        if ($donationAssigned) {
            $allVolunteers = User::role(User::VOLUNTEER)->get();
            $volunteers = $this->idleVolunteers($allVolunteers, $donation);
        }
        return view('donation.food.assignment', compact('donationAssignment', 'volunteers', 'donation', 'food'));
    }

    public function updateAssignment(Request $request, Donation $donation, Food $food)
    {
        $volunteerID = $this->getVolunteerID($request, $food->id);
        $donationFood = DonationFood::where(['donation_id' => $donation->id, 'food_id' => $food->id])->first();
        $user = auth()->user();
        try {
            DB::beginTransaction();

            // clear rescue schedule nya
            DonationSchedule::where(['donation_id' => $donation->id, 'food_id' => $food->id])->first()->delete();

            // buat assignment baru
            DonationAssignment::Create($volunteerID, $food->vault_id, $user, $donationFood);

            // buat schedule baru
            DonationSchedule::Create($volunteerID, $donationFood);

            // buat donation food log baru
            FoodDonationLog::Create($donationFood, $user, $food->photo);

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('donations.show', ['donation' => $donation]);
    }

    private function returnFood($food, $donationFood)
    {
        $food->amount = $food->amount + $donationFood->amount;
        $food->save();
    }

    private function idleVolunteers($volunteers, $donation)
    {
        $volunteers = $volunteers->filter(function ($volunteer) use ($donation) {

            // dateformat to 2023-12-23
            $donation_date = Carbon::parse($donation->donation_date)->format('Y-m-d');

            $maxFoodDonationInAday = 1;

            // volunteer hanya bisa handle 1 donation food dalam suatu hari
            return DonationSchedule::whereDate('donation_date', $donation_date)->where('user_id', $volunteer->id)->count() < $maxFoodDonationInAday;
        });

        return $volunteers;
    }

    private function getVolunteerID($request, $foodID)
    {
        return $request["food-$foodID-volunteer_id"];
    }
}
