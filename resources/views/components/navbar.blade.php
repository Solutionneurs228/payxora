<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:shadow-emerald-500/40 transition-shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold font-['Space_Grotesk'] text-slate-900">
                    Pay<span class="text-emerald-600">Xora</span>
                </span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('how-it-works') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">Comment ca marche</a>
                <a href="{{ route('pricing') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">Tarifs</a>
                <a href="{{ route('about') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">A propos</a>

                @auth
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-emerald-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                @endif
                            </button>

                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden">
                                <div class="p-3 border-b border-slate-100">
                                    <span class="text-sm font-semibold text-slate-700">Notifications</span>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->limit(5)->get() as $notif)
                                        <a href="{{ $notif->link }}" class="block p-3 hover:bg-slate-50 transition-colors {{ $notif->is_read ? 'opacity-60' : '' }}">
                                            <p class="text-sm font-medium text-slate-800">{{ $notif->title }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $notif->message }}</p>
                                        </a>
                                    @empty
                                        <p class="p-4 text-sm text-slate-400 text-center">Aucune notification</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 p-1 pr-3 rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-sm font-bold">
                                    {{ auth()->user()->initials }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ auth()->user()->first_name }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden">
                                <div class="p-3 border-b border-slate-100">
                                    <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600">Tableau de bord</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600">Mon profil</a>
                                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600">Mes transactions</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600">Administration</a>
                                @endif
                                <div class="border-t border-slate-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Deconnexion</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">Connexion</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/40">
                            Commencer
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-slate-100 shadow-lg">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('how-it-works') }}" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50">Comment ca marche</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50">Tarifs</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50">A propos</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50">Tableau de bord</a>
                <a href="{{ route('transactions.index') }}" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50">Transactions</a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-red-600 hover:bg-red-50">Deconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50">Connexion</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg bg-emerald-600 text-white text-center font-medium">Commencer</a>
            @endauth
        </div>
    </div>
</nav>

<div class="h-16 lg:h-20"></div>
