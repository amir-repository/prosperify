@extends('layouts.manager.index')

@section('main')
    <main class="p-6 text-slate-900">
        <div class="flex justify-between">
            <div class="flex items-center justify-between">
                @php
                    $rescueNotRejected = $rescue->rescue_status_id !== 7;
                    $rescueNotCompleted = $rescue->rescue_status_id !== 6;
                @endphp
                <a class="flex items-center gap-2"
                    @if ($rescueNotRejected) href="{{ route('rescues.edit', ['rescue' => $rescue]) }}" @endif>
                    <h1 class="text-2xl font-bold">{{ $rescue->title }}</h1>
                    @if ($rescueNotRejected)
                        <x-heroicon-o-pencil-square class="w-[18px] h-[18px]" />
                    @endif
                </a>
            </div>
            @php
                $rescueIsSubmitted = $rescue->rescue_status_id === 2;
            @endphp
            @if ($rescueIsSubmitted)
                <div>
                    <form onsubmit="return confirm('Are you sure');"
                        action="{{ route('rescues.update.status', ['rescue' => $rescue]) }}" method="post">
                        @csrf
                        @method('put')
                        <input type="text" name="status" value="7" hidden>

                        <Button
                            class="block py-2 px-3 w-full rounded-md bg-white mt-4 text-sm font-medium text-slate-900 border border-slate-300">Reject</Button>
                    </form>
                </div>
            @endif
        </div>
        <div class="mt-3 flex items-center gap-1 text-slate-500">
            <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
            <p class="text-sm">Created at {{ $rescue->created_at }}
        </div>
        <div class="mt-3 flex items-center gap-1 text-slate-500">
            @include('rescues.partials.status-icon')
            <p class="text-sm capitalize"> {{ $rescue->rescueStatus->name }}
            </p>
        </div>
        <form onsubmit="return confirm('Are you sure');"
            action="{{ route('rescues.update.status', ['rescue' => $rescue]) }}" method="post"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="mt-6">
                <section class="flex items-center gap-2">
                    <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                        <x-heroicon-o-user class="w-6 h-6" />
                    </div>
                    <div>
                        <p>{{ $rescue->donor_name }}</p>
                        <p class="text-xs text-slate-500 capitalize">Donor - {{ $rescue->phone }}</p>
                    </div>
                </section>
                <section class="mt-6">
                    <h2 class="text-lg font-bold">Rescue Date</h2>
                    <div class="mt-2 flex items-center gap-1">
                        <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
                        <p class="text-sm">{{ $rescue->rescue_date }}
                    </div>
                    <div class="mt-2 flex items-center gap-1">
                        <x-heroicon-o-map-pin class="w-[18px] h-[18px]" />
                        <p class="text-sm">{{ $rescue->pickup_address }}
                    </div>
                </section>
                <section>
                    <input type="text"
                        value="
                        @if ($rescue->rescue_status_id == 2) 3
                    @elseif ($rescue->rescue_status_id == 3) 4
                    @elseif ($rescue->rescue_status_id == 4) 5
                    @elseif ($rescue->rescue_status_id == 5) 5 @endif"
                        name="status" hidden>
                    <Button class="py-2 w-full rounded-md bg-slate-900 mt-4 text-sm font-medium text-white"
                        {{ $rescue->rescue_status_id > 3 ? 'hidden' : '' }}>
                        @if ($rescue->rescue_status_id == 2)
                            Process
                        @elseif ($rescue->rescue_status_id == 3)
                            Assign
                        @endif
                    </Button>
                </section>
            </div>
            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold">Foods</h2>
                    @if (!$rescue->rescue_status_id == 2)
                        <a href="{{ route('rescues.foods.create', ['rescue' => $rescue]) }}"
                            class="flex items-center gap-1">
                            <x-heroicon-o-plus class="w-5 h-5" />Add
                        </a>
                    @endif
                </div>
                <div>
                    @if ($rescue->foods->isEmpty())
                        <p class="mt-6 font-medium text-center">There's no food here yet</p>
                        <div class="flex justify-center mt-3">
                            <a href="{{ route('rescues.foods.create', ['rescue' => $rescue]) }}"
                                class="py-2 px-4 rounded-md bg-slate-900 text-white font-medium text-sm">Add a new
                                food</a>
                        </div>
                    @else
                        <div class="mt-4">
                            @foreach ($rescue->foods as $food)
                                @php
                                    $foodLog = $food->foodRescueLogs->last();
                                    $foodNotRejectedNorCanceled = !in_array($food->food_rescue_status_id, [13, 14]);
                                @endphp
                                @if ($foodNotRejectedNorCanceled)
                                    @php
                                        $showFoodForSpecificVolunteer = $food->foodAssignments->count() > 0 && $food->foodAssignments->last()->volunteer_id === auth()->user()->id;
                                        $isAdmin = auth()
                                            ->user()
                                            ->hasRole('admin');
                                    @endphp

                                    @if ($showFoodForSpecificVolunteer || $isAdmin)
                                        <a
                                            href="{{ route('rescues.foods.show', ['rescue' => $rescue, 'food' => $food]) }}">
                                            <section class="p-6 border border-slate-200 rounded-md mb-4 ">
                                                {{-- food info --}}
                                                <div class="flex items-center gap-4">
                                                    <div>
                                                        <img class="w-[72px] h-[72px] rounded-md object-cover"
                                                            src="{{ asset("storage/$food->photo") }}" alt="">
                                                    </div>
                                                    <div>
                                                        <h3 class="text-2xl font-bold">
                                                            {{ $food->amount }}.<span
                                                                class="text-base">{{ $food->unit->name }}</span>
                                                        </h3>
                                                        <p class="text-slate-500">{{ $food->name }}</p>
                                                        <p class="text-xs text-slate-500">Exp.
                                                        </p>
                                                    </div>
                                                </div>
                                                {{-- food info --}}
                                                {{-- food status --}}
                                                <section class="flex items-center gap-2 mt-4">
                                                    <div
                                                        class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                                                        @include('foods.partials.food-status')
                                                    </div>
                                                    <div>
                                                        <p>
                                                            <span
                                                                class="capitalize">{{ $foodLog->food_rescue_status_name }}</span>
                                                            by
                                                            {{ $foodLog->actor_name }}

                                                        </p>
                                                        <p class="text-xs text-slate-500 capitalize">
                                                            At {{ $foodLog->created_at }}
                                                        </p>
                                                    </div>
                                                </section>
                                                {{-- food status --}}
                                        </a>
                                        @php
                                            $rescueNotRejected = $rescue->rescue_status_id !== 7;
                                        @endphp
                                        @if ($rescueNotRejected)
                                            {{-- rescue state on processed --}}
                                            @php
                                                $foodIsNotRejectedAndNotCanceled = !in_array($food->food_rescue_status_id, [13, 14]);
                                                $foodProcessed = $rescue->rescue_status_id === 3;
                                            @endphp
                                            @if ($foodProcessed && $foodIsNotRejectedAndNotCanceled)
                                                <section class="flex justify-between">
                                                    <div>
                                                        <p class="mt-4 text-sm font-medium">Volunteer
                                                        </p>
                                                        <div class="mt-2">
                                                            <select class="rounded-md border border-slate-300"
                                                                name="food-{{ $food->id }}-volunteer_id" id="volunteer"
                                                                required>
                                                                @foreach ($volunteers as $volunteer)
                                                                    <option value="{{ $volunteer->id }}">
                                                                        {{ $volunteer->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="mt-4 text-sm font-medium">Vaults
                                                        </p>
                                                        <div class="mt-2">
                                                            <select class="rounded-md border border-slate-300"
                                                                name="food-{{ $food->id }}-vault_id" id="vault"
                                                                required>
                                                                @foreach ($vaults as $vault)
                                                                    <option value="{{ $vault->id }}">
                                                                        {{ $vault->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </section>
                                                {{-- rescue state on processed --}}

                                                {{-- rescue state on assigned --}}
                                            @elseif($rescue->rescue_status_id > 3)
                                                @php
                                                    $foodNotRejectedNorCanceled = !in_array($food->food_rescue_status_id, [13, 14]);
                                                @endphp
                                                {{-- food is not rejected nor canceled --}}
                                                @if ($foodNotRejectedNorCanceled)
                                                    <section class="flex justify-between">
                                                        <div>
                                                            <p class="mt-4 text-sm font-medium">Volunteer
                                                            </p>
                                                            <div class="mt-2">
                                                                <select class="rounded-md border border-slate-300"
                                                                    name="food-{{ $food->id }}-volunteer_id"
                                                                    id="volunteer">
                                                                    <option
                                                                        value="{{ $food->foodAssignments->last()->volunteer->id }}">
                                                                        {{ $food->foodAssignments->last()->volunteer->name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="mt-4 text-sm font-medium">Vaults
                                                            </p>
                                                            <div class="mt-2">
                                                                <select class="rounded-md border border-slate-300"
                                                                    name="food-{{ $food->id }}-vault_id"
                                                                    id="vault">
                                                                    <option
                                                                        value="{{ $food->foodAssignments->last()->vault->id }}">
                                                                        {{ $food->foodAssignments->last()->vault->name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </section>
                                                @endif
                                                {{-- food is not rejected nor canceled --}}
                                            @endif
                                            {{-- rescue state on assigned --}}
                                        @endif

                                        @if ($rescue->rescue_status_id > 3 && $rescue->rescue_status_id < 6 && !in_array($food->food_rescue_status_id, [10]))
                                            @role('volunteer')
                                                @php
                                                    $foodRescueStatus = $food->food_rescue_status_id;
                                                    $foodAssigned = in_array($foodRescueStatus, [7, 8]);
                                                    $foodTaken = in_array($foodRescueStatus, [9, 11]);
                                                    $isTimeToRescue = Carbon\Carbon::parse($rescue->rescue_date)
                                                        ->startOfDay()
                                                        ->isSameDay();
                                                @endphp
                                                @if ($isTimeToRescue)
                                                    {{-- rescue photo --}}
                                                    <p class="mt-4 text-sm font-medium">Photo when it's
                                                        @if ($foodAssigned)
                                                            taken
                                                        @elseif($foodTaken)
                                                            stored
                                                        @endif
                                                    </p>
                                                    <div class="border mt-2 rounded-md">
                                                        <input class="p-2" type="file" name="{{ $food->id }}-photo"
                                                            {{ in_array($rescue->rescue_status_id, [4, 5]) ? '' : 'required' }}>
                                                    </div>
                                                    {{-- rescue photo --}}

                                                    {{-- amount input --}}
                                                    <p class="mt-4 text-sm font-medium">Amount when it's
                                                        @if ($foodAssigned)
                                                            taken
                                                        @elseif($foodTaken)
                                                            stored
                                                        @endif
                                                    </p>

                                                    <div>
                                                        <input class="p-2 border mt-2 rounded-md w-full border-slate-400"
                                                            type="number" name="food-{{ $food->id }}-amount"
                                                            value="{{ $food->amount }}" max="{{ $food->amount }}" required>
                                                    </div>

                                                    {{-- amount input --}}

                                                    <button
                                                        class="py-2 w-full rounded-md bg-slate-900 mt-4 text-sm font-medium text-white">
                                                        @if ($foodAssigned)
                                                            Take
                                                        @elseif($foodTaken)
                                                            Store
                                                        @endif
                                                    </button>
                                                @endif
                                            @endrole
                                        @endif
                                        </section>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </main>
@endsection
