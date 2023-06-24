@extends('layouts.manager.index')

@section('main')
    <main class="flex flex-col p-4 gap-4">
        @foreach ($rescues as $rescue)
            <a href="{{ route('rescues.show', ['rescue' => $rescue]) }}">
                <section class="border border-gray-400 p-2">
                    <div class="flex justify-between">
                        <div class="flex gap-4">
                            <p>📅 {{ Carbon\Carbon::parse($rescue->recue_date)->format('d M Y') }}</p>
                            <p>⏰ {{ Carbon\Carbon::parse($rescue->recue_date)->format('H:i') }}</p>
                        </div>
                        <p class="capitalize font-bold">{{ $rescue->donor_name }} 😇</p>
                    </div>
                    <h1 class="capitalize text-2xl font-bold mt-2">{{ $rescue->title }}</h1>
                    <p class="capitalize font-bold text-red-600">🔔 {{ $rescue->status }}</p>
                    <p class="mt-2">📍 {{ $rescue->pickup_address }}</p>
                </section>
            </a>
        @endforeach
    </main>
@endsection
