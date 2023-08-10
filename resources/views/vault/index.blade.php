@extends('layouts.manager.index')

@section('main')
    <main class="px-6">
        <div class="flex justify-between items-end">
            <h1 class="text-2xl font-bold">Vaults</h1>
            <a class="flex gap-1" href="{{ route('vaults.create') }}">Add <x-heroicon-o-plus class="w-5 h-5" /></a>
        </div>
        <div class="mt-4">
            @foreach ($vaults as $vault)
                <section class="border mb-4 p-2 rounded-md border-slate-300 flex justify-between">
                    <h2 class="capitalize">{{ $vault->city->name }} - {{ $vault->name }}</h2>
                    <div class="flex gap-4 items-center">
                        <a href="{{ route('vaults.edit', ['vault' => $vault]) }}"><x-heroicon-o-pencil-square
                                class="w-5 h-5" /></a>
                        <form onsubmit="return confirm('Are you sure');" class="h-5"
                            action="{{ route('vaults.destroy', ['vault' => $vault]) }}" method="post">
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
