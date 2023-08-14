@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <form action="{{ route('donations.store') }}" method="post">
            @csrf
            <h1 class="text-lg font-bold">Food Distribution</h1>
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
                    <label for="rescue_date" class="text-sm font-medium block mb-[6px]">Distribution date</label>
                    <input id="donation_date" type="datetime-local" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('donation_date') }}" placeholder="Donasi berkah adalah ..." name="donation_date"
                        value="" required>
                    @error('donation_date')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <h2 class="text-lg font-bold mt-8">Recipient</h2>
            <div class="my-4">
                <label for="donor_name" class="text-sm font-medium block mb-[6px]">Name</label>
                <select name="recipient_id" id="recipient" class="border border-slate-200 rounded-md w-full" required>
                    @foreach ($recipients as $recipient)
                        <option @selected(old('recipient') == $recipient->id) value="{{ $recipient->id }}">
                            {{ $recipient->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="py-2 bg-slate-900 text-white w-full rounded-md mt-8 text-sm font-medium">Create</button>
        </form>
    </main>
@endsection
