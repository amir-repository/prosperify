@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)
@section('main')
    <main class="px-6">
        <form action="{{ route('rescues.update', ['rescue' => $rescue]) }}" method="post">
            @csrf
            @method('put')
            <h1 class="text-lg font-bold">Food Rescue</h1>
            <section class="mt-4">
                <div class="mb-4">
                    <label for="title" class="text-sm font-medium block mb-[6px]">Title</label>
                    <input id="title" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('title') ? old('title') : $rescue->title }}" placeholder="Donasi berkah"
                        name="title" required>
                    @error('title')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="text-sm font-medium block mb-[6px]">Description</label>
                    <input id="description" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('description') ? old('description') : $rescue->description }}"
                        placeholder="Donasi berkah adalah ..." name="description" required>
                    @error('description')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="pickup_address" class="text-sm font-medium block mb-[6px]">Pick-up address</label>
                    <input id="pickup_address" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('pickup_address') ? old('pickup_address') : $rescue->pickup_address }}"
                        placeholder="Jalan ..." name="pickup_address" required>
                    @error('pickup_address')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="rescue_date" class="text-sm font-medium block mb-[6px]">Pick-up date</label>
                    <input id="rescue_date" type="datetime-local" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('rescue_date') ? old('rescue_date') : Carbon\Carbon::createFromFormat('d M Y H:i', $rescue->rescue_date)->toDateTimeLocalString() }}"
                        placeholder="Donasi berkah adalah ..." name="rescue_date" value="" required>
                    @error('rescue_date')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>
            <h2 class="text-lg font-bold mt-8">Donor</h2>
            <section class="mt-4">
                <div class="mb-4">
                    <label for="donor_name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <input id="donor_name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ $rescue->user->name }}" placeholder="Nama saya" name="donor_name" required>
                    @error('donor_name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-4">
                    <div class="mb-4 flex-1">
                        <label for="phone" class="text-sm font-medium block mb-[6px]">Phone</label>
                        <input id="phone" type="text" class="border border-slate-200 rounded-md w-full"
                            value="{{ $rescue->user->phone }}" placeholder="081234567890" name="phone" required>
                        @error('phone')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="email" class="text-sm font-medium block mb-[6px]">Email</label>
                        <input id="email" type="email" class="border border-slate-200 rounded-md w-full"
                            value="{{ $rescue->user->email }}" placeholder="name@mail.com" name="email" required>
                        @error('email')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>
            <button onclick="return confirm('Are you sure?')"
                class="py-2 bg-slate-900 text-white w-full rounded-md mt-8">Update</button>
        </form>
        <form action="{{ route('rescues.destroy', ['rescue' => $rescue]) }}" method="post">
            @csrf
            @method('delete')
            <button onclick="return confirm('Are you sure?')"
                class="py-2 border border-slate-900 text-slate-900 w-full rounded-md mt-6">Delete</button>
        </form>
    </main>
@endsection
