@props(['status'])

@php
$classes = match($status) {
    'pending', 'en_attente' => 'bg-yellow-100 text-yellow-800',
    'completed', 'terminee', 'approved', 'approuve' => 'bg-green-100 text-green-800',
    'cancelled', 'annulee', 'rejected', 'rejete' => 'bg-red-100 text-red-800',
    'paid', 'payee' => 'bg-blue-100 text-blue-800',
    'disputed', 'en_litige' => 'bg-orange-100 text-orange-800',
    'in_delivery', 'en_livraison' => 'bg-purple-100 text-purple-800',
    'open', 'ouvert' => 'bg-yellow-100 text-yellow-800',
    'mediation' => 'bg-indigo-100 text-indigo-800',
    'resolved', 'resolu' => 'bg-green-100 text-green-800',
    'closed', 'ferme' => 'bg-gray-100 text-gray-800',
    default => 'bg-gray-100 text-gray-800',
};

$labels = [
    'pending' => 'En attente',
    'completed' => 'Terminee',
    'cancelled' => 'Annulee',
    'paid' => 'Payee',
    'disputed' => 'En litige',
    'in_delivery' => 'En livraison',
    'open' => 'Ouvert',
    'mediation' => 'Mediation',
    'resolved' => 'Resolu',
    'closed' => 'Ferme',
    'approved' => 'Approuve',
    'rejected' => 'Rejete',
];
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $classes }}">
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
