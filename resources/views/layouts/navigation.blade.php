<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Desktop Nav -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="10" fill="url(#payxora-gradient)"/>
                        <path d="M12 20C12 15.5817 15.5817 12 20 12C24.4183 12 28 15.5817 28 20" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M28 20C28 24.4183 24.4183 28 20 28C15.5817 28 12 24.4183 12 20" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="2 4"/>
                        <circle cx="20" cy="20" r="3" fill="white"/>
                        <defs>
                            <linearGradient id="payxora-gradient" x1="0" y1="0" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#4F46E5"/>
                                <stop offset="1" stop-color="#7C3AED"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <span class="font-bold text-xl text-indigo-700">PayXora</span>
                </a>

                @auth
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-6">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">
                            Tableau de bord
                        </a>
                        <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">
                            Transactions
                        </a>
                        <a href="{{ route('disputes.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">
                            Litiges
                        </a>

                        {{-- LIEN ADMIN VISIBLE UNIQUEMENT POUR LES ADMINS --}}
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-purple-600 hover:text-purple-800 hover:border-purple-300 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                Admin
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-4">
                @auth
                    <!-- Notifications -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-500 hover:text-indigo-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                {{ auth()->user()->initials }}
                            </div>
                            <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="userOpen" @click.away="userOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">Mon profil</a>
                            <a href="{{ route('kyc.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50">Verification KYC</a>

                            {{-- LIEN ADMIN DANS LE DROPDOWN --}}
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-purple-700 hover:bg-purple-50 font-medium">
                                    Panel Admin
                                </a>
                            @endif

                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Deconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Connexion</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-700">Inscription</a>
                @endauth

                <!-- Mobile menu button -->
                <button @click="open = !open" class="sm:hidden p-2 text-gray-500 hover:text-indigo-600">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" class="sm:hidden bg-white border-t border-gray-100" style="display: none;">
        <div class="px-4 py-3 space-y-1">
            @auth
                <a href="{{ route('dashboard') }}" class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition">
                    Tableau de bord
                </a>
                <a href="{{ route('transactions.index') }}" class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition">
                    Transactions
                </a>
                <a href="{{ route('disputes.index') }}" class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition">
                    Litiges
                </a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block w-full pl-3 pr-4 py-2 border-l-4 border-purple-400 text-left text-base font-medium text-purple-700 hover:text-purple-900 hover:bg-purple-50 transition">
                        Panel Admin
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition">
                    Mon profil
                </a>
            @endauth
        </div>
    </div>
</nav>
