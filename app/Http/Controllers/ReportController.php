<?php

namespace App\Http\Controllers;

use App\Models\DonationFood;
use App\Models\Food;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('is-admin')) {
            abort(403);
        }

        $status = $request->query('status');

        if ($status === 'food-rescued') {
            $food = Food::all()->map(function ($food) {
                return $food->amount;
            })->sum();
            dd($food);
        } else if ($status === 'food-donated') {
            $donationFood = DonationFood::all()->map(function ($donation) {
                return $donation->outbound_result;
            })->sum();
            dd($donationFood);
        } else if ($status === 'donors') {
            $donors = User::where('type', 'donor')->get()->count();
            dd($donors);
        }

        // jumlah donor
        // jumlah makanan yang telah di donorkan
        // food yang berhasil di rescue


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
