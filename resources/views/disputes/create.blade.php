
# === DISPUTES : CREATE & SHOW (recréation complète) ===

dispute_create = r"""@extends('layouts.app')

@section('title', 'Ouvrir un litige')

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('transactions.show', $transaction) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Ouvrir un litige</h1>
            <p class="text-slate-500">Transaction: {{ $transaction->reference }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800">Attention</p>
                    <p class="text-sm text-red-700">N'ouvrez un litige qu'en cas de probleme reel. Un litige abusif peut entrainer la suspension de votre compte.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('disputes.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Motif du litige</label>
                    <select name="reason" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                        <option value="">Selectionnez un motif</option>
                        <option value="not_received">Non recu</option>
                        <option value="not_as_described">Non conforme a la description</option>
                        <option value="damaged">Produit endommage</option>
                        <option value="wrong_item">Mauvais article recu</option>
                        <option value="seller_no_ship">Vendeur n'a pas expedie</option>
                        <option value="other">Autre</option>
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description detaillee</label>
                    <textarea name="description" id="description" rows="5" required minlength="20"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white resize-none"
                        placeholder="Decrivez precisement le probleme rencontre..."></textarea>
                    <p class="mt-1 text-xs text-slate-400">Minimum 20 caracteres</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Preuve (photo, capture d'ecran)</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-emerald-400 transition-colors cursor-pointer" onclick="document.getElementById('evidence').click()">
                        <input type="file" name="evidence" id="evidence" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                        <svg class="w-10 h-10 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-slate-500">Cliquez pour ajouter une preuve</p>
                        <p class="text-xs text-slate-400">JPG, PNG ou PDF (max 5Mo)</p>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-lg shadow-red-600/20 transition-all transform hover:-translate-y-0.5">
                    Ouvrir le litige
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
"""

dispute_show = r"""@extends('layouts.app')

@section('title', 'Litige #' . $dispute->id)

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('disputes.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux litiges
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Litige #{{ $dispute->id }}</h1>
                    <p class="text-slate-500">Transaction: {{ $transaction->reference }}</p>
                </div>
                @php
                $statusColors = ['open' => 'red', 'under_review' => 'amber', 'resolved_buyer' => 'emerald', 'resolved_seller' => 'emerald', 'closed' => 'slate'];
                $statusLabels = ['open' => 'Ouvert', 'under_review' => 'En cours', 'resolved_buyer' => 'Resolu (acheteur)', 'resolved_seller' => 'Resolu (vendeur)', 'closed' => 'Ferme'];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $statusColors[$dispute->status] }}-100 text-{{ $statusColors[$dispute->status] }}-800 w-fit">
                    {{ $statusLabels[$dispute->status] }}
                </span>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Conversation</h3>
                    <div class="space-y-4 max-h-96 overflow-y-auto mb-6">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs flex-shrink-0">
                                {{ $dispute->opener->initials }}
                            </div>
                            <div class="flex-1">
                                <div class="bg-slate-100 rounded-xl rounded-tl-none p-3">
                                    <p class="text-sm font-medium text-slate-800">{{ $dispute->opener->full_name }}</p>
                                    <p class="text-sm text-slate-600 mt-1">{{ $dispute->description }}</p>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">{{ $dispute->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @foreach($dispute->messages as $message)
                            <div class="flex gap-3 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                <div class="w-8 h-8 rounded-full {{ $message->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700' }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ $message->user->initials }}
                                </div>
                                <div class="flex-1 {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                                    <div class="inline-block {{ $message->user_id === auth()->id() ? 'bg-emerald-100 rounded-xl rounded-tr-none' : 'bg-slate-100 rounded-xl rounded-tl-none' }} p-3">
                                        <p class="text-sm font-medium text-slate-800">{{ $message->user->full_name }} {{ $message->is_admin ? '(Admin)' : '' }}</p>
                                        <p class="text-sm text-slate-600 mt-1">{{ $message->message }}</p>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(in_array($dispute->status, ['open', 'under_review']))
                        <form method="POST" action="{{ route('disputes.reply', $dispute) }}" class="flex gap-3">
                            @csrf
                            <input type="text" name="message" required placeholder="Votre message..."
                                class="flex-1 px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all">
                                Envoyer
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Details</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Motif</span>
                            <span class="font-medium text-slate-900">{{ $dispute->getReasonLabel() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Ouvert par</span>
                            <span class="font-medium text-slate-900">{{ $dispute->opener->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Date</span>
                            <span class="font-medium text-slate-900">{{ $dispute->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($dispute->resolved_at)
                            <div class="flex justify-between pt-3 border-t border-slate-100">
                                <span class="text-slate-500">Resolu le</span>
                                <span class="font-medium text-slate-900">{{ $dispute->resolved_at->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Transaction liee</h3>
                    <a href="{{ route('transactions.show', $transaction) }}" class="block p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <p class="font-medium text-slate-900">{{ Str::limit($transaction->product_name, 30) }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
"""

with open('/mnt/agents/output/payxora-togo/resources/views/disputes/create.blade.php', 'w') as f:
    f.write(dispute_create)

with open('/mnt/agents/output/payxora-togo/resources/views/disputes/show.blade.php', 'w') as f:
    f.write(dispute_show)

# Fix empty disputes/index
with open('/mnt/agents/output/payxora-togo/resources/views/disputes/index.blade.php', 'w') as f:
    f.write(disputes_index)

print("✅ 3 pages litiges crees/corriges")
