@extends('layouts.manager.index')

@section('main')
    <main class="p-4">
        <h1 class="text-2xl font-bold mb-8">Timeline</h1>
        <ul class="flex flex-col-reverse gap-8 [&>*:last-child]:opacity-100">
            @foreach ($foodDonationLogs as $foodDonationLog)
                <li class="flex items-center gap-4 opacity-50">
                    <div class="w-2 h-2 bg-slate-900 rounded-full"></div>
                    <div>
                        <p class="text-sm text-slate-900">{{ $foodDonationLog->created_at }}</p>
                        <h3 class="text-xl font-bold capitalize">{{ $foodDonationLog->food_donation_status_name }}</h3>
                        <div>
                            <p>{{ $foodDonationLog->amount }} {{ $foodDonationLog->unit_name }} -
                                {{ $foodDonationLog->actor_name }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </main>
@endsection
