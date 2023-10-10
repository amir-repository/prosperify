@extends('layouts.manager.index')


@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <div>
                <img class="h-36 w-full bg-slate-200 rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                    alt="">
            </div>
            <div class="mt-3 flex items-center gap-2">
                <h1 class="text-2xl font-bold ">{{ $food->name }}</h1>
                <a href="{{ route('donations.foods.edit', compact('food', 'donation', 'donationFood')) }}">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                </a>
            </div>
            <p>{{ $food->detail }}</p>
            <div class="flex items-center gap-4 mt-3">
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-archive-box
                        class="w-[18px] h-[18px]" />{{ $donationFood->amount }}.{{ $food->unit->name }}
                </p>
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />Exp.
                    {{ $food->expired_date }}
                </p>
            </div>
            <div class="mt-6">
                <a href="{{ route('donations.foods.history', compact('donation', 'food')) }}"
                    class="px-4 py-2 border border-slate-300 rounded-md">Timeline</a>
            </div>
        </div>
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">History</h2>
            @foreach ($donationFood->foodDonationLogs as $food)
                <a href="{{ asset("storage/$food->photo") }}" target="_blank" rel="noopener noreferrer">
                    <section class="p-6 border border-slate-200 rounded-md mb-4 flex items-center gap-4">
                        <div>
                            <img class="w-[72px] h-[72px] rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                                alt="">
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">
                                {{ $food->amount }}.<span class="text-base">{{ $food->unit_name }}</span>
                            </h3>
                            <p class="text-slate-500">{{ $food->actor_name }}</p>
                            <p class="text-xs text-slate-500">
                                <span class="capitalize">
                                    {{ $food->food_donation_status_name }}
                                </span>
                                at
                                {{ $food->created_at }}
                            </p>
                        </div>
                    </section>
                </a>
            @endforeach
        </div>
    </main>
@endsection
