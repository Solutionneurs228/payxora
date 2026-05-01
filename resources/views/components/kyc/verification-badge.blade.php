@props(['status'])

@php
$configs = [
    'pending' => ['color' => 'bg-yellow-100 text-yellow-700', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'KYC en attente'],
    'approved' => ['color' => 'bg-emerald-100 text-emerald-700', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'KYC verifie'],
    'rejected' => ['color' => 'bg-red-100 text-red-700', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'KYC rejete'],
];

$config = $configs[$status] ?? $configs['pending'];
@endphp

<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['color'] }}">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
    </svg>
    {{ $config['label'] }}
</span>