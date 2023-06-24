@extends('layouts.manager.index')

@section('main')
    <main class="flex flex-col p-4 gap-4">
        @foreach ($donations as $donation)
            <a href="{{ route('donations.show', ['donation' => $donation]) }}">
                <section class="border border-gray-400 p-2">
                    <div class="flex justify-between">
                        <p>📅 {{ Carbon\Carbon::parse($donation->donation_date)->format('d M Y') }}</p>
                        <p>😊 {{ $donation->recipient->name }}</p>
                    </div>
                    <h1 class="capitalize text-2xl font-bold mt-2">{{ $donation->title }}</h1>
                    <p class="capitalize font-bold text-red-600">🔔 {{ $donation->status }}</p>
                    <p class="mt-2">📍 {{ $donation->recipient->address }}</p>
                </section>
            </a>
        @endforeach
    </main>
@endsection
