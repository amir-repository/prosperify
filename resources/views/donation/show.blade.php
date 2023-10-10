@extends('layouts.manager.index')

@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <div>
                <div class="flex items-center justify-between">
                    <a href="{{ route('donations.edit', compact('donation')) }}" class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold">{{ $donation->title }}</h1>
                        <x-heroicon-o-pencil-square class="w-[18px] h-[18px]" />
                    </a>
                </div>
                <div class="mt-3 flex items-center gap-1 text-slate-500">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
                    <p class="text-sm">Created at {{ $donation->created_at }}
                </div>
                <div class="mt-3 flex items-center gap-1 text-slate-500 capitalize">
                    @include('donation.partials.status-icon')
                    <p class="text-sm"> {{ $donation->donationStatus->name }}
                </div>
            </div>
        </div>
        <div class="mt-3">
            <section class="flex items-center gap-2">
                <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                    <x-heroicon-o-user class="w-6 h-6" />
                </div>
                <div>
                    <p>{{ $donation->recipient->name }}</p>
                    <p class="text-xs text-slate-500 capitalize">Recipient - {{ $donation->recipient->phone }}</p>
                </div>
            </section>
            <section class="mt-6">
                <h2 class="text-lg font-bold">Donation Date</h2>
                <div class="mt-2 flex items-center gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />
                    <p class="text-sm">{{ $donation->donation_date_humanize }}</p>
                </div>
                <div class="mt-2 flex items-center gap-1">
                    <x-heroicon-o-map-pin class="w-[18px] h-[18px]" />
                    <p class="text-sm">{{ $donation->recipient->address }}</p>
                </div>
            </section>
        </div>
        <form action="{{ route('donations.update.status', compact('donation')) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <section>
                <input hidden type="text"
                    value="
                @if ($donation->donation_status_id === 1) 2
                @elseif($donation->donation_status_id === 2)3
                @else 3 @endif
                "
                    name="status">
                @if (!$donationFoods->isEmpty() && $donation->donation_status_id < 2)
                    <Button class="py-2 w-full rounded-md bg-slate-900 mt-8 text-sm font-medium text-white">Assign</Button>
                @endif
            </section>
            <div class="flex items-center justify-between mt-6">
                <h2 class="text-lg font-bold">Foods</h2>

                @if ($donation->donation_status_id < 2)
                    <a href="{{ route('donations.foods.create', compact('donation')) }}" class="flex items-center gap-1">
                        <x-heroicon-o-plus class="w-5 h-5" />Add
                    </a>
                @endif
            </div>
            <section class="mt-6">
                {{-- food card --}}
                @foreach ($donationFoods as $donationFood)
                    @php
                        $food = $donationFood->food;
                        $foodDonationLog = $donationFood->foodDonationLogs->last();
                        
                        $showFoodForSpecificVolunteer = $donationFood->donationAssignments->count() > 0 && $donationFood->donationAssignments->last()->volunteer_id === auth()->user()->id;
                        $isAdmin = auth()
                            ->user()
                            ->hasRole('admin');
                    @endphp
                    @if ($showFoodForSpecificVolunteer || $isAdmin)
                        <a href="{{ route('donations.foods.show', compact('donation', 'food', 'donationFood')) }}">
                            <section class="p-6 border border-slate-200 rounded-md mb-4">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <img class="w-[72px] h-[72px] rounded-md object-cover"
                                            src="{{ asset("storage/$food->photo") }}" alt="">
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold">
                                            {{ $donationFood->amount }}.<span
                                                class="text-base">{{ $food->unit->name }}</span>
                                        </h3>
                                        <p class="text-slate-500">{{ $food->name }}</p>
                                        <p class="text-xs text-slate-500">
                                            Exp. {{ $food->expired_date }}
                                        </p>
                                    </div>
                                </div>
                                <section class="flex items-center gap-2 mt-4">
                                    <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                                        @include('donation.partials.food-status')
                                    </div>
                                    <div>
                                        <p>
                                            <span class="capitalize">
                                                {{ $foodDonationLog->food_donation_status_name }}
                                                By
                                                {{ $foodDonationLog->actor_name }}
                                            </span>
                                        </p>
                                        <p class="text-xs text-slate-500 capitalize">
                                            At
                                            2023
                                        </p>
                                    </div>
                                </section>
                                {{-- assignment --}}
                                <section class="flex justify-between">
                                    <div>
                                        <p class="mt-4 text-sm font-medium">Volunteer
                                        </p>
                                        @php
                                            $donationPlanned = $donation->donation_status_id === 1;
                                        @endphp
                                        @if ($donationPlanned)
                                            {{-- when planned --}}
                                            <div class="mt-2">
                                                <select class="rounded-md border border-slate-300"
                                                    name="food-{{ $food->id }}-volunteer_id" id="volunteer" required>
                                                    @foreach ($volunteers as $volunteer)
                                                        <option value="{{ $volunteer->id }}">
                                                            {{ $volunteer->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- when planned --}}
                                        @else
                                            {{-- when assigned --}}
                                            <div class="mt-2">
                                                <select class="rounded-md border border-slate-300"
                                                    name="food-{{ $food->id }}-volunteer_id" id="volunteer" required>
                                                    @php
                                                        $volunteer = $donationFood->donationAssignments->last()->volunteer;
                                                    @endphp
                                                    <option value="{{ $volunteer->id }}">
                                                        {{ $volunteer->name }}
                                                    </option>
                                                </select>
                                            </div>
                                            {{-- when assigned --}}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mt-4 text-sm font-medium">Vaults
                                        </p>
                                        <div class="mt-2">
                                            <select class="rounded-md border border-slate-300"
                                                name="food-{{ $food->id }}-vault_id" id="vault" required>
                                                <option value="{{ $food->vault_id }}">{{ $food->vault->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                {{-- assignment --}}

                                @php
                                    $foodAssigned = in_array($donationFood->food_donation_status_id, [3, 4]);
                                    $foodTaken = in_array($donationFood->food_donation_status_id, [5, 6]);
                                    
                                @endphp
                                @if ($foodAssigned || $foodTaken)
                                    @role('volunteer')
                                        {{-- image --}}
                                        <p class="mt-4 text-sm font-medium">Photo when it's
                                            @if ($foodAssigned)
                                                taken
                                            @elseif($foodTaken)
                                                given
                                            @endif
                                        </p>
                                        <div class="border mt-2 rounded-md">
                                            <input class="p-2" type="file" name="{{ $food->id }}-photo">
                                        </div>
                                        <input type="text" name="donation_food_id" value="{{ $donationFood->id }}" hidden>
                                        <button class="py-2 w-full rounded-md bg-slate-900 mt-4 text-sm font-medium text-white">
                                            @if ($foodAssigned)
                                                Take
                                            @elseif($foodTaken)
                                                Give
                                            @endif
                                        </button>
                                        {{-- image --}}
                                    @endrole
                                @endif


                            </section>
                        </a>
                    @endif
                @endforeach
                {{-- food card --}}
            </section>
        </form>
    </main>
@endsection
