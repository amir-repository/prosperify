@extends('layouts.manager.index')

@section('main')
    <main class="p-6 border border-black m-6 rounded-md">
        <p class="text-right">Donation ID: {{ $takenReceipt->donationAssignment->donation_id }}</p>
        <p class="text-right">Receipt Date: {{ $takenReceipt->created_at }}</p>
        <div class="flex justify-between mt-4">
            <div class="text-center">
                <h1>Food. #{{ $takenReceipt->donationAssignment->donationFood->food->id }}</h1>
                <p class="text-2xl font-bold">{{ $takenReceipt->donationAssignment->donationFood->food->name }}</p>
            </div>
            <div class="text-center">
                <h1>Taken Amount</h1>
                <p class="text-2xl font-bold">
                    {{ $takenReceipt->taken_amount }}.<span
                        class="text-lg">{{ $takenReceipt->donationAssignment->donationFood->food->unit->name }}</span>
                </p>
            </div>
        </div>
        <hr class="my-6 border-black">
        <section>
            <div>
                <h2>Assigner. #{{ $takenReceipt->donationAssignment->assigner_id }}</h2>
                <p class="text-xl font-bold">{{ $takenReceipt->donationAssignment->assigner->name }}</p>
                <img class="w-64" src="{{ asset('storage/' . $takenReceipt->admin_signature) }}">
            </div>
            <div class="mt-6">
                <h2>Volunteer. #{{ $takenReceipt->donationAssignment->volunteer->id }}</h2>
                <p class="text-xl font-bold">{{ $takenReceipt->donationAssignment->volunteer->name }}</p>
            </div>
            <div class="mt-6">
                <h2>Donor. #{{ $takenReceipt->donationAssignment->donationFood->food->rescue->user_id }}</h2>
                <p class="text-xl font-bold">
                    {{ $takenReceipt->donationAssignment->donationFood->food->rescue->donor_name }}
                </p>
            </div>
            <div class="mt-6">
                <h2>Vault. #{{ $takenReceipt->donationAssignment->vault->id }}</h2>
                <p class="text-xl font-bold">{{ $takenReceipt->donationAssignment->vault->name }}</p>
            </div>
            <div class="mt-6">
                <h2>Recipient. #{{ $takenReceipt->donationAssignment->donationFood->donation->recipient_id }}</h2>
                <p class="text-xl font-bold">
                    {{ $takenReceipt->donationAssignment->donationFood->donation->recipient->name }}</p>
            </div>
        </section>
    </main>
@endsection
