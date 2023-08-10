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
            <p class="text-sm capitalize"> {{ $rescue->rescueStatus->name }} @if ($rescue->rescue_status_id > 4 && $rescue->rescue_status_id !== 7 && $rescue->rescue_status_id !== 8)
                    <span>({{ $rescue->food_rescue_result }}/{{ $rescue->food_rescue_plan }})</span>
                @endif
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
                                    $foodRescueStatus = $food->pivot->foodRescueStatus;
                                    $foodRescueNotCanceled = $foodRescueStatus->name !== 'canceled';
                                    $isAdminOrAssignedVolunteer =
                                        $food->pivot->volunteer_id === auth()->user()->id ||
                                        auth()
                                            ->user()
                                            ->hasRole('admin');
                                @endphp
                                @if ($isAdminOrAssignedVolunteer && $foodRescueNotCanceled)
                                    <a href="{{ route('rescues.foods.show', ['rescue' => $rescue, 'food' => $food]) }}">
                                        <section class="p-6 border border-slate-200 rounded-md mb-4 ">
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
                                                        {{ $food->expired_date }}</p>
                                                </div>
                                            </div>
                                            <section class="flex items-center gap-2 mt-4">
                                                <div
                                                    class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                                                    @include('foods.partials.food-status')
                                                </div>
                                                <div>
                                                    <p>
                                                        <span
                                                            class="capitalize">{{ $food->pivot->foodRescueStatus->name }}</span>
                                                        by
                                                        {{ $food->pivot->user->name }}
                                                    </p>
                                                    <p class="text-xs text-slate-500 capitalize">
                                                        At
                                                        {{ $food->pivot->updated_at }}
                                                    </p>
                                                </div>
                                            </section>
                                            @php
                                                $rescueNotRejected = $rescue->rescue_status_id !== 7;
                                            @endphp
                                            @if ($rescueNotRejected)
                                                @if ($rescue->rescue_status_id === 3)
                                                    <section class="flex justify-between">
                                                        <div>
                                                            <p class="mt-4 text-sm font-medium">Volunteer
                                                            </p>
                                                            <div class="mt-2">
                                                                <select class="rounded-md border border-slate-300"
                                                                    name="food-{{ $food->id }}-volunteer_id"
                                                                    id="volunteer" required>
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
                                                @elseif($rescue->rescue_status_id > 3)
                                                    <section class="flex justify-between">
                                                        <div>
                                                            <p class="mt-4 text-sm font-medium">Volunteer
                                                            </p>
                                                            <div class="mt-2">
                                                                <select class="rounded-md border border-slate-300"
                                                                    name="food-{{ $food->id }}-volunteer_id"
                                                                    id="volunteer" disabled>
                                                                    <option>
                                                                        {{ $food->pivot->volunteer->name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="mt-4 text-sm font-medium">Vaults
                                                            </p>
                                                            <div class="mt-2">
                                                                <select class="rounded-md border border-slate-300"
                                                                    name="food-{{ $food->id }}-vault_id" id="vault"
                                                                    disabled>
                                                                    <option value="">
                                                                        {{ $food->pivot->vault->name }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </section>
                                                @endif
                                            @endif

                                            @if ($rescue->rescue_status_id > 3 && $rescue->rescue_status_id < 6)
                                                @role('volunteer')
                                                    @php
                                                        $foodRescueStatus = $food->pivot->foodRescueStatus;
                                                    @endphp
                                                    @if ($foodRescueStatus->id < 6)
                                                        <p class="mt-4 text-sm font-medium">Photo when it's
                                                            @if ($foodRescueStatus->id === 4)
                                                                taken
                                                            @elseif($foodRescueStatus->id === 5)
                                                                stored
                                                            @endif
                                                        </p>
                                                        <div class="border mt-2 rounded-md">
                                                            <input class="p-2" type="file"
                                                                name="{{ $food->id }}-photo"
                                                                {{ in_array($rescue->rescue_status_id, [4, 5]) ? '' : 'required' }}>
                                                        </div>
                                                        <button
                                                            class="py-2 w-full rounded-md bg-slate-900 mt-4 text-sm font-medium text-white">
                                                            @if ($foodRescueStatus->id === 4)
                                                                Take
                                                            @elseif($foodRescueStatus->id === 5)
                                                                Store
                                                            @endif
                                                        </button>
                                                    @endif
                                                @endrole
                                            @endif
                                        </section>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </main>
@endsection
