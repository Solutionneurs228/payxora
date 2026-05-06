@props(['status'])

@php
$classes = match($status) {
    'approved' => 'bg-green-100 text-green-800',
    'pending' => 'bg-yellow-100 text-yellow-800',
    'rejected' => 'bg-red-100 text-red-800',
    default => 'bg-gray-100 text-gray-800',
};

$labels = match($status) {
    'approved' => 'Verifie',
    'pending' => 'En attente',
    'rejected' => 'Refuse',
    default => 'Non soumis',
};
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $labels }}
</span>
