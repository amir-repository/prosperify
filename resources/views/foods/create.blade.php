@extends('layouts.index')

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Tambah makanan</h1>
        <form action="{{ route('rescues.foods.store', ['rescue' => $rescue]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <section class="mt-4">
                <div class="mb-4">
                    <label for="name" class="text-sm font-medium block mb-[6px]">Nama</label>
                    <input id="name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('name') }}" placeholder="Gula manis" name="name" required>
                    @error('name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="detail" class="text-sm font-medium block mb-[6px]">Detail</label>
                    <input id="detail" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('detail') }}" placeholder="Gula putih manis" name="detail" required>
                    @error('detail')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-4">
                    <div class="mb-4 flex-1">
                        <label for="amount" class="text-sm font-medium block mb-[6px]">Kuantitas</label>
                        <input id="amount" type="number" class="border border-slate-200 rounded-md w-full"
                            value="{{ old('amount') }}" placeholder="5" name="amount" required>
                        @error('amount')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="unit" class="text-sm font-medium block mb-[6px]">Satuan</label>
                        <select name="unit" id="unit" class="border border-slate-200 rounded-md w-full" required>
                            @foreach ($units as $unit)
                                <option @selected(old('unit') == $unit->id) value="{{ $unit->id }}">
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="expired_date" class="text-sm font-medium block mb-[6px]">Tanggal kadaluarsa</label>
                    <input id="expired_date" type="date" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('expired_date') }}" name="expired_date" required>
                    @error('expired_date')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <h1></h1>
                    <label for="unit" class="text-sm font-medium block mb-[6px]">Kelompok</label>
                    <select name="sub_category" id="sub_category" class="border border-slate-200 rounded-md w-full"
                        required>
                        @foreach ($foodSubCategories as $foodSubCategory)
                            <option @selected(old('sub_category') == $foodSubCategory->id) value="{{ $foodSubCategory->id }}">
                                {{ $foodSubCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="photo" class="text-sm font-medium block mb-[6px]">Dokumentasi gambar</label>
                    <input id="photo" type="file" class="py-2 px-2 border border-slate-200 rounded-md w-full"
                        name="photo" required>
                </div>
                <Button class="mt-8 py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Tambahkan</Button>
            </section>
        </form>
    </main>
@endsection
