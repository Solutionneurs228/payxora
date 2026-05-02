@extends('layouts.app')

@section('title', 'Mes litiges — PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Mes litiges</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-medium">Transaction</th>
                        <th class="px-6 py-3 font-medium">Motif</th>
                        <th class="px-6 py-3 font-medium">Statut</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($disputes as $dispute)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $dispute->transaction->product_name }}</p>
                            <p class="text-xs text-gray-500">{{ $dispute->transaction->reference }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ Str::limit($dispute->reason, 50) }}</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$dispute->status->value" />
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $dispute->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('disputes.show', $dispute) }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <p>Aucun litige</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($disputes->hasPages())
    <div class="mt-6">
        {{ $disputes->links() }}
    </div>
    @endif
</div>
@endsection
