<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationUser;
use App\Models\Food;
use App\Models\Recipient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DonationController extends Controller
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
    public function create()
    {
        $this->authorize('admin');
        $user = auth()->user();
        $recipients = Recipient::where('status', 'diterima')->get();
        return view('admin.donations.create', ['user' => $user, 'recipients' => $recipients]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // format date
        $donation_date = explode('/', $request->donation_date);
        $month = $donation_date[0];
        $day = $donation_date[1];
        $year = $donation_date[2];
        $donation_date = Carbon::create($year, $month, $day, 0, 0, 0, 'UTC');

        // save donation
        $donation = new Donation();
        $donation->title = $request->title;
        $donation->description = $request->description;
        $donation->donation_date = $donation_date;
        $donation->recipient_id = $request->recipientID;
        $donation->status = 'direncanakan';
        $donation->save();

        // save donation logs
        $donationLog = new DonationUser();
        $donationLog->user_id = auth()->user()->id;
        $donationLog->donation_id = $donation->id;
        $donationLog->status = $donation->status;
        $donationLog->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        //
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
        // ubah donation status
        $donation->status = $request->status;
        $donation->save();

        // if status is selesai, ambil jumlah outbound lalu kurangi dengan yang ada di utama
        if ($donation->status === 'selesai') {
            foreach ($donation->foods as $food) {
                $foodID = $food->pivot->food_id;

                $selectedFood = Food::find($foodID);
                $foodOutbound = $food->pivot->outbound_plan;
                $foodAmount = $selectedFood->amount;
                $amount = $foodAmount - $foodOutbound;
                $selectedFood->amount = $amount;
                $selectedFood->save();
            }
        }

        // add log ke donation food
        $donationUser = new DonationUser();
        $donationUser->user_id = auth()->user()->id;
        $donationUser->donation_id = $donation->id;
        $donationUser->status = $request->status;
        $donationUser->save();

        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        //
    }
}
