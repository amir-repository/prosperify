@extends('layouts.manager.index')

@section('main')
    <main class="p-6">
        <h2 class="text-2xl font-bold mb-4">{{ $header }}</h2>
        @foreach ($rescuedFoods as $food)
            <a href="{{ route('foods.show', ['food' => $food]) }}">

                <section class="p-6 border border-slate-200 rounded-md mb-4 flex items-center gap-4">
                    <div>
                        <img class="w-[72px] h-[72px] rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                            alt="">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">
                            {{ $food->amount }}.<span class="text-base">{{ $food->unit->name }}</span>
                        </h3>
                        <p class="text-slate-500">{{ $food->name }}</p>
                        <p class="text-xs text-slate-500">
                            Exp. {{ Carbon\Carbon::parse($food->expired_date)->format('d M Y') }}</p>
                    </div>
                </section>
        @endforeach
        </a>

    </main>
@endsection
