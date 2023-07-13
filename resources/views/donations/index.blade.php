@extends('layouts.manager.index')

@section('main')
    <main class="flex flex-col p-4 gap-4">
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
