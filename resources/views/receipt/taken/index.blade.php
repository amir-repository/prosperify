@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)

@section('main')
    <main class="p-6 border border-black m-6 rounded-md">
        <p class="text-right">Rescue ID: {{ $takenReceipt->rescueAssignment->rescue->id }}</p>
        <p class="text-right">Receipt Date: {{ $takenReceipt->created_at }}</p>
        <div class="flex justify-between mt-4">
            <div class="text-center">
                <h1>Food. #{{ $takenReceipt->rescueAssignment->food->id }}</h1>
                <p class="text-2xl font-bold">{{ $takenReceipt->rescueAssignment->food->name }}</p>
            </div>
            <div class="text-center">
                <h1>Taken Amount</h1>
                <p class="text-2xl font-bold">
                    {{ $takenReceipt->taken_amount }}.<span
                        class="text-lg">{{ $takenReceipt->rescueAssignment->food->unit->name }}</span>
                </p>
            </div>
        </div>
        <hr class="my-6 border-black">
        <div>
            <h2>Assigner. #{{ $takenReceipt->rescueAssignment->assigner_id }}</h2>
            <p class="text-xl font-bold">{{ $takenReceipt->rescueAssignment->assigner->name }}</p>
        </div>
        <div class="mt-6">
            <h2>Volunteer. #{{ $takenReceipt->rescueAssignment->volunteer->id }}</h2>
            <p class="text-xl font-bold">{{ $takenReceipt->rescueAssignment->volunteer->name }}</p>
        </div>
        <div class="mt-6">
            <h2>Donor. #{{ $takenReceipt->rescueAssignment->rescue->user_id }}</h2>
            <p class="text-xl font-bold">{{ $takenReceipt->rescueAssignment->rescue->donor_name }}</p>
            <img class="w-64" src="{{ asset('storage/' . $takenReceipt->donor_signature) }}">
        </div>
        <div class="mt-6">
            <h2>Vault. #{{ $takenReceipt->rescueAssignment->vault->id }}</h2>
            <p class="text-xl font-bold">{{ $takenReceipt->rescueAssignment->vault->name }}</p>
        </div>
    </main>
@endsection
