<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipientRequest;
use App\Models\Recipient;
use App\Models\RecipientLog;
use App\Models\RecipientLogNote;
use App\Models\RecipientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status') ? $request->query('status') : Recipient::SUBMITTED;
        $recipients = Recipient::where('recipient_status_id', $status)->get();
        return view('recipient.index', compact('recipients'));
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
        $validated = $request->validated();

        $user = auth()->user();
        $attr = $request->only(['name', 'nik', 'address', 'phone', 'family_members']);
        $photo = $this->storePhoto($request);
        try {
            DB::beginTransaction();
            $recipient = new Recipient();
            $recipient->fill($attr);
            $recipient->recipient_status_id = Recipient::SUBMITTED;
            $recipient->photo = $photo;
            $recipient->save();

            RecipientLog::Create($recipient, $user);

            DB::commit();
        } catch (\Exception $th) {
            Storage::delete($photo);
            DB::rollBack();
            return $th;
        }

        return redirect()->route('recipients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipient $recipient)
    {
        $recipientLogs = RecipientLog::where('recipient_id', $recipient->id)->get();
        return view('manager.recipients.show', compact('recipient', 'recipientLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipient $recipient)
    {
        return view('recipient.edit', compact('recipient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipient $recipient)
    {
        $recipientStatus = (int)$request->recipient_status_id;
        $user = auth()->user();

        $recipientCanceledOrRejected = in_array($recipientStatus, [Recipient::CANCELED, Recipient::REJECTED]);

        try {
            DB::beginTransaction();
            if ($recipientStatus ===  $recipient->recipient_status_id) {
                $recipient->name = $request->name;
                $recipient->address = $request->address;
                $recipient->phone = $request->phone;
                $recipient->family_members = $request->family_members;
                $recipient->photo = $this->storePhoto($request);
            }
            $recipient->recipient_status_id = $request->recipient_status_id;
            $recipient->save();

            $recipientLog = RecipientLog::Create($recipient, $user);

            if ($recipientCanceledOrRejected) {
                $recipientLogNote = new RecipientLogNote();
                $recipientLogNote->recipient_log_id = $recipientLog->id;
                $recipientLogNote->note = $request->note;
                $recipientLogNote->save();
            }

            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
        return redirect()->route('recipients.show', compact('recipient'));
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
