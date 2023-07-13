@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Ubah jumlah</h1>
        <form action="{{ route('donations.foods.update', ['donation' => $donation, 'food' => $food]) }}" method="post"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            <section class="mt-4">
                <div class="mb-4">
                    <label for="amount" class="text-sm font-medium block mb-[6px]">Jumlah</label>
                    <input id="amount" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ $donationFood->amount_plan }}" placeholder="2" name="amount" required>
                </div>
                <div>
                    <label for="photo" class="text-sm font-medium block mb-[6px]">Dokumentasi gambar</label>
                    <input id="photo" type="file" class="py-2 px-2 border border-slate-200 rounded-md w-full"
                        name="photo" required>
                </div>
                <Button class="mt-8 py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Ubah</Button>
            </section>
        </form>
    </main>
@endsection
