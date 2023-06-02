<?php

namespace App\Http\Controllers;

use App\Models\Rescue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RescueController extends Controller
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
        $this->authorize('donor');
        $user = auth()->user();
        return view('rescues.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Find correct time stamp to store in DB
        $rescue_date = explode('/', $request->rescue_date);
        $month = $rescue_date[0];
        $day = $rescue_date[1];
        $year = $rescue_date[2];
        $hour = $request->rescue_hours;
        $timestamp = Carbon::create($year, $month, $day, $hour, 0, 0, 'UTC');

        $rescue = new Rescue();
        $rescue->donor_name = $request->donor_name;
        $rescue->pickup_address = $request->pickup_address;
        $rescue->phone = $request->phone;
        $rescue->email = $request->email;
        $rescue->title = $request->title;
        $rescue->description = $request->description;
        $rescue->status = "direncanakan";
        $rescue->rescue_date = $timestamp;
        $rescue->user_id = auth()->user()->id;
        $rescue->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Rescue $rescue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rescue $rescue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rescue $rescue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rescue $rescue)
    {
        //
    }
}
