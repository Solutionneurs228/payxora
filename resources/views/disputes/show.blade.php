@extends('layouts.app')

@section('title', 'Litige #' . $dispute->id . ' — PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Litige</h1>
                <x-status-badge :status="$dispute->status->value" />
            </div>
            <p class="text-sm text-gray-500 mt-1">Transaction: {{ $dispute->transaction->product_name }}</p>
        </div>
        <a href="{{ route('disputes.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">
            &larr; Retour aux litiges
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Messages -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Echanges</h2>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($dispute->messages as $message)
                    <div class="flex gap-3 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <div class="w-8 h-8 rounded-full {{ $message->user_id === auth()->id() ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }} flex items-center justify-center font-bold text-xs shrink-0">
                            {{ substr($message->user->name, 0, 1) }}
                        </div>
                        <div class="{{ $message->user_id === auth()->id() ? 'bg-indigo-50' : 'bg-gray-50' }} rounded-lg p-3 max-w-md">
                            <p class="text-sm text-gray-700">{{ $message->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">Aucun message pour le moment</p>
                    @endforelse
                </div>

                @if($dispute->isOpen())
                <form method="POST" action="{{ route('disputes.reply', $dispute) }}" class="mt-4 pt-4 border-t border-gray-100">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="message" required placeholder="Votre message..."
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Envoyer
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Informations</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Initie par</span>
                        <span class="font-medium">{{ $dispute->initiator->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span class="font-medium">{{ $dispute->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($dispute->resolved_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Resolu le</span>
                        <span class="font-medium">{{ $dispute->resolved_at->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($dispute->resolution)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Resolution</h3>
                <p class="text-sm text-gray-700">{{ $dispute->resolution }}</p>
                @if($dispute->resolution_notes)
                <p class="text-xs text-gray-500 mt-2">{{ $dispute->resolution_notes }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
