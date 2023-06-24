<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationFood;
use App\Models\DonationPhoto;
use App\Models\Food;
use Illuminate\Http\Request;

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
        $foods = Food::all();
        return view('donations.foods.create', ['foods' => $foods, 'donation' => $donation]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Donation $donation)
    {
        $donationFood = new DonationFood();
        $donationFood->food_id = $request->food_id;
        $donationFood->donation_id = $donation->id;
        $donationFood->outbound_plan = $request->outbound_plan;
        $donationFood->save();

        return redirect()->route('donations.show', ['donation' => $donation]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation, Food $food)
    {

        // get photo timeline for foods
        $donationUserIDs = $donation->users->map(function ($user) {
            return $user->pivot->id;
        });

        $donationPhotos = collect([]);
        foreach ($donationUserIDs as $donationUserID) {
            $donationPhoto = DonationPhoto::where(['donation_user_id' => $donationUserID, 'food_id' => $food->id])->get();
            if (!$donationPhoto->isEmpty()) {
                $donationPhotos->push($donationPhoto);
            }
        }

        return view('donations.foods.show', ['donation' => $donation, 'food' => $food, 'donationPhotos' => $donationPhotos]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation, Food $food)
    {
        $donationUserID = $donation->users->last()->pivot->id;
        $photo = $request->file('photo')->store('donation-documentations');

        $donationPhoto = new DonationPhoto();
        $donationPhoto->photo = $photo;
        $donationPhoto->user_id = auth()->user()->id;
        $donationPhoto->food_id = $food->id;
        $donationPhoto->donation_user_id = $donationUserID;
        $donationPhoto->save();

        return redirect()->route('donations.show', ['donation' => $donation]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        //
    }
}
