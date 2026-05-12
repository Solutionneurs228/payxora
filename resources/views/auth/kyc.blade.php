<x-app-layout>
    <div class="container">
        <h2>KYC</h2>

        @if(session('success'))
            <div>{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="text" name="full_name" placeholder="Nom complet" required>
            <input type="date" name="birth_date" required>
            <input type="text" name="nationality" placeholder="Nationalité" required>

            <input type="text" name="document_type" placeholder="Type document" required>
            <input type="text" name="document_number" placeholder="Numéro document" required>

            <textarea name="address" placeholder="Adresse"></textarea>

            <label>Recto document</label>
            <input type="file" name="document_front" required>

            <label>Verso document</label>
            <input type="file" name="document_back">

            <label>Selfie</label>
            <input type="file" name="selfie" required>

            <button type="submit">Soumettre</button>
        </form>

        @if($kyc)
            <hr>
            <p>Statut : {{ $kyc->status->value }}</p>
        @endif
    </div>
</x-app-layout>
