@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Edit Vault</h1>
        <form onsubmit="return confirm('Are you sure');" action="{{ route('vaults.update', ['vault' => $vault]) }}"
            method="POST">
            @csrf
            @method('put')
            <section class="mt-4">
                <div class="mb-4">
                    <label for="name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <input id="name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('name') ? old('name') : $vault->name }}" placeholder="Vault" name="name" required>
                    @error('name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="name" class="text-sm font-medium block mb-[6px]">Address</label>
                    <input id="address" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('address') ? old('address') : $vault->address }}" placeholder="Jl. Merdeka"
                        name="address" required>
                    @error('address')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="city" class="text-sm font-medium block mb-[6px]">City</label>
                    <select name="city_id" id="city" class="border border-slate-200 rounded-md w-full">
                        @foreach ($cities as $city)
                            <option @selected($city->id === $vault->city_id) value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <Button class="mt-6 py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Update</Button>
            </section>
        </form>
    </main>
@endsection
