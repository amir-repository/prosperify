@extends(
    auth()->user()->hasRole('donor')
        ? 'layouts.index'
        : 'layouts.manager.index'
)

@section('main')
    <main class="p-6 border border-black m-6 rounded-md">
        <p class="text-right">Rescue ID: {{ $storedReceipt->rescueAssignment->rescue->id }}</p>
        <p class="text-right">Receipt Date: {{ $storedReceipt->created_at }}</p>
        <div class="flex justify-between mt-4">
            <div class="text-center">
                <h1>Food. #{{ $storedReceipt->rescueAssignment->food->id }}</h1>
                <p class="text-2xl font-bold">{{ $storedReceipt->rescueAssignment->food->name }}</p>
            </div>
            <div class="text-center">
                <h1>Stored Amount</h1>
                <p class="text-2xl font-bold">
                    {{ $storedReceipt->stored_amount }}.<span
                        class="text-lg">{{ $storedReceipt->rescueAssignment->food->unit->name }}</span>
                </p>
            </div>
        </div>
        <hr class="my-6 border-black">
        <div>
            <h2>Assigner. #{{ $storedReceipt->rescueAssignment->assigner_id }}</h2>
            <p class="text-xl font-bold">{{ $storedReceipt->rescueAssignment->assigner->name }}</p>
            <img class="w-64" src="{{ asset('storage/' . $storedReceipt->admin_signature) }}">
        </div>
        <div class="mt-6">
            <h2>Volunteer. #{{ $storedReceipt->rescueAssignment->volunteer->id }}</h2>
            <p class="text-xl font-bold">{{ $storedReceipt->rescueAssignment->volunteer->name }}</p>
        </div>
        <div class="mt-6">
            <h2>Donor. #{{ $storedReceipt->rescueAssignment->rescue->user_id }}</h2>
            <p class="text-xl font-bold">{{ $storedReceipt->rescueAssignment->rescue->donor_name }}</p>
        </div>
        <div class="mt-6">
            <h2>Vault. #{{ $storedReceipt->rescueAssignment->vault->id }}</h2>
            <p class="text-xl font-bold">{{ $storedReceipt->rescueAssignment->vault->name }}</p>
        </div>
    </main>
@endsection
