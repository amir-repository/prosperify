@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Edit recipient</h1>
        <form onsubmit="return confirm('Are you sure');" action="{{ route('recipients.update', compact('recipient')) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            <input hidden id="recipient_status_id" type="text" class="border border-slate-200 rounded-md w-full"
                value="{{ $recipient->recipient_status_id }}" name="recipient_status_id" required>
            <section class="mt-4">
                <div class="mb-4">
                    <label for="name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <input id="name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('name') ? old('name') : $recipient->name }}" placeholder="Name" name="name"
                        required>
                    @error('name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="nik" class="text-sm font-medium block mb-[6px]">NIK</label>
                    <input id="nik" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('nik') ? old('nik') : $recipient->nik }}" placeholder="NIK" name="nik" required>
                    @error('nik')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="address" class="text-sm font-medium block mb-[6px]">Address</label>
                    <input id="address" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('address') ? old('address') : $recipient->address }}" placeholder="Address"
                        name="address" required>
                    @error('address')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="phone" class="text-sm font-medium block mb-[6px]">Phone</label>
                    <input id="phone" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('phone') ? old('phone') : $recipient->phone }}" placeholder="Phone" name="phone"
                        required>
                    @error('phone')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="family_members" class="text-sm font-medium block mb-[6px]">Family Members</label>
                    <input id="family_members" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ old('family_members') ? old('family_members') : $recipient->family_members }}"
                        placeholder="4" name="family_members" required>
                    @error('family_members')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="photo" class="text-sm font-medium block mb-[6px]">Photo</label>
                    <input id="photo" type="file" class="border border-slate-200 rounded-md w-full p-2"
                        name="photo" required>
                </div>
                <Button class="mt-6 py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Update</Button>
            </section>
        </form>
    </main>
@endsection
