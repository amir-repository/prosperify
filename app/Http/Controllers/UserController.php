<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
    public function show(User $user)
    {
        $foods = null;
        if ($user->hasRole('donor')) {
            $foods = $user->foods->whereNotNull('stored_at');
        } else if ($user->hasRole('volunteer')) {

            $foodRescued = collect([]);
            $foodRescue = $user->foodRescue->unique()->each(function ($rescue) use ($foodRescued) {
                $foodRescued->push($rescue->food);
            });
            $foods = $foodRescued;
        }

        return view('manager.users.show', ['user' => $user, 'foods' => $foods]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
