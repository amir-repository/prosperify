<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Models\DonationLog;
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

            $donationLog = new DonationLog();
            $donationLog->donation_id = $donation->id;
            $donationLog->donation_status_id = $donation->donation_status_id;
            $donationLog->donation_status_name = $donation->donationStatus->name;
            $donationLog->actor_id = $user->id;
            $donationLog->actor_name = $user->name;
            $donationLog->title = $donation->title;
            $donationLog->description = $donation->description;
            $donationLog->donation_date = $donation->donation_date;
            $donationLog->user_id = $donation->user_id;
            $donationLog->save();
            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('donations.index');

        // create donation
        // create donation logs
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        //
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
