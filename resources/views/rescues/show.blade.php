@extends('layouts.index')

@section('main')
    <main class="p-6 text-slate-900">
        <h1 class="text-2xl font-bold">{{ $rescue->title }}</h1>
        <div class="mt-4 text-sm">
            <div class="flex gap-4">
                <p class="flex items-center gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
                    {{ Carbon\Carbon::parse($rescue->rescue_date)->format('d M Y') }}
                </p>
                <p class="flex gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
                    {{ Carbon\Carbon::parse($rescue->rescue_date)->format('H:i') }}
                </p>
            </div>
            <p class="mt-2 flex gap-1">
                <x-heroicon-o-map-pin class="w-[18px] h-[18px]" />{{ $rescue->pickup_address }}
            </p>
        </div>
        <form action="{{ route('rescues.update', ['rescue' => $rescue]) }}" method="post" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="mt-6">
                <section class="flex items-center gap-2">
                    <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                        <x-heroicon-o-user class="w-6 h-6" />
                    </div>
                    <div>
                        <p>{{ $rescue->donor_name }}</p>
                        <p class="text-xs text-slate-500 capitalize">Donor</p>
                    </div>
                </section>
                <section class="flex items-center gap-2 mt-4">
                    <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                        @if ($rescue->rescue_status_id === 1)
                            <x-heroicon-o-bookmark class="w-6 h-6" />
                        @elseif($rescue->rescue_status_id === 2)
                            <x-heroicon-o-paper-airplane class="w-6 h-6" />
                        @elseif($rescue->rescue_status_id === 3)
                            <x-heroicon-o-cog class="w-6 h-6" />
                        @elseif($rescue->rescue_status_id === 4)
                            <x-heroicon-o-truck class="w-6 h-6" />
                        @endif
                    </div>
                    <div>
                        <p><span class="capitalize">{{ $rescue->rescueStatus->name }}</span> oleh
                            {{ $rescue->rescueUser->filter(fn($r) => $r->rescue_status_id === $rescue->rescue_status_id)->first()->user->name }}
                        </p>
                        <p class="text-xs text-slate-500 capitalize">
                            {{ $rescue->rescueUser->filter(fn($r) => $r->rescue_status_id === $rescue->rescue_status_id)->first()->user->roles->first()->name }}
                        </p>
                    </div>
                </section>
                <section>

                    <input type="text" value="2" name="status" hidden>
                    @if (!$rescue->foods->isEmpty() && $rescue->rescue_status_id === 1)
                        <Button
                            class="py-2 w-full rounded-md bg-slate-900 mt-4 text-sm font-medium text-white">Ajukan</Button>
                    @endif
                </section>
            </div>
            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold">Makanan</h2>
                    @if ($rescue->rescue_status_id === 1)
                        <a href="{{ route('rescues.foods.create', ['rescue' => $rescue]) }}"
                            class="flex items-center gap-1">
                            <x-heroicon-o-plus class="w-5 h-5" />Tambah
                        </a>
                    @endif
                </div>
                <div>
                    @if ($rescue->foods->isEmpty())
                        <p class="mt-6 font-medium text-center">Belum ada makanan yang ditambahkan</p>
                        <div class="flex justify-center mt-3">
                            <a href="{{ route('rescues.foods.create', ['rescue' => $rescue]) }}"
                                class="py-2 px-4 rounded-md bg-slate-900 text-white font-medium text-sm">Tambah
                                makanan</a>
                        </div>
                    @else
                        <div class="mt-4">
                            @foreach ($rescue->foods as $food)
                                <section class="p-6 border border-slate-200 rounded-md mb-4">

                                    <div class="flex items-center gap-4">
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
                                    </div>

                                    @if ($rescue->rescue_status_id === 1)
                                        <div>
                                            <p class="mt-6 text-sm font-medium">Kondisi saat diajukan
                                            </p>
                                            <div class="border mt-2 rounded-md">
                                                <input class="p-2" type="file" name="{{ $food->id }}-photo"
                                                    required>
                                            </div>
                                        </div>
                                    @endif

                                </section>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </main>
@endsection
