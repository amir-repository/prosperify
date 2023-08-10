@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <div class="flex justify-between items-end">
            <h1 class="text-2xl font-bold">Sub Category</h1>
            <a class="flex gap-1" href="{{ route('subcategory.create') }}">Add <x-heroicon-o-plus class="w-5 h-5" /></a>
        </div>
        <div class="mt-4">
            @foreach ($subCategory as $sub)
                <section class="border mb-4 p-2 rounded-md border-slate-300 flex justify-between">
                    <h2 class="capitalize">{{ $sub->category->name }} - {{ $sub->name }}</h2>
                    <div class="flex gap-4 items-center">
                        <a href="{{ route('subcategory.edit', ['subcategory' => $sub]) }}"><x-heroicon-o-pencil-square
                                class="w-5 h-5" /></a>
                        <form onsubmit="return confirm('Are you sure');" class="h-5"
                            action="{{ route('subcategory.destroy', ['subcategory' => $sub]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button><x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                </section>
            @endforeach
        </div>
    </main>
@endsection
