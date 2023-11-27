@extends('layouts.manager.index')

@section('main')
    <main class="px-6">

        @if (session('conflict'))
            <div class="p-4 bg-red-200 rounded-xl mt-2">
                {{ session('conflict') }}
            </div>
        @endif

        <form onsubmit="return confirm('Are you sure');" action="{{ route('donations.store') }}" method="post">
            @csrf
            <h1 class="text-lg font-bold">Food Donations</h1>
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
                    <label for="rescue_date" class="text-sm font-medium block mb-[6px]">Donation date</label>
                    <input id="rescue_date" type="datetime-local" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('rescue_date') }}" placeholder="Donasi berkah adalah ..." name="donation_date"
                        required>
                    @error('rescue_date')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <h2 class="text-lg font-bold mt-8">Recipient</h2>
            <section class="mt-4">
                <div>
                    <label for="donor_name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <select class="border border-slate-200 rounded-md w-full" name="recipient_id" id="recipient_id">
                        @foreach ($recipients as $recipient)
                            <option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
                        @endforeach
                    </select>
                </div>
            </section>
            <button class="py-2 bg-slate-900 text-white w-full rounded-md mt-8">Create</button>
        </form>
    </main>
@endsection
