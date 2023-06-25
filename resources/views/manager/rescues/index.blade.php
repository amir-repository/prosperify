@extends('layouts.manager.index')

@section('main')
    <div class="px-4 mt-4">
        <form class="flex justify-center items-center gap-11" action="{{ route('rescues.index') }}" method="get">
            <input type="text" name="status"
                value={{ request()->query('status') ? request()->query('status') : 'diajukan' }} hidden>
            <label for="">
                <input type="checkbox" name="urgent" id="urgent" @checked(request()->query('urgent'))>
                Urgent
            </label>
            <label for="">
                <input type="checkbox" name="high-amount" id="high-amount" @checked(request()->query('high-amount'))>
                High amount
            </label>
            <button class="px-4 py-1 bg-blue-100 text-blue-800" type="submit">Filter</button>
        </form>
    </div>
    <main class="flex flex-col p-4 gap-4">
        @foreach ($rescues as $rescue)
            <a href="{{ route('rescues.show', ['rescue' => $rescue]) }}">
                <section class="border border-gray-400 p-2">
                    <div class="flex justify-between">
                        <div class="flex gap-4">
                            <p>📅 {{ Carbon\Carbon::parse($rescue->rescue_date)->format('d M Y') }}</p>
                            <p>⏰ {{ Carbon\Carbon::parse($rescue->rescue_date)->format('H:i') }}</p>
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
