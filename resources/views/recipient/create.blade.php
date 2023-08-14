@extends('layouts.index')

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Propose a new recipient</h1>
        <form onsubmit="return confirm('Are you sure');" action="{{ route('recipients.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <section class="mt-4">
                <div class="mb-4">
                    <label for="name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <input id="name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('name') }}" placeholder="Name" name="name" required>
                    @error('name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="nik" class="text-sm font-medium block mb-[6px]">NIK</label>
                    <input id="nik" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('name') }}" placeholder="NIK" name="nik" required>
                    @error('nik')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="address" class="text-sm font-medium block mb-[6px]">Address</label>
                    <input id="address" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('address') }}" placeholder="Address" name="address" required>
                    @error('address')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="phone" class="text-sm font-medium block mb-[6px]">Phone</label>
                    <input id="phone" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('phone') }}" placeholder="Phone" name="phone" required>
                    @error('phone')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="family_members" class="text-sm font-medium block mb-[6px]">Family Members</label>
                    <input id="family_members" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('family_members') }}" placeholder="4" name="family_members" required>
                    @error('family_members')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="photo" class="text-sm font-medium block mb-[6px]">Photo</label>
                    <input id="photo" type="file" class="border border-slate-200 rounded-md w-full p-2"
                        name="photo" required>
                </div>
                <Button class="mt-6 py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Add</Button>
            </section>
        </form>
    </main>
@endsection
