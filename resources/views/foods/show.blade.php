@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)

@section('main')
    <main class="p-6 text-slate-900">
        <div>
            <div>
                <img class="h-36 w-full bg-slate-200 rounded-md object-cover" src="{{ asset("storage/$food->photo") }}"
                    alt="">
            </div>
            <div class="flex items-end justify-between">
                <h1 class="text-2xl font-bold mt-3">{{ $food->name }}</h1>
                <form action="{{ route('rescues.foods.destroy', ['rescue' => $rescue, 'food' => $food]) }}" method="post">
                    @csrf
                    @method('delete')
                    <button class="w-8 h-8 flex items-center justify-center">
                        <x-heroicon-o-trash class="w-[18px] h-[18px] text-red-600" />
                    </button>
                </form>
            </div>
            <p>{{ $food->detail }}</p>
            <div class="flex items-center gap-4 mt-3">
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-archive-box class="w-[18px] h-[18px]" />{{ $food->amount }}.{{ $food->unit->name }}
                </p>
                <p class="text-sm flex gap-1">
                    <x-heroicon-o-calendar class="w-[18px] h-[18px]" />Exp.
                    {{ Carbon\Carbon::parse($food->expired_date)->format('d M Y') }}

                </p>
            </div>
            <a href="{{ route('rescues.foods.edit', ['rescue' => $rescue, 'food' => $food]) }}"
                class="block py-2 bg-slate-900 text-white w-full rounded-md text-sm font-medium mt-4 text-center ">Ubah</a>
        </div>
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">Riwayat</h2>
            @foreach ($foodRescueUsers as $foodRescueUser)
                <section class="p-6 border border-slate-200 rounded-md mb-4 flex items-center gap-4">
                    <div>
                        <img class="w-[72px] h-[72px] rounded-md object-cover"
                            src="{{ asset("storage/$foodRescueUser->photo") }}" alt="">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">
                            {{ $foodRescueUser->amount }}.<span class="text-base">{{ $foodRescueUser->unit->name }}</span>
                        </h3>
                        <p class="text-slate-500">
                            {{ $foodRescueUser->user->name }}</p>
                        <p class="text-xs text-slate-500">
                            <span class="capitalize">{{ $foodRescueUser->rescueStatus->name }}</span>
                            {{ Carbon\Carbon::parse($foodRescueUser->created_at)->format('d M Y h:i:s') }}
                        </p>
                    </div>
                </section>
            @endforeach

        </div>
    </main>
@endsection
