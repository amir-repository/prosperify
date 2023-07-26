@extends('layouts.index')

@section('main')
    <main class="flex flex-col p-6 gap-4">
        <div x-data="{ filter: false, search: true }">
            <section class="flex items-center gap-6">
                <div>
                    <button @click="filter=!filter; search=false" class="flex items-center gap-2">
                        <x-heroicon-o-adjustments-horizontal class="w-6 h-6" /> Filter
                    </button>
                </div>
                <div @click="search=!search; filter=false" class="cursor-pointer">
                    <p class="flex gap-2">
                        <x-heroicon-o-magnifying-glass class="w-6 h-6" /> Search
                    </p>
                </div>
            </section>
            <section>
                <div x-show="filter">
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
                        <button class="px-4 py-1 bg-slate-900 text-white rounded-md text-sm" type="submit">Filter</button>
                    </form>
                </div>
                <div x-show="search">
                    <form action="" method="get" class="flex items-center gap-2 mt-4">
                        <input type="text" name="status" value="{{ request()->query('status') }}" hidden>
                        <input type="text" placeholder="Search" name="q"
                            class="rounded-full text-sm h-8 px-4 w-full">
                        <div hidden>
                            <input type="checkbox" name="urgent" id="urgent" @checked(request()->query('urgent')) hidden>
                            <input type="checkbox" name="high-amount" id="high-amount" @checked(request()->query('high-amount'))>
                        </div>
                        <button>
                            <x-heroicon-o-magnifying-glass class="w-6 h-6" />
                        </button>
                    </form>
                </div>
            </section>
        </div>
        @if ($rescues->isEmpty())
            <p class="font-medium text-center mt-16">There's no food rescue campaign yet
            </p>
            <div class="flex justify-center">
                <a href="{{ route('rescues.create') }}" class="py-2 px-4 bg-slate-900 text-white rounded-md">Create new</a>
            </div>
        @endif
        @foreach ($rescues as $rescue)
            <a href="{{ route('rescues.show', ['rescue' => $rescue]) }}">
                <section class="border border-slate-200 p-6 rounded-md text-slate-900">
                    <h2 class="font-bold text-2xl">{{ $rescue->title }}</h2>
                    <div class="mt-1 flex items-center gap-1 text-slate-500">
                        <x-heroicon-o-calendar class="w-[14px] h-[14px]" />
                        <p class="text-xs">Created at
                            {{ $rescue->created_at }}</p>
                        </p>
                    </div>
                    <div class="flex items-center gap-3 mt-3">
                        <div class="w-11 h-11 bg-slate-100 rounded-md flex items-center justify-center">
                            <x-heroicon-o-calendar class="w-6 h-6" />
                        </div>
                        <div>
                            <p><span class="capitalize">Rescue Date
                            </p>
                            <p class="text-xs text-slate-500">

                                {{ $rescue->rescue_date }}</p>
                        </div>
                    </div>
                </section>
            </a>
        @endforeach
    </main>
@endsection
