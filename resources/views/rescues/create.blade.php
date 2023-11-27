@extends('layouts.index')

@section('main')
    <main class="px-6">

        <form onsubmit="return confirm('Are you sure');" action="{{ route('rescues.store') }}" method="post">
            @csrf
            <h1 class="text-lg font-bold mt-4">Create Food Rescue</h1>

            @if (session('conflict'))
                <div class="p-4 bg-red-200 rounded-xl mt-2">
                    {{ session('conflict') }}
                </div>
            @endif

            <section class="p-4 bg-yellow-200 rounded-xl mt-2">
                <h2 class="font-bold">Notes</h2>
                <ul>
                    <li>+ Minimum pickup date is >={{ $prep }} days from now.</li>
                </ul>
            </section>

            @if ($errors->any())
                <section>
                    <h4>{{ $errors->first() }}</h4>
                </section>
            @endif

            <section class="mt-4">
                <div class="mb-4">
                    <label for="title" class="text-sm font-medium block mb-[6px]">Title</label>
                    <input id="title" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('title') }}" placeholder="Donasi berkah" name="title" required>
                    @error('title')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="text-sm font-medium block mb-[6px]">Description</label>
                    <input id="description" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('description') }}" placeholder="Donasi berkah adalah ..." name="description" required>
                    @error('description')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="pickup_address" class="text-sm font-medium block mb-[6px]">Pick-up address</label>
                    <input id="pickup_address" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('pickup_address') }}" placeholder="Jalan ..." name="pickup_address" required>
                    @error('pickup_address')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="rescue_date" class="text-sm font-medium block mb-[6px]">Pick-up date</label>
                    <input id="rescue_date" type="datetime-local" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('rescue_date') }}" placeholder="Donasi berkah adalah ..." name="rescue_date"
                        value="" required>
                    @error('rescue_date')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <h2 class="text-lg font-bold mt-8">Donor</h2>
            <section class="mt-4">
                <div class="mb-4">
                    <label for="donor_name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <input id="donor_name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ $user->name }}" placeholder="Nama saya" name="donor_name" required>
                    @error('donor_name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-4">
                    <div class="mb-4 flex-1">
                        <label for="phone" class="text-sm font-medium block mb-[6px]">Phone</label>
                        <input id="phone" type="text" class="border border-slate-200 rounded-md w-full"
                            value="{{ $user->phone }}" placeholder="081234567890" name="phone" required>
                        @error('phone')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="email" class="text-sm font-medium block mb-[6px]">Email</label>
                        <input id="email" type="email" class="border border-slate-200 rounded-md w-full"
                            value="{{ $user->email }}" placeholder="name@mail.com" name="email" required>
                        @error('email')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>
            <button class="py-2 bg-slate-900 text-white w-full rounded-md mt-8">Create</button>
        </form>
    </main>
@endsection
