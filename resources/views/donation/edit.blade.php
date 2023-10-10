@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <form onsubmit="return confirm('Are you sure');" action="{{ route('donations.update', compact('donation')) }}"
            method="post">
            @csrf
            @method('put')
            <h1 class="text-lg font-bold">Food Donation</h1>
            <section class="mt-4">
                <div class="mb-4">
                    <label for="title" class="text-sm font-medium block mb-[6px]">Title</label>
                    <input id="title" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('title') ? old('title') : $donation->title }}" placeholder="Donasi berkah"
                        name="title" required>
                    @error('title')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="text-sm font-medium block mb-[6px]">Description</label>
                    <input id="description" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('description') ? old('description') : $donation->description }}"
                        placeholder="Donasi berkah adalah ..." name="description" required>
                    @error('description')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="rescue_date" class="text-sm font-medium block mb-[6px]">Donation date</label>
                    <input id="rescue_date" type="datetime-local" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('rescue_date') ? old('rescue_date') : $donation->donation_date }}"
                        placeholder="Donasi berkah adalah ..." name="donation_date" required>
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
                            <option @selected($recipient->id === $donation->recipient_id) value="{{ $recipient->id }}">{{ $recipient->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </section>
            <button class="py-2 bg-slate-900 text-white w-full rounded-md mt-8">Update</button>
        </form>
        <div class="p-4 border border-red-600 rounded-md mt-8">
            <form onsubmit="return confirm('Are you sure');" action="{{ route('donations.destroy', compact('donation')) }}"
                method="post">
                @csrf
                @method('delete')
                <h1 class="font-bold text-red-600">Danger !!!</h1>
                {{-- @if (true)
                    <label for="note" class="mt-4 text-sm font-medium block mb-[6px]">Note</label>
                    <input id="note" type="text" name="note" class="border border-slate-200 rounded-md w-full"
                        placeholder="Note" required>
                @endif --}}
                <Button
                    class="mt-6 py-2 w-full border border-red-600 text-slate-900 rounded-md text-sm font-medium">Delete</Button>
            </form>
        </div>
    </main>
@endsection
