<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationFood;
use App\Models\DonationUser;
use App\Models\Food;
use App\Models\Recipient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $manager = Gate::allows('is-admin') || Gate::allows('is-volunteer');
        if (!$manager) {
            abort(403);
        }

        $donationStatus = ['direncanakan', 'berlangsung', 'diserahkan', 'selesai'];
        $status = $request->query('status');
        $donations = null;

        if (in_array($status, $donationStatus)) {
            $donations = Donation::where('status', $status)->get();
        } else {
            $donations = Donation::all();
        }

        return view('donations.index', ['donations' => $donations]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admin = Gate::allows('is-admin');

        if ($admin) {
            $user = auth()->user();
            $recipients = Recipient::where('status', 'diterima')->get();
            return view('donations.create', ['user' => $user, 'recipients' => $recipients]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // save donation
        $donation = new Donation();
        $donation->title = $request->title;
        $donation->description = $request->description;
        $donation->donation_date = $this->formatDateAndHour($request->donation_date, 0);
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
        $donationFoods = DonationFood::where('donation_id', $donation->id)->get();
        return view('donations.show', ['donation' => $donation, 'donationFoods' => $donationFoods]);
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

        return redirect()->route('donations.show', ['donation' => $donation]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        //
    }

    public function formatDateAndHour($datestring, $hour)
    {
        $rescue_date = explode('-', $datestring);
        $year = $rescue_date[0];
        $month = $rescue_date[1];
        $day = $rescue_date[2];
        $timestamp = Carbon::create($year, $month, $day, $hour, 0, 0, 'UTC');
        return $timestamp;
    }
}
