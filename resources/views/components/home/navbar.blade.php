<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-brand-cream-dark shadow-sm">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between h-16">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <img src="/assets/PlatePal_logo.jpg" alt="PlatePal" class="size-8 rounded-lg object-cover">
            <span class="text-xl font-display font-black tracking-tight">
                <span class="text-brand-dark text-display">PLATE</span><span class="text-brand-orange">PAL</span>
            </span>
        </a>

        {{-- Desktop links --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('browse.caterers') }}" class="relative text-sm font-medium {{ request()->routeIs('browse.caterers') ? 'text-brand-dark' : 'text-brand-muted hover:text-brand-dark' }} transition-colors after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 {{ request()->routeIs('browse.caterers') ? 'after:w-full' : 'after:w-0 hover:after:w-full' }} after:bg-brand-orange after:transition-all after:duration-500">Browse caterers</a>
            <a href="{{ route('how.it.works') }}" class="relative text-sm font-medium {{ request()->routeIs('how.it.works') ? 'text-brand-dark' : 'text-brand-muted hover:text-brand-dark' }} transition-colors after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 {{ request()->routeIs('how.it.works') ? 'after:w-full' : 'after:w-0 hover:after:w-full' }} after:bg-brand-orange after:transition-all after:duration-500">How it works</a>
            <a href="{{ route('for.caterers') }}" class="relative text-sm font-medium {{ request()->routeIs('for.caterers') ? 'text-brand-dark' : 'text-brand-muted hover:text-brand-dark' }} transition-colors after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 {{ request()->routeIs('for.caterers') ? 'after:w-full' : 'after:w-0 hover:after:w-full' }} after:bg-brand-orange after:transition-all after:duration-500">For caterers</a>
            @auth
                @php $initials = strtoupper(substr(auth()->user()->name, 0, 1) . (str_contains(auth()->user()->name, ' ') ? substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) : '')); @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-brand-cream-dark hover:bg-brand-cream transition-colors">
                        <div class="w-7 h-7 rounded-full bg-brand-orange text-white text-xs font-bold flex items-center justify-center">{{ $initials }}</div>
                        <span class="text-sm font-bold text-brand-dark">{{ auth()->user()->name }}</span>
                        <svg class="size-3.5 text-brand-muted transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white border border-brand-cream-dark rounded-2xl shadow-lg py-1.5 z-50">
                        <a href="{{ auth()->user()->role === 'caterer' ? route('caterer.dashboard') : route('client.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-brand-dark hover:bg-brand-cream transition-colors">
                            <svg class="size-4 stroke-brand-muted" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <a href="#" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-brand-dark hover:bg-brand-cream transition-colors">
                            <svg class="size-4 stroke-brand-muted" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <a href="#" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-brand-dark hover:bg-brand-cream transition-colors">
                            <svg class="size-4 stroke-brand-muted" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </a>
                        <div class="border-t border-brand-cream-dark my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                <svg class="size-4 stroke-red-400" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-l-lg rounded-r-lg bg-brand-orange text-white text-sm font-bold hover:bg-brand-orange-light transition-colors shadow-sm">
                    Sign In
                </a>
            @endauth
        </div>

        {{-- Mobile toggle --}}
        <button
            class="md:hidden p-2 rounded-lg hover:bg-brand-cream transition-colors"
            onclick="const m=document.getElementById('mobile-menu');m.classList.toggle('hidden');m.classList.toggle('flex');"
            aria-label="Toggle menu"
        >
            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden border-t border-brand-cream-dark px-6 py-4 flex-col gap-4 bg-white">
        <a href="{{ route('browse.caterers') }}" class="text-sm font-medium text-brand-muted">Browse caterers</a>
        <a href="{{ route('how.it.works') }}" class="text-sm font-medium text-brand-muted">How it works</a>
        <a href="{{ route('for.caterers') }}" class="text-sm font-medium text-brand-muted">For caterers</a>
        @auth
            <a href="{{ auth()->user()->role === 'caterer' ? route('caterer.dashboard') : route('client.dashboard') }}" class="w-fit px-5 py-2 rounded-full bg-brand-orange text-white text-sm font-bold">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="w-fit px-5 py-2 rounded-full bg-brand-orange text-white text-sm font-bold">Sign In</a>
        @endauth
    </div>
</nav>
