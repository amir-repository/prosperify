@extends('layouts.manager.index')

@section('main')
    <main class="p-6">
        <h1 class="text-xl font-bold">Food Assignment</h1>
        <section class="border rounded-md p-6 mt-4">
            <form action="{{ route('rescues.foods.assignment', ['rescue' => $rescue, 'food' => $food]) }}" method="post">
                @csrf
                <section class="flex justify-between">
                    <div>
                        <p class="text-sm font-medium">Volunteer
                        </p>
                        <div class="mt-2">
                            <select class="rounded-md border border-slate-300" name="food-{{ $food->id }}-volunteer_id"
                                id="volunteer">
                                @foreach ($volunteers as $volunteer)
                                    <option value="{{ $volunteer->id }}">
                                        {{ $volunteer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <p class=" text-sm font-medium">Vaults
                        </p>
                        <div class="mt-2">
                            <select class="rounded-md border border-slate-300" name="food-{{ $food->id }}-vault_id"
                                id="vault">
                                @foreach ($vaults as $vault)
                                    <option value="{{ $vault->id }}">
                                        {{ $vault->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>
                <button class="py-2 w-full rounded-md bg-slate-900 mt-4 text-sm font-medium text-white">Update</button>
            </form>
        </section>
    </main>
@endsection
