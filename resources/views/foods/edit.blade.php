@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)

@section('main')
    <main class="px-6">
        <h1 class="text-lg font-bold">Edit Food</h1>
        <form onsubmit="return confirm('Are you sure');"
            action="{{ route('rescues.foods.update', ['rescue' => $rescue, 'food' => $food]) }}" method="post"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            <section class="mt-4">
                <div class="mb-4">
                    <label for="name" class="text-sm font-medium block mb-[6px]">Name</label>
                    <input id="name" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ $food->name }}" placeholder="Gula manis" name="name" required>
                    @error('name')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="detail" class="text-sm font-medium block mb-[6px]">Detail</label>
                    <input id="detail" type="text" class="border border-slate-200 rounded-md w-full"
                        value="{{ $food->detail }}" placeholder="Gula putih manis" name="detail" required>
                    @error('detail')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-4">
                    <div class="mb-4 flex-1">
                        <label for="amount" class="text-sm font-medium block mb-[6px]">Amount</label>
                        <input id="amount" type="number" class="border border-slate-200 rounded-md w-full"
                            value="{{ $food->amount }}" placeholder="5" name="amount" required>
                        @error('amount')
                            <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4 flex-1">
                        <label for="unit" class="text-sm font-medium block mb-[6px]">Unit</label>
                        <select name="unit_id" id="unit" class="border border-slate-200 rounded-md w-full" required>
                            @foreach ($units as $unit)
                                <option @selected($food->unit_id == $unit->id) value="{{ $unit->id }}">
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="expired_date" class="text-sm font-medium block mb-[6px]">Expired date</label>
                    <input id="expired_date" type="date" class="border border-slate-200 rounded-md w-full"
                        value="{{ Carbon\Carbon::parse($food->expired_date)->format('Y-m-d') }}" name="expired_date"
                        required>
                    @error('expired_date')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <h1></h1>
                    <label for="unit" class="text-sm font-medium block mb-[6px]">Group</label>
                    <select name="sub_category_id" id="sub_category" class="border border-slate-200 rounded-md w-full"
                        required>
                        @foreach ($foodSubCategories as $foodSubCategory)
                            <option @selected($food->sub_category_id == $foodSubCategory->id) value="{{ $foodSubCategory->id }}">
                                {{ $foodSubCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="photo" class="text-sm font-medium block mb-[6px]">Picture</label>
                    <input id="photo" type="file" class="py-2 px-2 border border-slate-200 rounded-md w-full"
                        name="photo" required>
                </div>
                <Button class="mt-8 py-2 w-full bg-slate-900 text-white rounded-md text-sm font-medium">Update</Button>
            </section>
        </form>
    </main>
@endsection
