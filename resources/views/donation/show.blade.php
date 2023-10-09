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
                <input type="text" value="2" name="status" hidden>
                <Button class="py-2 w-full rounded-md bg-slate-900 mt-8 text-sm font-medium text-white">Assign</Button>
            </section>
        </div>
    </main>
@endsection
