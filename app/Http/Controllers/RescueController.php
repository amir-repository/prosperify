<?php

namespace App\Http\Controllers;

use App\Models\Rescue;
use App\Models\RescueUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RescueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $donor = Gate::allows('is-donor');
        $manager = Gate::allows('is-volunteer') || Gate::allows('is-volunteer');

        if ($donor) {
            $userID = auth()->user()->id;
            $rescues = User::find($userID)->rescues;

            $filtered = $rescues->filter(function ($rescues) {
                $status = request()->query('status');
                if ($status === null) {
                    return $rescues;
                }
                return $rescues->status === request()->query('status');
            });

            return view('rescues.index', ['rescues' => $filtered]);
        } else if ($manager) {
            $rescues = Rescue::all();

            $filtered = $rescues->filter(function ($rescues) {
                $status = request()->query('status');
                if ($status === null) {
                    return $rescues;
                }
                return $rescues->status === request()->query('status');
            });

            return view('manager.rescues.index', ['rescues' => $filtered]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('is-donor')) {
            abort(403);
        }

        $user = auth()->user();
        return view('rescues.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!Gate::allows('is-donor')) {
            abort(403);
        }

        $rescue = new Rescue();
        $rescue->donor_name = $request->donor_name;
        $rescue->pickup_address = $request->pickup_address;
        $rescue->phone = $request->phone;
        $rescue->email = $request->email;
        $rescue->title = $request->title;
        $rescue->description = $request->description;
        $rescue->status = "direncanakan";
        $rescue->rescue_date = $this->formatDateAndHour($request->rescue_date, $request->rescue_hours);
        $rescue->user_id = auth()->user()->id;
        $rescue->save();

        // save to log
        $rescue_user_log = new RescueUser();
        $rescue_user_log->user_id = auth()->user()->id;
        $rescue_user_log->rescue_id = $rescue->id;
        $rescue_user_log->status = $rescue->status;
        $rescue_user_log->save();

        return redirect()->route('rescues.show', ['rescue' => $rescue]);

        // return redirect()->route('donors.rescues.show', ['id' => $rescue->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rescue $rescue)
    {
        $donor = Gate::allows('is-donor');
        $manager = Gate::allows('is-volunteer') || Gate::allows('is-volunteer');

        if ($donor) {
            return view('rescues.show', ['rescue' => $rescue]);
        } else if ($manager) {
            return view('manager.rescues.show', ['rescue' => $rescue]);
        }
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
        $rescue->status = $request->status;
        $rescue->save();

        // save to logs
        $rescue_user_log = new RescueUser();
        $rescue_user_log->user_id = auth()->user()->id;
        $rescue_user_log->rescue_id = $rescue->id;
        $rescue_user_log->status = $request->status;
        $rescue_user_log->save();

        return redirect()->route("rescues.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rescue $rescue)
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