@extends('layouts.manager.index')

@section('main')
    <main class="p-6">
        <h1 class="text-2xl font-bold mb-4">Analitik Pangan</h1>
        <a href="#">
            <div class="border border-slate-200 rounded-md p-6 mb-4">
                <h2>Pangan yang berhasil diselamatkan</h2>
                <div class="flex justify-between mt-1">
                    <p class="font-bold text-[32px]">{{ $rescuedFoodAmount['kg'] }}.<span class="text-2xl">Kg</span></p>
                    <p class="font-bold text-[32px]">{{ $rescuedFoodAmount['porsi'] }}.<span class="text-2xl">Porsi</span></p>
                </div>
            </div>
        </a>
        <a href="#">
            <div class="border border-slate-200 rounded-md p-6 mb-4">
                <h2>Kadaluarsa minggu ini</h2>
                <p class="font-bold text-[32px] mt-1">{{ $expiredThisWeek }}.<span class="text-2xl">Kg</span></p>
            </div>
        </a>
        <a href="#">
            <div class="border border-slate-200 rounded-md p-6 mb-4">
                <h2>Inventori</h2>
                <div class="flex justify-between mt-1">
                    <p class="font-bold text-[32px]">{{ $rescuedFoodInStock['kg'] }}.<span class="text-2xl">Kg</span></p>
                    <p class="font-bold text-[32px]">{{ $rescuedFoodInStock['porsi'] }}.<span class="text-2xl">Porsi</span>
                    </p>
                </div>
            </div>
        </a>
        <div class="flex gap-4">
            <div class="border border-slate-200 rounded-md p-6 mb-4 flex-1">
                <h2>Donatur</h2>
                <p class="font-bold text-[32px] mt-1">{{ $donors }}
                </p>
            </div>
            <div class="border border-slate-200 rounded-md p-6 mb-4 flex-1">
                <h2>Relawan</h2>
                <p class="font-bold text-[32px] mt-1">{{ $volunteers }}
                </p>
            </div>
        </div>
        <a href="#">
            <div class="border border-slate-200 rounded-md p-6 mb-4">
                <h2>Penerima manfaat</h2>
                <div class="flex justify-between mt-1">
                    <p class="font-bold text-[32px]">{{ $recipients['familyAmount'] }}.<span
                            class="text-2xl">Keluarga</span></p>
                    <p class="font-bold text-[32px]">{{ $recipients['familyMemberAmount'] }}.<span
                            class="text-2xl">Orang</span>
                    </p>
                </div>
            </div>
        </a>
    </main>
@endsection
