@extends('layouts.manager.index')

@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <div>
                <img class="h-36 w-full bg-slate-200 rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                    alt="">
            </div>
            <h1 class="text-2xl font-bold mt-3">{{ $food->name }}</h1>
            <p>{{ $food->detail }}</p>
            <div class="flex items-center gap-4 mt-3">
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-archive-box class="w-[18px] h-[18px]" />
                    {{ $donationFoodUsers->last()->amount }}.{{ $donationFoodUsers->last()->unit->name }}
                </p>
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />Exp.
                    {{ Carbon\Carbon::parse($food->expired_date)->format('d M Y') }}
                </p>
            </div>
            <div class="mt-4 flex gap-4">
                <form
                    class="block font-medium py-2 w-full text-center rounded-md text-red-700 text-sm border border-red-600 cursor-pointer"
                    action="{{ route('donations.foods.destroy', ['donation' => $donation, 'food' => $food]) }}"
                    method="post">
                    @csrf
                    @method('delete')
                    <button>Hapus</button>
                </form>
                <a href="{{ route('donations.foods.edit', ['donation' => $donation, 'food' => $food]) }}"
                    class="block font-medium py-2 w-full text-center bg-slate-900 rounded-md text-white  text-sm">Ubah</a>
            </div>
        </div>
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">Riwayat</h2>
            @foreach ($donationFoodUsers as $donationFoodUser)
                <section class="p-6 border border-slate-200 rounded-md mb-4 flex items-center gap-4">
                    <div>
                        <img class="w-[72px] h-[72px] rounded-md object-cover"
                            src="{{ asset("storage/$donationFoodUser->photo") }}" alt="">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">
                            {{ $donationFoodUser->amount }}.<span
                                class="text-base">{{ $donationFoodUser->unit->name }}</span>
                        </h3>
                        <p class="text-slate-500">
                            {{ $donationFoodUser->user->name }}</p>
                        <p class="text-xs text-slate-500">
                            <span class="capitalize">{{ $donationFoodUser->donationStatus->name }}</span>
                            {{ Carbon\Carbon::parse($donationFoodUser->created_at)->format('d M Y h:i:s') }}
                        </p>
                    </div>
                </section>
            @endforeach

        </div>
    </main>
@endsection
