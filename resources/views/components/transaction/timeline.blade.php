@props(['transaction'])

@php
$steps = [
    ['status' => 'draft', 'label' => 'Creee', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['status' => 'pending_payment', 'label' => 'Paiement', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
    ['status' => 'funded', 'label' => 'Sequestre', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
    ['status' => 'shipped', 'label' => 'Expedie', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
    ['status' => 'delivered', 'label' => 'Livre', 'icon' => 'M5 13l4 4L19 7'],
    ['status' => 'completed', 'label' => 'Termine', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
];

$currentStatus = $transaction->status;
$currentIndex = collect($steps)->search(fn($s) => $s['status'] === $currentStatus->value);
if ($currentIndex === false) $currentIndex = -1;
@endphp

<div class="w-full">
    <div class="flex items-center justify-between relative">
        <div class="absolute top-5 left-0 right-0 h-0.5 bg-slate-200 -z-10"></div>
        <div class="absolute top-5 left-0 h-0.5 bg-emerald-500 -z-10 transition-all duration-500" 
             style="width: {{ $currentIndex >= 0 ? ($currentIndex / (count($steps) - 1)) * 100 : 0 }}%"></div>

        @foreach($steps as $index => $step)
            @php
            $isCompleted = $index <= $currentIndex;
            $isCurrent = $index === $currentIndex;
            @endphp

            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-300 {{ $isCompleted ? 'bg-emerald-500 border-emerald-500' : ($isCurrent ? 'bg-white border-emerald-500' : 'bg-white border-slate-300') }}">
                    <svg class="w-5 h-5 {{ $isCompleted ? 'text-white' : ($isCurrent ? 'text-emerald-500' : 'text-slate-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                    </svg>
                </div>
                <span class="mt-2 text-xs font-medium {{ $isCompleted || $isCurrent ? 'text-emerald-600' : 'text-slate-400' }}">{{ $step['label'] }}</span>
            </div>
        @endforeach
    </div>
</div>