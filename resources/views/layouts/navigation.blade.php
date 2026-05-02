@php
$isAdmin = Auth::check() && Auth::user()->role === 'admin';
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Desktop Nav -->
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">PayXora</span>
                    </a>
                </div>

                <!-- Desktop Links -->
                <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium transition-colors">
                        Accueil
                    </a>
                    <a href="{{ route('how-it-works') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('how-it-works') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium transition-colors">
                        Comment ca marche
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('pricing') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium transition-colors">
                        Tarifs
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium transition-colors">
                            Tableau de bord
                        </a>
                        <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('transactions.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium transition-colors">
                            Transactions
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Notifications -->
                    <div class="relative ml-3" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" class="relative p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Notifications</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                            @endif
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="ml-3 relative" x-data="{ userOpen: false }">
                        <div>
                            <button @click="userOpen = !userOpen" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition-colors">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            </button>
                        </div>

                        <div x-show="userOpen" @click.away="userOpen = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm text-gray-900 font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon Profil</a>
                            <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mes Transactions</a>
                            <a href="{{ route('disputes.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mes Litiges</a>
                            @if($isAdmin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-indigo-700 hover:bg-indigo-50 font-medium">Administration</a>
                            @endif
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Deconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors">Connexion</a>
                    <a href="{{ route('register') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                        Inscription
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="h-6 w-6" :class="{'block': open, 'hidden': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden" x-show="open" @click.away="open = false">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('home') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} text-base font-medium transition-colors">Accueil</a>
            <a href="{{ route('how-it-works') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('how-it-works') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} text-base font-medium transition-colors">Comment ca marche</a>
            <a href="{{ route('pricing') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('pricing') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} text-base font-medium transition-colors">Tarifs</a>

            @auth
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} text-base font-medium transition-colors">Tableau de bord</a>
                <a href="{{ route('transactions.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('transactions.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} text-base font-medium transition-colors">Transactions</a>
                <a href="{{ route('disputes.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('disputes.*') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} text-base font-medium transition-colors">Litiges</a>
                <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 text-base font-medium transition-colors">Mon Profil</a>
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 text-base font-medium transition-colors">Administration</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-red-600 hover:bg-red-50 hover:border-red-300 text-base font-medium transition-colors">Deconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 text-base font-medium transition-colors">Connexion</a>
                <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 text-base font-medium transition-colors">Inscription</a>
            @endauth
        </div>
    </div>
</nav>
