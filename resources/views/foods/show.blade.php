@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)

@php
    $foodNotRejected = $food->food_rescue_status_id !== 13;
    $foodNotCanceled = $food->food_rescue_status_id !== 14;
    $foodHasNotBeenTaken = !in_array($food->food_rescue_status_id, [9, 11]);
    $foodIsNotStored = !in_array($food->food_rescue_status_id, [10, 12]);
@endphp

@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <div>
                <img class="h-36 w-full bg-slate-200 rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                    alt="">
            </div>
            <div class="mt-3 flex items-center gap-2">
                <h1 class="text-2xl font-bold ">{{ $food->name }}</h1>
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('rescues.foods.edit', ['rescue' => $rescue, 'food' => $food]) }}">
                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </a>
                @elseif($foodNotRejected && $foodNotCanceled && $foodHasNotBeenTaken && $foodIsNotStored)
                    <a href="{{ route('rescues.foods.edit', ['rescue' => $rescue, 'food' => $food]) }}">
                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </a>
                @endif
            </div>
            <p>{{ $food->detail }}</p>
            <div class="flex items-center gap-4 mt-3">
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-archive-box class="w-[18px] h-[18px]" />{{ $food->amount }}.{{ $food->unit->name }}
                </p>
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />Exp.
                    {{ $food->expired_date }}
                </p>
            </div>
            @php
                $foodHasBeenAssigned = in_array($food->food_rescue_status_id, [7, 8, 9]);
            @endphp
            @role('admin')
                @if ($foodHasBeenAssigned)
                    <a href="{{ route('rescues.foods.assignment', ['rescue' => $rescue, 'food' => $food]) }}"
                        class="block py-2 bg-slate-900 text-white w-full rounded-md text-sm font-medium mt-4 text-center">Edit
                        Assignment</a>
                @endif
            @endrole
        </div>
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">History</h2>
            @foreach ($foodRescueLogs as $food)
                <section class="p-6 border border-slate-200 rounded-md mb-4 flex items-center gap-4">
                    <div>
                        <img class="w-[72px] h-[72px] rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                            alt="">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">
                            {{ $food->amount }}.<span class="text-base">{{ $food->unit_name }}</span>
                        </h3>
                        <p class="text-slate-500">{{ $food->actor_name }}</p>
                        <p class="text-xs text-slate-500">
                            <span class="capitalize">
                                {{ $food->food_rescue_status_name }}
                            </span>
                            at
                            {{ $food->created_at }}
                        </p>
                    </div>
                </section>
            @endforeach
        </div>
    </main>
@endsection
