@extends('layouts.manager.index')

@section('main')
    <main class="flex flex-col p-6 gap-4">
        <div class="mt-4">
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
        <div>
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
        </div>
    </main>
@endsection
