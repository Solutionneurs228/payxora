@extends('layouts.app')

@section('title', 'Transactions — Admin PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Toutes les transactions</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-medium">Reference</th>
                        <th class="px-6 py-3 font-medium">Produit</th>
                        <th class="px-6 py-3 font-medium">Vendeur</th>
                        <th class="px-6 py-3 font-medium">Acheteur</th>
                        <th class="px-6 py-3 font-medium">Montant</th>
                        <th class="px-6 py-3 font-medium">Statut</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        onclick="window.location='{{ route('admin.transactions.show', $tx) }}'">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $tx->reference }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ Str::limit($tx->product_name, 30) }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.users.show', $tx->seller) }}"
                               class="text-indigo-600 hover:text-indigo-800 hover:underline"
                               onclick="event.stopPropagation();">
                                {{ $tx->seller->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($tx->buyer)
                                <a href="{{ route('admin.users.show', $tx->buyer) }}"
                                   class="text-indigo-600 hover:text-indigo-800 hover:underline"
                                   onclick="event.stopPropagation();">
                                    {{ $tx->buyer->name }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium">{{ number_format($tx->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$tx->status->value" />
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Aucune transaction</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
