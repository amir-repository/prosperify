@extends('layouts.manager.index')

@section('main')
    <main>
        <section class="flex justify-center items-center my-6">
            <div
                class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <form class="space-y-6" method="POST" action="{{ route('donations.store') }}">
                    @csrf
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Food Donation</h5>
                    <div>
                        <label for="title"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                        <input type="text" name="title" id="title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Judul Donasi" required>
                    </div>
                    <div>
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <input type="text" name="description" id="description"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Jalan bersama" required>
                    </div>
                    <div>
                        <label for="donation_date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                            pelaksanaan donasi</label>
                        <input type="date" id="donation_date" name="donation_date" class="border-none" required>

                    </div>
                    <div>
                        <select name="recipientID" id="recipient"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach ($recipients as $recipient)
                                <option value="{{ $recipient->id }}" selected>{{ $recipient->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Lanjut</button>
                </form>
            </div>
        </section>
    </main>
@endsection
