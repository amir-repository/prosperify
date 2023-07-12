@extends('layouts.manager.index')
@section('main')
    <main class="flex flex-col p-6 gap-4">
        @if ($recipients->isEmpty())
            <p class="font-medium text-center mt-16"> Belum ada
            </p>
        @endif
        @foreach ($recipients as $recipient)
            <a href="{{ route('recipients.show', ['recipient' => $recipient]) }}">
                <section class="p-6 border border-slate-200 rounded-md flex gap-4">
                    <div class="w-[72px] h-[72px] bg-[#F4F6FA] rounded-md flex items-center justify-center">
                        <x-heroicon-o-user class="w-9 h-9" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $recipient->family_members }}.<span class="text-base">Anggota
                                keluarga</span>
                        </h2>
                        <p class="capitalize text-slate-500">{{ $recipient->name }}</p>
                        <p class="text-slate-500 text-xs">Sejak
                            {{ Carbon\Carbon::parse($recipient->created_at)->format('d M Y') }}
                        </p>
                    </div>
                </section>
            </a>
        @endforeach
    </main>
@endsection
