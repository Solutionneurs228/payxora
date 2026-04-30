
# === ADMIN PAGES (7 fichiers) ===

admin_dashboard = r"""@extends('layouts.app')

@section('title', 'Administration')

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Tableau de bord Admin</h1>
            <p class="text-slate-500">Vue d'ensemble de la plateforme</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_users'] }}</p>
                <p class="text-sm text-slate-500 mt-1">Utilisateurs</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['verified_users'] }}</p>
                <p class="text-sm text-slate-500 mt-1">KYC Verifies</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_transactions'] }}</p>
                <p class="text-sm text-slate-500 mt-1">Transactions</p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_volume'], 0, ',', ' ') }} F</p>
                <p class="text-sm text-slate-500 mt-1">Volume total</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-900 mb-4">KYC en attente</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-amber-600">{{ $stats['pending_kyc'] }}</p>
                        <p class="text-sm text-slate-500">Utilisateurs a valider</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors">Voir</a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Litiges ouverts</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-red-600">{{ $stats['open_disputes'] }}</p>
                        <p class="text-sm text-slate-500">A arbitrer</p>
                    </div>
                    <a href="{{ route('admin.disputes.index') }}" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">Voir</a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Commissions</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold text-emerald-600">{{ number_format($stats['total_commissions'], 0, ',', ' ') }} F</p>
                        <p class="text-sm text-slate-500">Revenus generes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900">Transactions recentes</h3>
                    <a href="{{ route('admin.transactions.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700">Voir tout</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($recentTransactions as $t)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ Str::limit($t->product_name, 25) }}</p>
                                <p class="text-xs text-slate-500">{{ $t->reference }}</p>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">{{ number_format($t->amount, 0, ',', ' ') }} F</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900">Nouveaux utilisateurs</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700">Voir tout</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($recentUsers as $u)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">{{ $u->initials }}</div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $u->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $u->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $u->kyc_status === 'verified' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $u->kyc_status }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
"""

admin_users_index = r"""@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Utilisateurs</h1>
            <p class="text-slate-500">Gestion des utilisateurs et KYC</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Utilisateur</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">KYC</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Inscription</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">{{ $user->initials }}</div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $user->full_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 capitalize">{{ $user->role }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->kyc_status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($user->kyc_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $user->kyc_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Actif' : 'Suspendu' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">{{ $users->links() }}</div>
        </div>
    </div>
</section>
@endsection
"""

admin_users_show = r"""@extends('layouts.app')

@section('title', 'Utilisateur #' . $user->id)

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour
            </a>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xl">{{ $user->initials }}</div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $user->full_name }}</h1>
                    <p class="text-slate-500">{{ $user->email }} | {{ $user->phone }}</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Informations</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-slate-500">Role:</span> <span class="font-medium capitalize">{{ $user->role }}</span></div>
                        <div><span class="text-slate-500">KYC:</span> <span class="font-medium">{{ $user->kyc_status }}</span></div>
                        <div><span class="text-slate-500">Ville:</span> <span class="font-medium">{{ $user->city ?? 'N/A' }}</span></div>
                        <div><span class="text-slate-500">Inscrit le:</span> <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Transactions</h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-2xl font-bold text-slate-900">{{ $user->transactionsAsSeller->count() }}</p>
                            <p class="text-xs text-slate-500">Ventes</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-2xl font-bold text-slate-900">{{ $user->transactionsAsBuyer->count() }}</p>
                            <p class="text-xs text-slate-500">Achats</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-2xl font-bold text-slate-900">{{ $user->disputesOpened->count() }}</p>
                            <p class="text-xs text-slate-500">Litiges</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($user->kyc_status === 'pending')
                            <form method="POST" action="{{ route('admin.users.validate-kyc', $user) }}">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all">
                                    Valider KYC
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                            @csrf
                            <button type="submit" class="w-full py-3 {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white font-semibold rounded-xl transition-all">
                                {{ $user->is_active ? 'Suspendre' : 'Reactiver' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
"""

with open('/mnt/agents/output/payxora-togo/resources/views/admin/dashboard.blade.php', 'w') as f:
    f.write(admin_dashboard)

with open('/mnt/agents/output/payxora-togo/resources/views/admin/users/index.blade.php', 'w') as f:
    f.write(admin_users_index)

with open('/mnt/agents/output/payxora-togo/resources/views/admin/users/show.blade.php', 'w') as f:
    f.write(admin_users_show)

print("✅ admin/dashboard + users/index + users/show crees (3 fichiers)")
print("📊 Reste: 6 fichiers (transactions 2 + disputes 2 + CSS 1 + JS 1)")
