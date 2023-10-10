<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Volunteer;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use App\Models\Donation;
use App\Models\DonationLog;
use App\Models\Food;
use App\Models\Recipient;
use App\Models\User;
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
            $userID = auth()->user()->id;
            $donation = Donation::all();
            $filtered = $this->filterDonationByStatus($donation, [Donation::PLANNED]);

            if ($request->query('q')) {
                $filtered = $this->filterSearch($filtered, $request->query('q'));
            }

            return view('donation.index', ['donations' => $filtered]);
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
        $volunteers = User::role(User::VOLUNTEER)->get();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $planned = $donation->donation_status_id === Donation::PLANNED;

        try {
            if ($planned) {
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
        }
    }

    private function filterDonationByStatus($donation, $arrDonationStatusID)
    {
        $filtered = $donation->filter(function ($donation) use ($arrDonationStatusID) {
            $status = request()->query('status');
            if ($status === null) {
                return in_array($donation->donation_status_id, $arrDonationStatusID);
            }
            return $donation->rescue_status_id === (int) request()->query('status');
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
}
