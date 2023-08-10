<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaultRequest;
use App\Models\City;
use App\Models\Vault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaults = Vault::all();
        return view('vault.index', compact('vaults'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::all();
        return view('vault.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaultRequest $request)
    {
        $validated = $request->validated();
        $attr = $request->only('name', 'address', 'city_id');

        try {
            DB::beginTransaction();

            $vault = new Vault();
            $vault->fill($attr);
            $vault->save();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('vaults.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vault $vault)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vault $vault)
    {
        $cities = City::all();
        return view('vault.edit', compact('vault', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVaultRequest $request, Vault $vault)
    {
        $validated = $request->validated();
        $attr = $request->only('name', 'address', 'city_id');

        try {
            DB::beginTransaction();

            $vault->fill($attr);
            $vault->save();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            return $th;
        }

        return redirect()->route('vaults.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vault $vault)
    {
        $vault->delete();
        return redirect()->route('vaults.index');
    }
}
