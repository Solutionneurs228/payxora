<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-emerald-500" viewBox="0 0 32 32" fill="none">
                        <rect x="2" y="8" width="28" height="16" rx="4" stroke="currentColor" stroke-width="2.5" fill="none"/>
                        <path d="M8 16h16M16 12v8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="24" cy="8" r="3" fill="#10B981"/>
                    </svg>
                    <span class="text-xl font-bold text-slate-900">Pay<span class="text-emerald-500">Xora</span></span>
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-emerald-600 font-medium transition">Accueil</a>
                <a href="{{ route('how-it-works') }}" class="text-slate-600 hover:text-emerald-600 font-medium transition">Comment ca marche</a>
                <a href="{{ route('pricing') }}" class="text-slate-600 hover:text-emerald-600 font-medium transition">Tarifs</a>
                <a href="{{ route('contact') }}" class="text-slate-600 hover:text-emerald-600 font-medium transition">Contact</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-emerald-600 font-medium transition">Tableau de bord</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-600 hover:text-red-600 font-medium transition">Deconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-emerald-600 font-medium transition">Connexion</a>
                    <a href="{{ route('register') }}" class="gradient-accent text-white px-5 py-2.5 rounded-lg font-semibold hover:opacity-90 transition shadow-lg shadow-emerald-500/25">Commencer</a>
                @endauth
            </div>
            <div class="flex items-center md:hidden">
                <button @click="open = !open" class="text-slate-600 hover:text-slate-900 p-2">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>
    <div x-show="open" class="md:hidden bg-white border-t border-slate-200" style="display: none;">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-slate-600 hover:text-emerald-600 font-medium">Accueil</a>
            <a href="{{ route('how-it-works') }}" class="block px-3 py-2 text-slate-600 hover:text-emerald-600 font-medium">Comment ca marche</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 text-slate-600 hover:text-emerald-600 font-medium">Tarifs</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 text-slate-600 hover:text-emerald-600 font-medium">Contact</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-slate-600 hover:text-emerald-600 font-medium">Tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-slate-600 hover:text-emerald-600 font-medium">Connexion</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 text-emerald-600 font-semibold">Inscription</a>
            @endauth
        </div>
    </div>
</nav>