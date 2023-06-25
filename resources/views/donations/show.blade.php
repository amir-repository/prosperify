@extends('layouts.manager.index')

@section('main')
    <main class="p-4">
        <section>
            <div class="flex justify-between">
                <div class="flex gap-2">
                    <p>📅 {{ Carbon\Carbon::parse($donation->donation_date)->format('d M Y') }}</p>
                </div>
                <h2 class="font-bold">{{ $donation->recipient->name }} 😇</h2>
            </div>
            <p class="mt-1">📍 {{ $donation->recipient->address }}</p>
            <h1 class="capitalize text-2xl font-bold mt-4">{{ $donation->title }}</h1>
            <p>{{ $donation->description }}</p>
            <form action="{{ route('donations.update', ['donation' => $donation]) }}" method="post">
                @method('put')
                @csrf
                <input type="text" name="status" id="status"
                    value="
            @if ($donation->status === 'direncanakan') berlangsung
            @elseif ($donation->status === 'berlangsung') diserahkan
            @else
                selesai @endif
            "
                    hidden>

                @if (!$donationFoods->isEmpty())
                    <button type="submit" class="w-full p-2 bg-blue-600 text-white font-bold mt-4"
                        @disabled($donation->status === 'selesai')>

                        @if ($donation->status === 'direncanakan')
                            Laksanakan
                        @elseif ($donation->status === 'berlangsung')
                            Lanjut serahkan
                        @else
                            Selesai
                        @endif

                    </button>
                @endif

            </form>
        </section>
        <section class="mt-11">
            <div class="flex justify-between mb-8">
                <h1 class="text-xl font-bold">Makanan</h1>
                @if ($donation->status === 'direncanakan')
                    <a href="{{ route('donations.foods.create', ['donation' => $donation]) }}"
                        class="p-1 px-2 text-blue-800 bg-blue-100 border-blue-400 border text-sm font-bold">Tambah
                        Makanan</a>
                @endif
            </div>
        </section>
        <section>
            <main class="flex gap-4 flex-wrap justify-center">
                @if ($donationFoods->isEmpty())
                    <p>Tambahkan makanan yang ingin di donasikan 🍎</p>
                @else
                    @foreach ($donationFoods as $donationFood)
                        <a
                            href="{{ route('donations.foods.show', ['donation' => $donation, 'food' => $donationFood->food->id]) }}">
                            <div class="cursor-pointer w-[132px] border-gray-500 border">
                                <img class="w-[132px] h-[132px] object-cover"
                                    src="{{ asset('storage/' . $donationFood->food->photo) }}" alt="">
                                <div class="p-2">
                                    <h2 class="text-xl">{{ $donationFood->food->name }}</h2>
                                    <h3 class="font-bold">
                                        @if ($donationFood->outbound_result === null)
                                            {{ $donationFood->outbound_plan }}
                                        @else
                                            {{ $donationFood->outbound_result }}
                                        @endif
                                        {{ $donationFood->food->unit }}
                                    </h3>
                                    <h3 class="text-sm text-gray-600 capitalize mt-2">🎁
                                        {{ $donationFood->food->subCategory->name }}
                                    </h3>
                                    <h3 class="text-sm text-gray-600">⚠️
                                        {{ Carbon\Carbon::parse($donationFood->food->expired_date)->format('d M Y') }}</h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif


            </main>

        </section>
    </main>
@endsection
