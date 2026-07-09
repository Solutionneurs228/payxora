@props(['status'])

@php
// Si c'est un enum, récupérer la value
$statusValue = is_object($status) && enum_exists(get_class($status)) ? $status->value : $status;

$color = match($statusValue) {
    'pending', 'pending_payment', 'draft' => 'yellow',
    'paid', 'funded', 'shipped' => 'blue',
    'delivered' => 'purple',
    'completed' => 'green',
    'cancelled' => 'gray',
    'disputed' => 'red',
    'refunded' => 'slate',
    default => 'gray',
};

$label = match($statusValue) {
    'pending' => 'En attente',
    'pending_payment' => 'En attente de paiement',
    'draft' => 'Brouillon',
    'paid' => 'Paye',
    'funded' => 'En sequestre',
    'shipped' => 'Expedie',
    'delivered' => 'Livre',
    'completed' => 'Termine',
    'cancelled' => 'Annule',
    'disputed' => 'En litige',
    'refunded' => 'Rembourse',
    default => ucfirst($statusValue),
};
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
    {{ $label }}
</span>
