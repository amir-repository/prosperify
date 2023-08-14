<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationFood;
use App\Models\DonationFoodUser;
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
        $filtered = $this->filterDonationByStatus($status);

        if ($request->query('q')) {
            $filtered = $this->filterSearch($filtered, $request->query('q'));
        }

        $filtered = $this->filterPriority(request()->query('urgent'), request()->query('high-amount'), $filtered);

        return view('donations.index', ['donations' => $filtered]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $recipients = Recipient::where('recipient_status_id', Recipient::ACCEPTED)->get();
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
        $user = auth()->user();
        try {
            $donation->donation_status_id = $request->status;
            $donation->save();

            $donationUser = new DonationUser();
            $donationUser->donation_id = $donation->id;
            $donationUser->user_id = $user->id;
            $donationUser->donation_status_id = $donation->donation_status_id;
            $donationUser->save();

            foreach ($donation->foods as $food) {
                $donationFoodUser = new DonationFoodUser();
                $donationFoodUser->user_id = $user->id;
                $donationFoodUser->donation_food_id = $food->pivot->id;
                $donationFoodUser->amount = $food->pivot->amount_plan;
                $donationFoodUser->photo =
                    count($request->file()) === 0
                    ? $food->photo
                    : $this->storePhoto($request, $food->id);
                $donationFoodUser->donation_status_id = $donation->donation_status_id;
                $donationFoodUser->unit_id = $food->unit_id;
                $donationFoodUser->save();

                if ($donationFoodUser->donation_status_id == Donation::DISERAHKAN) {
                    $donationFood = DonationFood::find($food->pivot->id);
                    $donationFood->amount_result = $donationFoodUser->amount;
                    $donationFood->save();
                }
            }

            // if($donation->donation_status_id === Donation::DISERAHKAN) {
            //     // update semua food dalam donation ini di tabel donation_food, value amount result jadi value terakhir dari food_rescue_user log
            // }

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
    public function destroy(Donation $donation)
    {
        try {
            DB::beginTransaction();
            $donation->foods->each(function ($food) {
                // ignore the donation food that been soft deleted
                if ($food->pivot->deleted_at) {
                    return;
                }
                $food->in_stock = $food->in_stock + $food->pivot->amount_plan;
                $food->save();
            });
            $donation->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('donations.index');
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

    private function storePhoto($request, $foodID)
    {
        $photoURL = $request->file("$foodID-photo")->store('donation-documentations');
        return $photoURL;
    }

    private function filterDonationByStatus($status)
    {
        $donationStatus = [
            Donation::DIRENCANAKAN,
            Donation::DILAKSANAKAN,
            Donation::DIANTAR,
            Donation::DISERAHKAN
        ];
        return (in_array($status, $donationStatus))
            ?
            Donation::where('donation_status_id', $status)->get()
            :
            Donation::where('donation_status_id', Donation::DIRENCANAKAN)->get();
    }

    private function filterSearch($collections, $filterValue)
    {
        $filtered = $collections->filter(function ($f) use ($filterValue) {
            return str_contains(strtolower($f->title), strtolower($filterValue));
        });

        return $filtered;
    }

    private function filterPriority($urgent, $highAmount, $collections)
    {
        $filtered = $collections;
        if ($urgent === 'on' && $highAmount === 'on') {
            $filtered = $collections->sortBy([
                ['rescue_date', 'asc'],
                ['score', 'desc']
            ]);
        } else if ($urgent === 'on') {
            $filtered = $collections->sortBy('rescue_date');
        } else if ($highAmount === 'on') {
            $filtered = $collections->sortByDesc('score');
        } else {
            return $collections;
        }
        return $filtered;
    }
}
