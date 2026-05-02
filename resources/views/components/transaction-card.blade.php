@props(['transaction'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
    <div class="p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $transaction->title }}</h3>
            <x-status-badge :status="$transaction->status" />
        </div>

        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($transaction->description, 100) }}</p>

        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                <span class="font-medium text-gray-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="text-xs text-gray-400">
                {{ $transaction->created_at->diffForHumans() }}
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center space-x-2 text-xs text-gray-500">
                <span>Vendeur: {{ $transaction->seller->name ?? 'N/A' }}</span>
            </div>
            <a href="{{ route('transactions.show', $transaction) }}" 
               class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                Voir details
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
