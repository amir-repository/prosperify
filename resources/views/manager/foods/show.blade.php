@extends('layouts.manager.index')

@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <div>
                <img class="h-36 w-full bg-slate-200 rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                    alt="">
            </div>
            <h1 class="text-2xl font-bold mt-3">{{ $food->name }}</h1>
            <div class="flex items-center gap-4 mt-3">
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-archive-box class="w-[18px] h-[18px]" />{{ $food->amount }}.{{ $food->unit->name }}
                </p>
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />Exp.
                    {{ Carbon\Carbon::parse($food->expired_date)->format('d M Y') }}

                </p>
            </div>
        </div>
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">Riwayat</h2>
            @foreach ($foodRescueUsers as $foodRescueUser)
                <section class="p-6 border border-slate-200 rounded-md mb-4 flex items-center gap-4">
                    <div>
                        <img class="w-[72px] h-[72px] rounded-md object-cover"
                            src="{{ asset("storage/$foodRescueUser->photo") }}" alt="">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">
                            {{ $foodRescueUser->amount }}.<span class="text-base">{{ $foodRescueUser->unit->name }}</span>
                        </h3>
                        <p class="text-slate-500">
                            {{ $foodRescueUser->user->name }}</p>
                        <p class="text-xs text-slate-500">
                            <span class="capitalize">{{ $foodRescueUser->rescueStatus->name }}</span>
                            {{ Carbon\Carbon::parse($foodRescueUser->created_at)->format('d M Y h:i:s') }}
                        </p>
                    </div>
                </section>
            @endforeach

        </div>
    </main>
    {{-- <main>
        <img class="w-full h-[375px] object-cover" src="{{ asset('storage/' . $rescuePhotos->first()->first()->photo) }}"
            alt="">
        <div class="p-4">
            <div class="flex justify-between">
                <h2 class="capitalize text-2xl font-bold">{{ $food->amount }} {{ $food->unit }}</h2>
                <p>⚠️ {{ Carbon\Carbon::parse($food->expired_date)->format('d M Y') }}</p>
            </div>
            <h3 class="mt-1 capitalize"><span class="text-lg">{{ $food->name }}</span> <span
                    class=" ml-1 text-xs py-[1px] px-2 bg-blue-50 text-blue-600 border border-blue-400">{{ $food->subCategory->name }}</span>
            </h3>
            <p class="mt-2">
                📣 {{ $food->detail }}</p>
        </div>
        <section class="p-4">
            <h2 class="text-lg font-bold">📸 Foto Dokumentasi</h2>
            @foreach ($rescuePhotos as $rescuePhoto)
                <div class="mt-4">
                    <label class="mb-2 text-sm">Kondisi
                        saat
                        {{ $rescuePhoto->first()->rescueUser->status }}</label>
                    <img class="w-[375px] h-[175px] object-cover"
                        src="{{ asset('storage/' . $rescuePhoto->first()->photo) }}" alt="" />
                </div>
            @endforeach
        </section>
        <section class="p-4">
            <form action="{{ route('rescues.foods.update', ['rescue' => $rescue, 'food' => $food]) }}" method="post"
                enctype="multipart/form-data">

                @method('put')
                @csrf

                @if ($rescue->status !== 'diajukan' && $rescue->status !== 'diproses' && $rescue->status !== 'selesai')
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Kondisi
                            saat {{ $rescue->status }}</label>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="file_input" type="file" name="photo">
                    </div>
                    <div class="mt-4">
                        <label for="">Jumlah pangan saat {{ $rescue->status }}</label>
                        <div>
                            <input type="number" name="amount" value="{{ $food->amount }}">
                            <span class="capitalize font-bold">{{ $food->unit }}</span>
                        </div>
                    </div>
                    <input type="text" name="status" value="{{ $rescue->status }}" hidden>
                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-4 mb-12">Simpan</button>
                @endif
            </form>
        </section>
    </main> --}}
@endsection
