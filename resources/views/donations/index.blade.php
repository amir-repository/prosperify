@extends('layouts.manager.index')

@section('main')
    <main class="flex flex-col p-4 gap-4">
        <div x-data="{ filter: false, search: true }">
            <section class="flex items-center gap-6">
                <div>
                    <button @click="filter=!filter; search=false" class="flex items-center gap-2">
                        <x-heroicon-o-adjustments-horizontal class="w-6 h-6" /> Filter
                    </button>
                </div>
                <div @click="search=!search; filter=false" class="cursor-pointer">
                    <p class="flex gap-2">
                        <x-heroicon-o-magnifying-glass class="w-6 h-6" /> Search
                    </p>
                </div>
            </section>
            <section>
                <div x-show="filter">
                    <form class="flex items-center gap-11 my-4" action="{{ route('donations.index') }}" method="get">
                        <input type="text" name="status"
                            value={{ request()->query('status') ? request()->query('status') : '1' }} hidden>
                        <label for="urgent">
                            <input type="checkbox" name="urgent" id="urgent" @checked(request()->query('urgent'))>
                            Urgent
                        </label>
                        <label for="high-amount">
                            <input type="checkbox" name="high-amount" id="high-amount" @checked(request()->query('high-amount'))>
                            High amount
                        </label>
                        <button class="px-4 py-1 bg-slate-900 text-white rounded-md text-sm" type="submit">Filter</button>
                    </form>
                </div>
                <div x-show="search">
                    <form action="" method="get" class="flex items-center gap-2 mt-4">
                        <input type="text" name="status"
                            value="{{ request()->query('status') ? request()->query('status') : 1 }}" hidden>
                        <input type="text" placeholder="Search" name="q"
                            class="rounded-full text-sm h-8 px-4 w-full">
                        <div hidden>
                            <input type="checkbox" name="urgent" id="urgent" @checked(request()->query('urgent')) hidden>
                            <input type="checkbox" name="high-amount" id="high-amount" @checked(request()->query('high-amount'))>
                        </div>
                        <button>
                            <x-heroicon-o-magnifying-glass class="w-6 h-6" />
                        </button>
                    </form>
                </div>
            </section>
        </div>
        @if ($donations->isEmpty())
            <p class="font-medium text-center mt-16"> Belum ada donasi
            </p>
            <div class="flex justify-center">
                <a href="{{ route('donations.create') }}" class="py-2 px-4 bg-slate-900 text-white rounded-md">Buat baru</a>
            </div>
        @endif
        @foreach ($donations as $donation)
            <a href="{{ route('donations.show', ['donation' => $donation]) }}">
                <section class="border border-slate-200 p-6 rounded-md text-slate-900">
                    <h2 class="font-bold text-2xl">{{ $donation->title }}</h2>
                    <p class="mt-1">Pada {{ Carbon\Carbon::parse($donation->donation_date)->format('d M Y') }} jam
                        {{ Carbon\Carbon::parse($donation->donation_date)->format('H:i') }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        <div class="w-11 h-11 bg-slate-100 rounded-md flex items-center justify-center">
                            @if ($donation->donation_status_id === 1)
                                <x-heroicon-o-bookmark class="w-6 h-6" />
                            @elseif($donation->donation_status_id === 2)
                                <x-heroicon-o-cog class="w-6 h-6" />
                            @elseif($donation->donation_status_id === 3)
                                <x-heroicon-o-truck class="w-6 h-6" />
                            @elseif($donation->donation_status_id === 4)
                                <x-heroicon-o-gift class="w-6 h-6" />
                            @endif
                        </div>
                        <div>
                            <p><span class="capitalize">{{ $donation->donationStatus->name }}</span> oleh
                                {{ $donation->donationUsers->filter(fn($d) => $d->donation_status_id === $donation->donation_status_id)->first()->user->name }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ Carbon\Carbon::parse($donation->created_at)->format('d M Y') }}</p>
                        </div>
                    </div>
                </section>
            </a>
        @endforeach
    </main>
@endsection
