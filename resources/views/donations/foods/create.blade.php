@extends('layouts.manager.index')

@section('main')
    <main>
        <section class="flex justify-center items-center my-6">
            <div
                class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <form class="space-y-6" method="POST" action="{{ route('donations.foods.store', ['donation' => $donation]) }}">
                    @csrf
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Tambah Makanan</h5>
                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                            makanan</label>
                        <select id="foodID" name="food_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach ($foods as $food)
                                <option value="{{ $food->id }}">{{ $food->name }},
                                    {{ $food->amount }}
                                    {{ $food->unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label for="amount"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kuantitas</label>
                            <input type="number" name="outbound_plan" id="amount"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="1" min="1" max="23" value="1" required>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah
                        Makanan</button>
                </form>
            </div>
        </section>
    </main>
@endsection
