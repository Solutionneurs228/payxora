@extends('layouts.app')

@section('title', 'Notifications — PayXora')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Notifications</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
        @forelse($notifications as $notification)
        <div class="p-4 flex items-start gap-4 hover:bg-gray-50 transition {{ $notification->read_at ? 'opacity-60' : '' }}">
            <div class="w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-gray-100' : 'bg-indigo-100' }} flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 {{ $notification->read_at ? 'text-gray-400' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-medium text-gray-900">{{ $notification->title }}</p>
                <p class="text-sm text-gray-600 mt-0.5">{{ $notification->message }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notification->read_at)
            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                @csrf
                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800">Marquer comme lue</button>
            </form>
            @endif
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <p>Aucune notification</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
