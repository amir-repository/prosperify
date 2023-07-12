@extends('layouts.manager.index')

@section('main')
    <main class="p-6">
        <section class="flex gap-2 items-center">
            <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex justify-center items-center">
                <x-heroicon-o-user class="w-6 h-6" />

            </div>
            <div>
                <h2 class="capitalize">{{ $recipient->name }}</h2>
                <p class="text-xs text-slate-500">{{ $recipient->family_members }} Anggota keluarga</p>
            </div>
        </section>
        <section class="mt-4 text-slate-900 flex flex-col gap-2">
            <div class="flex items-center gap-1">
                <x-heroicon-o-phone class="w-[18px] h-[18px]" />
                <p class="text-sm">{{ $recipient->phone }}</p>
            </div>
            <div class="flex items-center gap-1">
                <x-heroicon-o-map-pin class="w-[18px] h-[18px]" />
                <p class="text-sm">{{ $recipient->address }}</p>
            </div>
        </section>
        <section class="mt-8">
            <h2 class="text-lg font-bold">Riwayat penerimaan</h2>
            <div class="mt-4">
                {{-- donation here --}}
            </div>
        </section>
    </main>
@endsection
