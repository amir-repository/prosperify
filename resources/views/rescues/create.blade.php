@extends('layouts.index')

@section('main')
    <main>
        <section class="flex justify-center items-center my-6">
            <div
                class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <form class="space-y-6" method="POST" action="{{ route('rescues.store') }}">
                    @csrf
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Food Rescue</h5>
                    <div>
                        <label for="donor_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                            donatur</label>
                        <input type="text" name="donor_name" id="donor_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Nama" value="{{ $user->name }}" required>
                    </div>
                    <div>
                        <label for="pickup_address"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat pengambilan
                            makanan</label>
                        <input type="text" name="pickup_address" id="pickup_address"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Jalan bersama" required>
                    </div>
                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor
                            Telepon</label>
                        <input type="text" name="phone" id="phone"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="081234567890" required>
                    </div>
                    <div>
                        <label for="email"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="081234567890" value="{{ $user->email }}" required>
                    </div>
                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul
                            donasi</label>
                        <input type="text" name="title" id="title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Donasi Berkah" required>
                    </div>
                    <div>
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <input type="text" name="description" id="description"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Donasi rutin setiap minggu ..." required>
                    </div>

                    <div class="flex">
                        <div class="flex-1">
                            <label for="rescue_date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                pengambilan</label>
                            <input class="border-none" type="date" id="rescue_date" name="rescue_date" required>
                        </div>
                        <div>
                            <label for="rescue_hours"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam
                                pengambilan</label>
                            <input type="number" name="rescue_hours" id="rescue_hours"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="1" min="1" max="23" value="8" required>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Lanjut</button>
                </form>
            </div>
        </section>
    </main>
@endsection
