@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Add a new food</h1>
        <form onsubmit="return confirm('Are you sure');" action="{{ route('donations.foods.store', compact('donation')) }}"
            method="post">
            @csrf
            <section class="mt-4">
                <div class="flex gap-4">
                    <div class="mb-4 flex-1">
                        <label for="amount" class="text-sm font-medium block mb-[6px]">Foods</label>
                        <select class="border border-slate-200 rounded-md w-full" name="food_id" id="food_id">
                            @foreach ($foods as $food)
                                <option value="{{ $food->id }}">{{ $food->name }} -
                                    {{ $food->amount }}.{{ $food->unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="mb-4 flex-1">
                        <label for="amount" class="text-sm font-medium block mb-[6px]">Amount</label>
                        <input id="amount" type="number" class="border border-slate-200 rounded-md w-full"
                            value="{{ old('amount') }}" placeholder="5" name="amount" required>
                        @error('amount')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <Button class=" py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Add</Button>
            </section>
        </form>
    </main>
@endsection
