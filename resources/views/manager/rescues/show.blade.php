@extends('layouts.manager.index')

@section('main')
    <main class="p-4">
        <section>
            <div class="flex justify-between">
                <div class="flex gap-2">
                    <p>📅 {{ Carbon\Carbon::parse($rescue->rescue_date)->format('d M Y') }}</p>
                    <p>⏰ {{ Carbon\Carbon::parse($rescue->rescue_date)->format('H:i') }}</p>
                </div>
                <h2 class="font-bold">{{ $rescue->donor_name }} 😇</h2>
            </div>
            <p class="mt-1">📍 {{ $rescue->pickup_address }}</p>
            <h1 class="capitalize text-2xl font-bold mt-4">{{ $rescue->title }}</h1>
            <p>{{ $rescue->description }}</p>

            <form action="{{ route('rescues.update', ['rescue' => $rescue]) }}" method="post">
                @method('put')
                @csrf
                <input type="datetime-local" value="{{ $rescue->rescue_date }}" name="rescue_date" hidden>
                <div>
                    <input type="text" name='status'
                        value="
                          @if ($rescue->status === 'diajukan') diproses 
                          @elseif ($rescue->status === 'diproses')diambil 
                          @elseif($rescue->status === 'diambil')disimpan 
                          @elseif($rescue->status === 'disimpan')selesai
                          @elseif($rescue->status === 'selesai') @endif"
                        hidden>
                </div>
                @if ($rescue->status !== 'selesai')
                    <button type="submit" class="w-full p-2 bg-blue-600 text-white font-bold mt-4">
                        @if ($rescue->status === 'diajukan')
                            Proses
                        @elseif ($rescue->status === 'diproses')
                            Ambil
                        @elseif ($rescue->status === 'diambil')
                            Simpan
                        @elseif ($rescue->status === 'disimpan')
                            Selesai
                        @else
                            Berhasil Diselamatkan
                        @endif
                    </button>
                @endif
            </form>
        </section>

        <section class="mt-11">
            <div class="flex justify-between mb-8">
                <h1 class="text-xl font-bold">Makanan</h1>
                @if ($rescue->status === 'direncanakan')
                    <a href="{{ route('rescues.foods.create', ['rescue' => $rescue]) }}"
                        class="p-1 px-2 text-blue-800 bg-blue-100 border-blue-400 border text-sm font-bold">Tambah
                        Makanan</a>
                @endif
            </div>
            <div>
                @if (!$rescue->foods)
                    <p class="text-center mt-10">Ayo tambahkan makanannya 🍎</p>
                @else
                    <main class="flex gap-4 flex-wrap justify-center">
                        @foreach ($rescue->foods as $food)
                            <a href="{{ route('rescues.foods.show', ['rescue' => $rescue, 'food' => $food]) }}">
                                <div class="w-[132px] border-gray-500 border">
                                    <img class="w-[132px] h-[132px] object-cover" src="{{ asset("storage/$food->photo") }}"
                                        alt="">
                                    <div class="p-2">
                                        <h2 class="text-xl">{{ $food->name }}</h2>
                                        <h3 class="font-bold">{{ $food->amount }} {{ $food->unit }}</h3>
                                        <h3 class="text-sm text-gray-600 capitalize mt-2">🎁 {{ $food->subCategory->name }}
                                        </h3>
                                        <h3 class="text-sm text-gray-600">⚠️
                                            {{ Carbon\Carbon::parse($food->expired_date)->format('d M Y') }}</h3>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </main>
                @endif
            </div>
        </section>
    </main>
@endsection
