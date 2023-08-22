@extends('layouts.manager.index')

@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <img class="h-36 w-full object-cover rounded-md" src="{{ asset("storage/$recipient->photo") }}" alt="">
        </div>
        @php
            $recipientCanceled = $recipient->recipient_status_id === 5;
        @endphp
        <div class="mt-3 flex items-center justify-between">
            <a @if (!$recipientCanceled) href="{{ route('recipients.edit', compact('recipient')) }}" @endif>
                <h1 class="text-2xl font-bold flex items-center gap-2">{{ $recipient->name }}
                    @if (!$recipientCanceled)
                        <x-heroicon-o-pencil-square class="w-[18px] h-[18px]" />
                    @endif
                </h1>
            </a>
            @if (!$recipientCanceled)
                <form onsubmit="return confirm('Are you sure');"
                    action="{{ route('recipients.update', compact('recipient')) }}" method="post">
                    @csrf
                    @method('put')
                    <input type="text" name="recipient_status_id" value="5" hidden>
                    <button>
                        <x-heroicon-o-trash class="w-[18px] h-[18px]" />
                    </button>
                </form>
            @endif
        </div>
        <div class="mt-3 flex gap-4">
            <p class="capitalize flex items-center gap-1"><x-heroicon-o-bell
                    class="w-[18px] h-[18px]" />{{ $recipient->recipientStatus->name }}
            </p>
            <p class="capitalize flex items-center gap-1"><x-heroicon-o-user-group
                    class="w-[18px] h-[18px]" />{{ $recipient->family_members }} Members
            </p>
        </div>
        <div class="mt-6">
            <div class="mb-4">
                <label class="text-sm font-medium block mb-[6px]">NIK</label>
                <input disabled type="text" class="border border-slate-200 rounded-md w-full"
                    value="{{ $recipient->nik }}">
            </div>
            <div class="mb-4">
                <label class="text-sm font-medium block mb-[6px]">Address</label>
                <input disabled type="text" class="border border-slate-200 rounded-md w-full"
                    value="{{ $recipient->address }}">
            </div>
            <div class="mb-4">
                <label class="text-sm font-medium block mb-[6px]">Phone</label>
                <input disabled type="text" class="border border-slate-200 rounded-md w-full"
                    value="{{ $recipient->phone }}">
            </div>
        </div>
        <div class="mt-7">
            <h2 class="font-bold text-[18px] mb-3">History</h2>
            @foreach ($recipientLogs as $recipientLog)
                <div class="p-6 border rounded-md mb-4">
                    <p>{{ $recipientLog->actor_name }}</p>
                    <p class="text-xs"><span class="capitalize">{{ $recipientLog->recipient_status_name }}</span> at
                        {{ $recipientLog->created_at }}
                    </p>
                </div>
            @endforeach
        </div>
    </main>
@endsection
