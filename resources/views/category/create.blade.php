@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Add a new category</h1>
        <form onsubmit="return confirm('Are you sure');" action="{{ route('categories.store') }}" method="POST">
            @csrf
            <section class="mt-4">
                <div>
                    <label for="name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <input id="name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('name') }}" placeholder="Snack" name="name" required>
                    @error('name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <Button class="mt-6 py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Add</Button>
            </section>
        </form>
    </main>
@endsection
