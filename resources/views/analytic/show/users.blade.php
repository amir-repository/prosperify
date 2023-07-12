@extends('layouts.manager.index')

@section('main')
    <main class="flex flex-col p-6 gap-4">
        @if ($users->isEmpty())
            <p class="font-medium text-center mt-16"> Belum ada
            </p>
        @endif
        @foreach ($users as $user)
            <a href="{{ route('users.show', ['user' => $user]) }}">
                <section class="p-6 border border-slate-200 rounded-md flex gap-4">
                    <div class="w-[72px] h-[72px] bg-[#F4F6FA] rounded-md flex items-center justify-center">
                        <x-heroicon-o-user class="w-9 h-9" />
                    </div>
                    <div>
                        @if (!$user->point === null)
                            <h2 class="text-2xl font-bold">{{ $user->point->point }}.<span class="text-base">point</span></h2>
                        @endif
                        <p class="capitalize text-slate-500">{{ $user->name }}</p>
                        <p class="text-slate-500 text-xs">Sejak
                            {{ Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                        </p>
                    </div>
                </section>
            </a>
        @endforeach
    </main>
@endsection
