@extends('layouts.index')

@section('main')
    <main class="flex flex-col p-6 gap-4">
        @if (count($rescues) > 2)
            <div class="flex items-center gap-6">
                <section x-data="{ open: false }">
                    <div>
                        <button @click="open=!open" class="flex items-center gap-2">
                            <x-heroicon-o-adjustments-horizontal class="w-6 h-6" /> Filter
                        </button>
                    </div>
                    <div x-show="open">
                        <form class="flex items-center gap-11 my-4" action="{{ route('rescues.index') }}" method="get">
                            <input type="text" name="status"
                                value={{ request()->query('status') ? request()->query('status') : '1' }} hidden>
                            <label for="urgent">
                                <input type="checkbox" name="urgent" id="urgent" @checked(request()->query('urgent'))>
                                Urgent
                            </label>
                            <label for="high-amount">
                                <input type="checkbox" name="high-amount" id="high-amount" @checked(request()->query('high-amount'))>
                                High amount
                            </label>
                            <button class="px-4 py-1 bg-slate-900 text-white rounded-md text-sm"
                                type="submit">Filter</button>
                        </form>
                    </div>
                </section>
                <section x-data="{ open: false }">
                    <div @click="open=!open" class="cursor-pointer">
                        <p class="flex gap-2">
                            <x-heroicon-o-magnifying-glass class="w-6 h-6" /> Search
                        </p>
                    </div>
                    <div x-show="open">
                        <form action="" method="get" class="flex items-center gap-2">
                            <input type="text" placeholder="Search" name="q" class="rounded-full text-sm h-8 px-4">
                            <button>
                                <x-heroicon-o-magnifying-glass class="w-6 h-6" />
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        @endif
        @if ($rescues->isEmpty())
            <p class="font-medium text-center mt-16"> Belum ada penyelamatan pangan
            </p>
            <div class="flex justify-center">
                <a href="{{ route('rescues.create') }}" class="py-2 px-4 bg-slate-900 text-white rounded-md">Buat baru</a>
            </div>
        @endif
        @foreach ($rescues as $rescue)
            <a href="{{ route('rescues.show', ['rescue' => $rescue]) }}">
                <section class="border border-slate-200 p-6 rounded-md text-slate-900">
                    <h2 class="font-bold text-2xl">{{ $rescue->title }}</h2>
                    <p class="mt-1">Pada {{ Carbon\Carbon::parse($rescue->rescue_date)->format('d M Y') }} jam
                        {{ Carbon\Carbon::parse($rescue->rescue_date)->format('H:i') }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        <div class="w-11 h-11 bg-slate-100 rounded-md flex items-center justify-center">
                            @if ($rescue->rescue_status_id === 1)
                                <x-heroicon-o-bookmark class="w-6 h-6" />
                            @elseif($rescue->rescue_status_id === 2)
                                <x-heroicon-o-paper-airplane class="w-6 h-6" />
                            @elseif($rescue->rescue_status_id === 3)
                                <x-heroicon-o-cog class="w-6 h-6" />
                            @elseif($rescue->rescue_status_id === 4)
                                <x-heroicon-o-truck class="w-6 h-6" />
                            @elseif($rescue->rescue_status_id === 5)
                                <x-heroicon-o-archive-box class="w-6 h-6" />
                            @endif
                        </div>
                        <div>
                            <p><span class="capitalize">{{ $rescue->rescueStatus->name }}</span> oleh
                                {{ $rescue->rescueUser->filter(fn($r) => $r->rescue_status_id === $rescue->rescue_status_id)->first()->user->name }}
                            </p>
                            <p class="text-xs text-slate-500">21 Juli 2023</p>
                        </div>
                    </div>
                </section>
            </a>
        @endforeach
    </main>
@endsection
