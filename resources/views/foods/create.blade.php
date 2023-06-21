@extends('layouts.index')

@section('main')
    <main>
        <section class="flex justify-center items-center my-6">
            <div
                class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700 mx-3">
                <form class="space-y-6" method="POST" action="{{ route('rescues.foods.store', ['rescue' => $rescue]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white ">Tambah Makanan</h5>
                    <input type="text" hidden name="user_id" value="{{ $rescue->user->id }}">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                            makanan</label>
                        <input type="text" name="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Gula manis" required>
                    </div>
                    <div>
                        <label for="detail"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Detail</label>
                        <input type="text" name="detail" id="detail"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Gula asli dari PT. Gula" required>
                    </div>
                    <div>
                        <label for="expired_date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                            kadaluarsa</label>
                        <input class="border-none" type="date" name="expired_date" id="expired_date" required>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label for="amount"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kuantitas</label>
                            <input type="number" name="amount" id="amount"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="1" min="1" value="3" required>
                        </div>
                        <div>
                            <label for="unit"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                            <select name="unit" id="unit"
                                class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="kg">Kg</option>
                                <option value="serving">Porsi</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="sub_category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sub
                            kategori</label>
                        <select id="sub_category" name="sub_category"
                            class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach ($foodSubCategories as $foodSubCategory)
                                <option value="{{ $foodSubCategory->id }}">{{ $foodSubCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            for="file_input">Dokumentasi Gambar</label>
                        <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="file_input" type="file" name="photo">
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah
                        Makanan</button>
                </form>
            </div>
        </section>
    </main>
@endsection
