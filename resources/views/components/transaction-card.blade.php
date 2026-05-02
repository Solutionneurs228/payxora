@props(['transaction'])

<div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <p class="font-medium text-gray-900">{{ $transaction->product_name }}</p>
                <p class="text-xs text-gray-500">{{ $transaction->reference }} &middot; {{ $transaction->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="font-semibold text-gray-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
            <x-status-badge :status="$transaction->status->value" />
        </div>
    </div>
    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span>Vendeur: {{ $transaction->seller->name }}</span>
            @if($transaction->buyer)
            <span>Acheteur: {{ $transaction->buyer->name }}</span>
            @endif
        </div>
        <a href="{{ route('transactions.show', $transaction) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
            Voir details &rarr;
        </a>
    </div>
</div>
