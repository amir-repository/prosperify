@extends('layouts.manager.index')

@section('main')
    <main>
        <img class="w-full h-[375px] object-cover" src="{{ asset('storage/' . $food->photo) }}" alt="">
        <div class="p-4">
            <div class="flex justify-between">
                <h2 class="capitalize text-2xl font-bold">
                    {{ $food->donations->first()->pivot->outbound_plan }}
                    {{ $food->unit }}</h2>
                <p>⚠️ {{ Carbon\Carbon::parse($food->expired_date)->format('d M Y') }}</p>
            </div>
            <h3 class="mt-1 capitalize"><span class="text-lg">{{ $food->name }}</span> <span
                    class=" ml-1 text-xs py-[1px] px-2 bg-blue-50 text-blue-600 border border-blue-400">{{ $food->subCategory->name }}</span>
            </h3>
            <p class="mt-2">
                📣 {{ $food->detail }}</p>
            😇 Donasi pangan dari {{ $food->user->name }}</p>
        </div>
        <section class="p-4">
            <h2 class="text-lg font-bold">📸 Foto Dokumentasi</h2>
            @foreach ($donationPhotos as $donationPhoto)
                <div class="mt-4">
                    <label class="mb-2 text-sm">Kondisi
                        saat
                        {{ $donationPhoto->first()->donationUser->status }}</label>
                    <img class="w-[375px] h-[175px] object-cover"
                        src="{{ asset('storage/' . $donationPhoto->first()->photo) }}" alt="" />
                </div>
            @endforeach
        </section>

        <section class="p-4">
            <form action="{{ route('donations.foods.update', ['donation' => $donation, 'food' => $food]) }}" method="post"
                enctype="multipart/form-data">

                @method('put')
                @csrf

                @if ($donation->status !== 'direncanakan' && $donation->status !== 'selesai')
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Kondisi
                            saat {{ $donation->status }}</label>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="file_input" type="file" name="photo">
                    </div>
                    <input type="text" name="status" value="{{ $donation->status }}" hidden>
                    <div class="mt-4">
                        <label for="outbound_amount">Jumlah makanan saat {{ $donation->status }}</label>
                        <div>
                            <input type="number" name="outbound_amount" id="outbound_amount"
                                value="{{ $food->donations->first()->pivot->outbound_plan }}">
                            <span class="capitalize font-bold">{{ $food->unit }}</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full p-2 bg-blue-600 text-white font-bold mt-4">Simpan</button>
                @endif
            </form>
        </section>
    </main>
@endsection
