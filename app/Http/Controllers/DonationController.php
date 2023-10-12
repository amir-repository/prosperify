<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use App\Models\Donation;
use App\Models\DonationAssignment;
use App\Models\DonationFood;
use App\Models\DonationLog;
use App\Models\DonationSchedule;
use App\Models\Food;
use App\Models\FoodDonationGivenReceipt;
use App\Models\FoodDonationLog;
use App\Models\FoodDonationTakenReceipt;
use App\Models\FoodRescueTakenReceipt;
use App\Models\Recipient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $admin = $user->hasRole(User::ADMIN);
        $volunteer = $user->hasRole(User::VOLUNTEER);

        if ($admin) {
            $donation = Donation::all();
            $filtered = $this->filterDonationByStatus($donation, [Donation::PLANNED]);

            if ($request->query('q')) {
                $filtered = $this->filterSearch($filtered, $request->query('q'));
            }

            return view('donation.index', ['donations' => $filtered->sortBy('donation_date')]);
        } else if ($volunteer) {

            $donations = Donation::all();
            $filtered = $this->filterDonationByStatus($donations, [Donation::ASSIGNED]);

            if ($request->query('q')) {
                $filtered = $this->filterSearch($filtered, $request->query('q'));
            }

            $donations = collect([]);

            foreach ($filtered as $donation) {
                foreach ($donation->donationFoods as $donationFood) {
                    $foodHasAssignment = $donationFood->donationAssignments->count() > 0;
                    $foodIsAssignedToLogedVolunteer = $foodHasAssignment && $donationFood->donationAssignments->last()->volunteer_id === $user->id;

                    if ($foodIsAssignedToLogedVolunteer) {
                        $donations->push($donation);
                        break;
                    }
                }
            }

            return view('donation.index', ['donations' => $donations->sortBy('donation_date')]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $recipients = Recipient::where('recipient_status_id', Recipient::ACCEPTED)->get();
        return view('donation.create', compact('recipients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDonationRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        try {
            DB::beginTransaction();
            $donation = new Donation();
            $donation->title = $request->title;
            $donation->description = $request->description;
            $donation->donation_date = $request->donation_date;
            $donation->recipient_id = $request->recipient_id;
            $donation->donation_status_id = Donation::PLANNED;
            $donation->user_id = $user->id;
            $donation->save();

            DonationLog::Create($donation, $user);

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
    public function show(Donation $donation)
    {
        $donationPlanned = $donation->donation_status_id === Donation::PLANNED;
        $volunteers = [];

        if ($donationPlanned) {
            $allVolunteers = User::role(User::VOLUNTEER)->get();
            $volunteers = $this->idleVolunteers($allVolunteers, $donation, 1);
        }

        $donationFoods = $donation->donationFoods;

        return view('donation.show', compact('donation', 'volunteers', 'donationFoods'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        $recipients = Recipient::where('recipient_status_id', Recipient::ACCEPTED)->get();
        return view('donation.edit', compact('donation', 'recipients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDonationRequest $request, Donation $donation)
    {
        $validated = $request->validated();
        $user = auth()->user();

        try {
            DB::beginTransaction();
            $donation->title = $request->title;
            $donation->description = $request->description;
            $donation->donation_date = $request->donation_date;
            $donation->recipient_id = $request->recipient_id;
            $donation->donation_status_id = Donation::PLANNED;
            $donation->user_id = $user->id;
            $donation->save();

            DonationLog::Create($donation, $user);
            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('donations.show', compact('donation'));
    }

    public function updateStatus(Request $request, Donation $donation)
    {
        $user = auth()->user();
        $isPhotoInRequest = count($request->file());
        try {
            DB::beginTransaction();
            $donation->donation_status_id = (int)$request->status;
            $donation->save();
            DonationLog::Create($donation, $user);

            $donationAssigned = $donation->donation_status_id === Donation::ASSIGNED;
            $donationIncomplete = $donation->donation_status_id === Donation::INCOMPLETED;

            if ($donationAssigned) {
                $this->updateDonationFoodsStatus($request, $user, $donation, DonationFood::ASSIGNED);
            } else if ($donationIncomplete) {

                if ($isPhotoInRequest) {
                    $donationFood = DonationFood::find($request->donation_food_id);
                    $photo = $this->storePhoto($request, $donationFood->food_id);


                    $donationFoodAssigned = in_array($donationFood->food_donation_status_id, [DonationFood::ASSIGNED, DonationFood::ADJUSTED_AFTER_ASSIGNED]);

                    $donationFoodTaken = in_array($donationFood->food_donation_status_id, [DonationFood::TAKEN, DonationFood::ADJUSTED_AFTER_TAKEN]);

                    if ($donationFoodAssigned) {
                        $donationFood->food_donation_status_id = DonationFood::TAKEN;
                        $donationFood->save();
                        FoodDonationLog::Create($donationFood, $user, $photo);

                        $foodDonationTakenReceipt = new FoodDonationTakenReceipt();
                        $foodDonationTakenReceipt->donation_assignment_id = $donationFood->donationAssignments->last()->id;
                        $foodDonationTakenReceipt->taken_amount = $donationFood->amount;
                        $foodDonationTakenReceipt->save();
                    } else if ($donationFoodTaken) {
                        $donationFood->food_donation_status_id = DonationFood::GIVEN;
                        $donationFood->save();
                        FoodDonationLog::Create($donationFood, $user, $photo);

                        $donationSchedule = DonationSchedule::where(['donation_food_id' => $donationFood->id]);
                        $donationSchedule->delete();

                        $receiptPhoto = $this->storeReceiptPhoto($request, $donationFood->food_id);

                        $foodDonationGivenReceipt = new FoodDonationGivenReceipt();
                        $foodDonationGivenReceipt->donation_assignment_id = $donationFood->donationAssignments->last()->id;
                        $foodDonationGivenReceipt->given_amount = $donationFood->amount;
                        $foodDonationGivenReceipt->receipt_photo = $receiptPhoto;
                        $foodDonationGivenReceipt->save();

                        $this->changeDonationToComplete($donation, $user);
                    }
                }
            }
            DB::commit();

            return redirect()->route('donations.show', compact('donation'));
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $planned = $donation->donation_status_id === Donation::PLANNED;
        $assigned = $donation->donation_status_id === Donation::ASSIGNED;
        $incompleted = $donation->donation_status_id === Donation::INCOMPLETED;


        try {
            if ($planned) {
                $this->returnFoods($donation);
                $donation->delete();
            } else if ($assigned) {
                $this->returnFoods($donation);
                $donation->delete();
            }
        } catch (\Exception $th) {
            throw $th;
        }

        return redirect()->route('donations.index');
    }

    private function returnFoods($donation)
    {
        foreach ($donation->donationFoods as $donationFood) {
            $food = Food::find($donationFood->food_id);
            $food->amount = $food->amount + $donationFood->amount;
            $food->save();

            $donationFood->delete();
        }
    }

    private function updateDonationFoodsStatus($request, $user, $donation, $status)
    {
        foreach ($donation->donationFoods as $donationFood) {
            $donationFood->food_donation_status_id = $status;
            $donationFood->save();

            $volunteerID = $this->getVolunteerID($request, $donationFood->food_id);
            $vaultID = $this->getVaultID($request, $donationFood->food_id);

            FoodDonationLog::Create($donationFood, $user, $donationFood->food->photo);
            DonationAssignment::Create($volunteerID, $vaultID, $user, $donationFood);
            DonationSchedule::Create($volunteerID, $donationFood);
        }
    }

    private function filterDonationByStatus($donation, $arrDonationStatusID)
    {
        $filtered = $donation->filter(function ($donation) use ($arrDonationStatusID) {
            $status = request()->query('status');
            if ($status === null) {
                return in_array($donation->donation_status_id, $arrDonationStatusID);
            }
            return $donation->donation_status_id === (int) request()->query('status');
        });

        return $filtered;
    }

    private function filterSearch($collections, $filterValue)
    {
        $filtered = $collections->filter(function ($f) use ($filterValue) {
            return str_contains(strtolower($f->title), strtolower($filterValue));
        });

        return $filtered;
    }

    private function idleVolunteers($volunteers, $donation, $maxFoodDonationInAday)
    {
        $filtered = $volunteers->filter(function ($volunteer) use ($donation, $maxFoodDonationInAday) {
            // dateformat to 2023-12-23
            $donation_date = Carbon::parse($donation->donation_date)->format('Y-m-d');

            // volunteer hanya bisa handle 1 food dalam suatu hari
            return DonationSchedule::whereDate('donation_date', $donation_date)->where('user_id', $volunteer->id)->count() < $maxFoodDonationInAday;
        });

        return $filtered;
    }

    private function getVolunteerID($request, $foodID)
    {
        return $request["food-$foodID-volunteer_id"];
    }

    private function getVaultID($request, $foodID)
    {
        return $request["food-$foodID-vault_id"];
    }

    private function changeDonationToComplete($donation, $user)
    {
        $allfoodStored = true;

        foreach ($donation->donationFoods as $donationFood) {
            $foodNotRejectedNorCanceled = !in_array($donationFood->food_donation_status_id, [DonationFood::CANCELED]);

            $foodHasNotBeenStored = !in_array($donationFood->food_donation_status_id, [DonationFood::GIVEN]);

            if ($foodNotRejectedNorCanceled && $foodHasNotBeenStored) {
                $allfoodStored = false;
            }
        }

        if ($allfoodStored) {
            $donation->donation_status_id = Donation::COMPLETED;
            $donation->save();
            DonationLog::Create($donation, $user);
        }
    }

    private function storePhoto($request, $foodID)
    {
        $photoURL = $request->file("$foodID-photo")->store('donation-documentations');
        return $photoURL;
    }

    private function storeReceiptPhoto($request, $foodID)
    {
        $photoURL = $request->file("receipt-$foodID-photo")->store('receipt-documentations');
        return $photoURL;
    }
}
