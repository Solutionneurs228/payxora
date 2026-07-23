@extends('layouts.app')

@section('title', 'Gestion Utilisateurs - Admin PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Gestion des Utilisateurs</h1>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md mb-6">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md mb-6">
            <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Utilisateur</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Contact</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">KYC</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Documents</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Motif</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="{{ !$user->is_active ? 'bg-red-50' : '' }} hover:bg-gray-50 transition cursor-pointer"
                    onclick="window.location='{{ route('admin.users.show', $user) }}'">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                {{ $user->initials }}
                            </div>
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline"
                                   onclick="event.stopPropagation();">
                                    {{ $user->name }}
                                </a>
                                <p class="text-xs text-gray-500">Inscrit {{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="hover:text-indigo-600 hover:underline"
                           onclick="event.stopPropagation();">
                            {{ $user->email }}
                        </a>
                        <p>{{ $user->phone }}</p>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' :
                               ($user->role === 'seller' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ match($user->role) { 'admin' => 'Admin', 'seller' => 'Vendeur', 'buyer' => 'Acheteur', default => $user->role } }}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        @if($user->kyc)
                            <div class="space-y-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full block w-fit
                                    {{ $user->kyc->status === 'verified' ? 'bg-green-100 text-green-800' :
                                       ($user->kyc->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ match($user->kyc->status) {
                                        'verified' => 'Verifie',
                                        'rejected' => 'Rejete',
                                        'pending' => 'En attente',
                                        'not_submitted' => 'Non soumis',
                                        default => $user->kyc->status
                                    } }}
                                </span>
                                @if($user->kyc->document_type)
                                    <p class="text-xs text-gray-500">{{ $user->kyc->document_type }}: {{ $user->kyc->document_number }}</p>
                                @endif
                            </div>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Non soumis</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($user->kyc)
                            <div class="flex gap-1 flex-wrap">
                                @if($user->kyc->document_front)
                                    <span class="px-1.5 py-0.5 text-xs bg-blue-50 text-blue-700 rounded">Recto</span>
                                @endif
                                @if($user->kyc->document_back)
                                    <span class="px-1.5 py-0.5 text-xs bg-blue-50 text-blue-700 rounded">Verso</span>
                                @endif
                                @if($user->kyc->selfie)
                                    <span class="px-1.5 py-0.5 text-xs bg-blue-50 text-blue-700 rounded">Selfie</span>
                                @endif
                            </div>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="space-y-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Actif' : 'Suspendu' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs">
                        @if($user->kyc && $user->kyc->admin_notes)
                            <p class="text-xs text-red-600 truncate" title="{{ $user->kyc->admin_notes }}">{{ $user->kyc->admin_notes }}</p>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm space-y-2">
                        @if($user->kyc)
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="inline-block px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 transition w-full text-center"
                               onclick="event.stopPropagation();">
                                Voir KYC
                            </a>
                        @endif

                        @if($user->is_active)
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}"
                                  onsubmit="event.stopPropagation(); return confirm('Suspendre cet utilisateur ?');">
                                @csrf
                                <input type="hidden" name="reason" value="Suspension admin">
                                <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition w-full"
                                        onclick="event.stopPropagation();">
                                    Suspendre
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}"
                                  onclick="event.stopPropagation();">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition w-full"
                                        onclick="event.stopPropagation();">
                                    Reactiver
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
