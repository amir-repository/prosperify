@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Edit food amount</h1>
        <form onsubmit="return confirm('Are you sure');"
            action="{{ route('donations.foods.update', compact('donation', 'food')) }}" method="post">
            @csrf
            @method('put')
            <section class="mt-4">
                <div class="flex gap-4">
                    <div class="mb-4 flex-1">
                        <label for="amount" class="text-sm font-medium block mb-[6px]">Foods</label>
                        <select class="border border-slate-200 rounded-md w-full" name="food_id" id="food_id">
                            <option value="{{ $food->id }}">{{ $food->name }} -
                                {{ $food->amount }}.{{ $food->unit->name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-4 flex-col">
                    <input id="donation_food_original_amount" type="number"
                        class="border border-slate-200 rounded-md w-full" value="{{ $donationFood->amount }}"
                        name="donation_food_original_amount" hidden required>
                    <div class="flex-1">
                        <label for="amount" class="text-sm font-medium block mb-[6px]">Amount</label>
                        <input id="amount" type="number" class="border border-slate-200 rounded-md w-full"
                            value="{{ old('amount') ? old('amount') : $donationFood->amount }}" placeholder="5"
                            name="amount" required>
                        @error('amount')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1">
                        <label for="note" class="text-sm font-medium block mb-[6px]">Note</label>
                        <input id="note" type="text" class="border border-slate-200 rounded-md w-full"
                            placeholder="Note" name="note" value="{{ old('note') ? old('note') : '' }}" required>
                        @error('note')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <Button class=" py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium mt-4">Update</Button>
            </section>
        </form>
        <div class="p-4 border border-red-600 rounded-md mt-8">
            <form onsubmit="return confirm('Are you sure');"
                action="{{ route('donations.foods.destroy', compact('donation', 'food')) }}" method="post">
                @csrf
                @method('delete')
                <h1 class="font-bold text-red-600">Danger !!!</h1>
                @if (in_array($donationFood->food_donation_status_id, [5, 6]))
                    <label for="note" class="mt-4 text-sm font-medium block mb-[6px]">Note</label>
                    <input id="note" type="text" name="note" class="border border-slate-200 rounded-md w-full"
                        placeholder="Note" required>
                @endif
                <Button
                    class="mt-6 py-2 w-full border border-red-600 text-slate-900 rounded-md text-sm font-medium">Delete</Button>
            </form>
        </div>
    </main>
@endsection
