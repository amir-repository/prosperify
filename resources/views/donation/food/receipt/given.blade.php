@extends('layouts.manager.index')

@section('main')
    <main class="p-6 border border-black m-6 rounded-md">
        <p class="text-right">Donation ID: {{ $givenReceipt->donationAssignment->donation_id }}</p>
        <p class="text-right">Receipt Date: {{ $givenReceipt->created_at }}</p>
        <div class="flex justify-between mt-4">
            <div class="text-center">
                <h1>Food. #{{ $givenReceipt->donationAssignment->donationFood->food->id }}</h1>
                <p class="text-2xl font-bold">{{ $givenReceipt->donationAssignment->donationFood->food->name }}</p>
            </div>
            <div class="text-center">
                <h1>Given Amount</h1>
                <p class="text-2xl font-bold">
                    {{ $givenReceipt->given_amount }} <span
                        class="text-lg">{{ $givenReceipt->donationAssignment->donationFood->food->unit->name }}</span>
                </p>
            </div>
        </div>
        <hr class="my-6 border-black">
        <section>
            <div>
                <h2>Assigner. #{{ $givenReceipt->donationAssignment->assigner_id }}</h2>
                <p class="text-xl font-bold">{{ $givenReceipt->donationAssignment->assigner->name }}</p>
            </div>
            <div class="mt-6">
                <h2>Volunteer. #{{ $givenReceipt->donationAssignment->volunteer->id }}</h2>
                <p class="text-xl font-bold">{{ $givenReceipt->donationAssignment->volunteer->name }}</p>
            </div>
            <div class="mt-6">
                <h2>Donor. #{{ $givenReceipt->donationAssignment->donationFood->food->rescue->user_id }}</h2>
                <p class="text-xl font-bold">
                    {{ $givenReceipt->donationAssignment->donationFood->food->rescue->donor_name }}
                </p>
            </div>
            <div class="mt-6">
                <h2>Vault. #{{ $givenReceipt->donationAssignment->vault->id }}</h2>
                <p class="text-xl font-bold">{{ $givenReceipt->donationAssignment->vault->name }}</p>
            </div>
            <div class="mt-6">
                <h2>Recipient. #{{ $givenReceipt->donationAssignment->donationFood->donation->recipient_id }}</h2>
                <p class="text-xl font-bold">
                    {{ $givenReceipt->donationAssignment->donationFood->donation->recipient->name }}</p>
                <img class="w-64" src="{{ asset('storage/' . $givenReceipt->recipient_signature) }}" alt=""
                    srcset="">
            </div>
        </section>
    </main>
@endsection
