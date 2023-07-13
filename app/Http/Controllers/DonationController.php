<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationFood;
use App\Models\DonationUser;
use App\Models\Food;
use App\Models\Recipient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $donations = (in_array($status, [Donation::DIRENCANAKAN, Donation::DILAKSANAKAN, Donation::DIANTAR, Donation::DISERAHKAN]))
            ?
            $donations = Donation::where('status', $status)->get()
            :
            $donations = Donation::all();


        return view('donations.index', ['donations' => $donations]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $recipients = Recipient::where('status', 'diterima')->get();
        return view('donations.create', ['user' => $user, 'recipients' => $recipients]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // save donation
            $donation = new Donation();
            $donation->title = $request->title;
            $donation->description = $request->description;
            $donation->donation_date = $this->formatDateTime($request->donation_date);
            $donation->recipient_id = $request->recipient_id;
            $donation->donation_status_id = Donation::DIRENCANAKAN;
            $donation->save();

            // save who's making the donation
            $donationUser = new DonationUser();
            $donationUser->user_id = auth()->user()->id;
            $donationUser->donation_id = $donation->id;
            $donationUser->donation_status_id = $donation->donation_status_id;
            $donationUser->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e);
        }

        return redirect()->route('donations.index');
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
                $foodOutbound = $food->pivot->outbound_result;
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

    function formatDateTime($dateTimeString)
    {
        $dateTime = explode('T', $dateTimeString);
        $date = explode('-', $dateTime[0]);
        $time = explode(':', $dateTime[1]);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];
        $hour = $time[0];
        $minute = $time[1];

        return Carbon::create($year, $month, $day, $hour, $minute, 0);
    }
}
