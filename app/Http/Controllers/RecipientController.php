<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipientRequest;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $recipients = Recipient::all();
        return view();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recipient.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipientRequest $request)
    {
        $validate = $request->validated();
        $attr = $request->only(['name', 'nik', 'address', 'phone', 'family_members']);
        $photo = $this->storePhoto($request);
        try {
            DB::beginTransaction();
            $recipient = new Recipient();
            $recipient->fill($attr);
            $recipient->recipient_status_id = Recipient::SUBMITTED;
            $recipient->save();
            DB::commit();
        } catch (\Exception $th) {
            Storage::delete($photo);
            DB::rollBack();
            return $th;
        }
        return to_route('recipients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipient $recipient)
    {
        return view('manager.recipients.show', ['recipient' => $recipient]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipient $recipient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipient $recipient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipient $recipient)
    {
        //
    }

    private function storePhoto($request)
    {
        $photoURL = $request->file("photo")->store('recipient-documentations');
        return $photoURL;
    }
}
