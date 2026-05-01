@props(['status'])

@php
$colors = [
    'draft' => 'bg-gray-100 text-gray-700',
    'pending_payment' => 'bg-yellow-100 text-yellow-700',
    'funded' => 'bg-blue-100 text-blue-700',
    'shipped' => 'bg-indigo-100 text-indigo-700',
    'delivered' => 'bg-purple-100 text-purple-700',
    'completed' => 'bg-emerald-100 text-emerald-700',
    'disputed' => 'bg-red-100 text-red-700',
    'refunded' => 'bg-orange-100 text-orange-700',
    'cancelled' => 'bg-gray-100 text-gray-500',
];

$labels = [
    'draft' => 'Brouillon',
    'pending_payment' => 'En attente de paiement',
    'funded' => 'Paye - Sequestre',
    'shipped' => 'Expedie',
    'delivered' => 'Livre',
    'completed' => 'Termine',
    'disputed' => 'En litige',
    'refunded' => 'Rembourse',
    'cancelled' => 'Annule',
];

$color = $colors[$status] ?? 'bg-gray-100 text-gray-700';
$label = $labels[$status] ?? ucfirst($status);
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
    {{ $label }}
</span>