@extends('layouts.manager.index')

@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <div>
                <div class="flex items-center justify-between">
                    <a href="{{ route('donations.edit', compact('donation')) }}" class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold">{{ $donation->title }}</h1>
                        <x-heroicon-o-pencil-square class="w-[18px] h-[18px]" />
                    </a>
                </div>
                <div class="mt-3 flex items-center gap-1 text-slate-500">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
                    <p class="text-sm">Created at {{ $donation->created_at }}
                </div>
                <div class="mt-3 flex items-center gap-1 text-slate-500 capitalize">
                    @include('donation.partials.status-icon')
                    <p class="text-sm"> {{ $donation->donationStatus->name }}
                </div>
            </div>
        </div>
        <div class="mt-3">
            <section class="flex items-center gap-2">
                <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                    <x-heroicon-o-user class="w-6 h-6" />
                </div>
                <div>
                    <p>{{ $donation->recipient->name }}</p>
                    <p class="text-xs text-slate-500 capitalize">Recipient - {{ $donation->recipient->phone }}</p>
                </div>
            </section>
            <section class="mt-6">
                <h2 class="text-lg font-bold">Donation Date</h2>
                <div class="mt-2 flex items-center gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
                    <p class="text-sm">{{ $donation->donation_date_humanize }}</p>
                </div>
                <div class="mt-2 flex items-center gap-1">
                    <x-heroicon-o-map-pin class="w-[18px] h-[18px]" />
                    <p class="text-sm">{{ $donation->recipient->address }}</p>
                </div>
            </section>
            <section>
                @unless ($donationFoods->isEmpty())
                    <input type="text" value="2" name="status" hidden>
                    <Button class="py-2 w-full rounded-md bg-slate-900 mt-8 text-sm font-medium text-white">Assign</Button>
                @endunless
            </section>
        </div>
        <div class="flex items-center justify-between mt-6">
            <h2 class="text-lg font-bold">Foods</h2>
            <a href="{{ route('donations.foods.create', compact('donation')) }}" class="flex items-center gap-1">
                <x-heroicon-o-plus class="w-5 h-5" />Add
            </a>
        </div>
        <section class="mt-6">
            {{-- food card --}}
            @foreach ($donationFoods as $donationFood)
                @php
                    $food = $donationFood->food;
                    $foodDonationLog = $donationFood->foodDonationLogs->last();
                @endphp
                <a href="{{ route('donations.foods.show', compact('donation', 'food', 'donationFood')) }}">
                    <section class="p-6 border border-slate-200 rounded-md mb-4">
                        <div class="flex items-center gap-4">
                            <div>
                                <img class="w-[72px] h-[72px] rounded-md object-cover"
                                    src="{{ asset("storage/$food->photo") }}" alt="">
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold">
                                    {{ $donationFood->amount }}.<span class="text-base">{{ $food->unit->name }}</span>
                                </h3>
                                <p class="text-slate-500">{{ $food->name }}</p>
                                <p class="text-xs text-slate-500">
                                    Exp. {{ $food->expired_date }}
                                </p>
                            </div>
                        </div>
                        <section class="flex items-center gap-2 mt-4">
                            <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                                @include('donation.partials.food-status')
                            </div>
                            <div>
                                <p>
                                    <span class="capitalize">
                                        {{ $foodDonationLog->food_donation_status_name }}
                                        By
                                        {{ $foodDonationLog->actor_name }}
                                    </span>
                                </p>
                                <p class="text-xs text-slate-500 capitalize">
                                    At
                                    2023
                                </p>
                            </div>
                        </section>
                        {{-- assignment --}}
                        <section class="flex justify-between">
                            <div>
                                <p class="mt-4 text-sm font-medium">Volunteer
                                </p>
                                <div class="mt-2">
                                    <select class="rounded-md border border-slate-300"
                                        name="food-{{ $food->id }}-volunteer_id" id="volunteer" required>
                                        @foreach ($volunteers as $volunteer)
                                            <option value="{{ $volunteer->id }}">
                                                {{ $volunteer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <p class="mt-4 text-sm font-medium">Vaults
                                </p>
                                <div class="mt-2">
                                    <select class="rounded-md border border-slate-300"
                                        name="food-{{ $food->id }}-vault_id" id="vault" required>
                                        <option value="{{ $food->vault_id }}">{{ $food->vault->name }}</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                        {{-- assignment --}}
                    </section>
                </a>
            @endforeach
            {{-- food card --}}
        </section>
    </main>
@endsection
