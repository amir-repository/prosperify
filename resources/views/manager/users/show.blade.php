@extends('layouts.manager.index')

@section('main')
    <main class="p-6">
        <section class="flex gap-2 items-center">
            <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex justify-center items-center">
                <x-heroicon-o-user class="w-6 h-6" />

            </div>
            <div>
                <h2 class="capitalize">{{ $user->name }}</h2>
                @if (!$user->point === null)
                    <p class="text-xs text-slate-500">{{ $user->point->point }} Point</p>
                @endif
            </div>
        </section>
        <section class="mt-4 text-slate-900 flex flex-col gap-2">
            <div class="flex items-center gap-1">
                <x-heroicon-o-phone class="w-[18px] h-[18px]" />
                <p class="text-sm">{{ $user->phone }}</p>
            </div>
            <div class="flex items-center gap-1">
                <x-heroicon-o-envelope class="w-[18px] h-[18px]" />
                <p class="text-sm">{{ $user->phone }}</p>
            </div>
            <div class="flex items-center gap-1">
                <x-heroicon-o-map-pin class="w-[18px] h-[18px]" />
                <p class="text-sm">{{ $user->address }}</p>
            </div>
        </section>
        <section class="mt-8">
            <h2 class="text-lg font-bold">Riwayat Pangan</h2>
            <div class="mt-4">
                @foreach ($foods as $food)
                    <a href="{{ route('foods.show', ['food' => $food]) }}">
                        <section class="p-6 border border-slate-200 rounded-md mb-4 flex items-center gap-4">
                            <div>
                                <img class="w-[72px] h-[72px] rounded-md object-cover"
                                    src="{{ asset("storage/$food->photo") }}" alt="">
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
                    </a>
                @endforeach
            </div>
        </section>
    </main>
@endsection
