@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)

@section('main')
    <main class="p-6 text-slate-900">
        @foreach ($recipients as $recipient)
            <a href="{{ route('recipients.show', ['recipient' => $recipient]) }}">
                <section class="border border-1 p-6 rounded-md mb-4">
                    <h1 class="capitalize font-bold text-2xl">{{ $recipient->name }}</h1>
                    <div class="text-slate-500 flex items-center gap-1">
                        <x-heroicon-o-calendar class="w-[14px] h-[14px]" />
                        <p class="text-xs ">Created at {{ $recipient->created_at }}</p>
                    </div>
                    <div class="flex mt-4 gap-[10px]">
                        <div class="w-11 h-11 bg-[#F4F6FA] rounded-md flex items-center justify-center">
                            <x-heroicon-o-user-group class="w-6 h-6" />
                        </div>
                        <div>
                            <p>{{ $recipient->family_members }} Family members</p>
                            <p class="text-xs text-slate-500">NIK {{ $recipient->nik }}</p>
                        </div>
                    </div>
                </section>
            </a>
        @endforeach
    </main>
@endsection
