@props(['amount', 'currency' => 'XOF'])

<span class="font-semibold text-slate-900">
    {{ number_format($amount, 0, ',', ' ') }} <span class="text-sm text-slate-500">{{ $currency }}</span>
</span>