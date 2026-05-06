{{-- @extends('layouts.app') --}}

{{-- @section('title', 'PlatePal – Tagum City\'s Home Kitchen Marketplace') --}}

{{-- @section('content') --}}

── NAVBAR ──────────────────────────────────────────────────
{{-- <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-brand-cream-dark"> --}}
    {{-- <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between h-16">

        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <svg class="size-8 fill-brand-orange" viewBox="0 0 32 32">
                <path d="M16 2a12 12 0 100 24A12 12 0 0016 2zm0 4a3 3 0 110 6 3 3 0 010-6zm-4 9h8l-1.2 7H13.2L12 15z"/>
            </svg>
            <span class="text-xl font-black tracking-tight">
                <span class="text-brand-dark">PLATE</span><span class="text-brand-orange">PAL</span>
            </span>
        </a> --}}
{{--
<div class="hidden md:flex items-center gap-8">
    <a href="#" class="nav-underline text-sm font-medium text-brand-muted hover:text-brand-dark transition-colors">Browse caterers</a>
    <a href="#" class="nav-underline text-sm font-medium text-brand-muted hover:text-brand-dark transition-colors">How it works</a>
    <a href="#" class="nav-underline text-sm font-medium text-brand-muted hover:text-brand-dark transition-colors">For caterers</a> --}}
    {{-- @auth
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="flex flex-col items-end">
                <span class="text-xs font-bold text-brand-dark">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-brand-orange font-medium">Go to Dashboard</span>
            </div>
            <div class="w-9 h-9 rounded-full bg-brand-orange text-white flex items-center justify-center text-xs font-bold shadow-sm group-hover:bg-brand-orange-light transition-colors">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </a>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="px-5 py-2 rounded-full bg-brand-orange text-white text-sm font-bold hover:bg-brand-orange-light transition-colors shadow-sm">
            Sign In
        </a>
    @endguest --}}

{{--
</div> --}}

        {{-- Mobile toggle --}}
        {{-- <button
            class="md:hidden p-2 rounded-lg hover:bg-brand-cream transition-colors"
            onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
        >
            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

    </div> --}}

    {{-- Mobile menu --}}
{{-- <div id="mobile-menu" class="hidden md:hidden border-t border-brand-cream-dark px-6 py-4 flex flex-col gap-4 bg-white">
    <a href="#" class="text-sm font-medium text-brand-muted">Browse caterers</a>
    <a href="#" class="text-sm font-medium text-brand-muted">How it works</a>
    <a href="#" class="text-sm font-medium text-brand-muted">For caterers</a>

    @guest
        <a href="{{ route('login') }}" class="w-fit px-5 py-2 rounded-full bg-brand-orange text-white text-sm font-bold">Sign In</a>
    @endguest

    @auth
        <div class="pt-2 border-t border-brand-cream-dark">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-orange text-white flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="text-sm font-bold text-brand-dark">My Dashboard</span>
            </a>
        </div>
    @endauth
</div> --}}
{{-- </nav> --}}


{{-- ── HERO ────────────────────────────────────────────────────── --}}
<section class="hero-bg relative overflow-hidden min-h-[600px] flex items-center">

    {{-- Decorative images --}}
    <div class="absolute left-0 bottom-0 w-56 lg:w-72 pointer-events-none select-none">
        <img
            src="https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&q=80"
            alt=""
            class="w-full h-80 object-cover rounded-tr-3xl"
        >
    </div>
    <div class="absolute right-0 top-0 w-48 lg:w-64 pointer-events-none select-none">
        <img
            src="https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400&q=80"
            alt=""
            class="w-full h-72 object-cover rounded-bl-3xl"
        >
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20 text-center w-full">

        {{-- Pill --}}
        <div class="animate-fade-in inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-brand-orange/40 bg-white/60 backdrop-blur-sm shadow-lg mb-6">
            <span class="text-[0.7rem] font-bold tracking-[.12em] text-brand-orange uppercase">
                Tagum City's Home Kitchen Marketplace
            </span>
        </div>

        {{-- Headline --}}
        <h1 class="animate-fade-up font-display text-4xl md:text-5xl lg:text-6xl font-black leading-tight mb-4"
            style="animation-delay: .05s">
            Find <em class="text-brand-orange not-italic">authentic</em> home<br>
            cooking for your next event
        </h1>

        <p class="animate-fade-up text-brand-muted text-base md:text-lg max-w-md mx-auto mb-10"
           style="animation-delay: .12s">
            Connect with talented home-based caterers right in your barangay — real food, real people, no middlemen.
        </p>

        {{-- Search Box --}}
        <div class="animate-fade-up shadow-lg max-w-xl mx-auto bg-white rounded-2xl p-4 md:p-5"
             style="animation-delay: .18s">

            <div class="flex flex-col sm:flex-row gap-3 mb-3">
                <input
                    type="text"
                    placeholder="Search native chicken, lechon, kare-kare..."
                    class="flex-1 text-sm px-4 py-3 rounded-xl border border-brand-cream-dark bg-brand-cream/40 placeholder:text-brand-muted/60 text-brand-dark focus:outline-none focus:ring-2 focus:ring-brand-orange/30"
                >
                <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-green-50 border border-green-200 whitespace-nowrap">
                    <span class="size-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-sm font-bold text-green-700">Tagum City</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <select class="w-full appearance-none text-sm px-4 py-3 pr-10 rounded-xl border border-brand-cream-dark bg-white text-brand-muted focus:outline-none focus:ring-2 focus:ring-brand-orange/30 cursor-pointer">
                        <option>All Barangays</option>
                        <option>Magugpo Poblacion</option>
                        <option>Apokon</option>
                        <option>Visayan Village</option>
                        <option>Mankilam</option>
                        <option>New Balamban</option>
                        <option>Pagsabangan</option>
                    </select>
                    <svg class="size-4 absolute right-3 top-1/2 -translate-y-1/2 text-brand-muted pointer-events-none"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <button class="px-7 py-3 rounded-xl bg-brand-orange text-white text-sm font-bold whitespace-nowrap hover:bg-brand-orange-light active:scale-95 transition-all shadow-md shadow-brand-orange/30">
                    Find Caterers
                </button>
            </div>
        </div>

        {{-- Stats --}}
        <div class="animate-fade-up flex justify-center mt-10" style="animation-delay: .25s">
            @foreach([['48+', 'Home caterers'], ['12', 'Barangays Covered'], ['320+', 'Events Served']] as $i => $stat)
                <div class="{{ $i > 0 ? 'stat-sep' : '' }} px-8 md:px-12 text-center">
                    <p class="font-display text-3xl md:text-4xl font-black text-brand-dark">{{ $stat[0] }}</p>
                    <p class="text-xs md:text-sm text-brand-muted font-medium mt-0.5">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ── FEATURED CATERERS ───────────────────────────────────────── --}}
<section class="bg-brand-cream py-16 px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
            <h2 class="font-display text-3xl md:text-4xl font-bold text-brand-dark">Featured Local Caterers</h2>
            <a href="#" class="group flex items-center gap-1 text-sm font-bold text-brand-orange hover:text-brand-orange-light transition-colors">
                View All
                <svg class="size-4 transition-transform group-hover:translate-x-1"
                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @php
        $caterers = [
            ['name' => "Lola Maria's Kitchen",   'location' => 'Magugpo Poblacion', 'cuisine' => 'Authentic Tagum Native Chicken',    'rating' => '4.8', 'reviews' => 127, 'price' => '₱300-500/head', 'img' => 'https://images.unsplash.com/photo-1604908177522-ee0b8c0f2a6e?w=600&q=80'],
            ['name' => 'Kusina ni Aling Nena',   'location' => 'Apokon',            'cuisine' => 'Mindanao Fusion Cuisine',           'rating' => '4.9', 'reviews' => 98,  'price' => '₱400-600/head', 'img' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&q=80'],
            ['name' => 'TasteBuds Catering',     'location' => 'Visayan Village',   'cuisine' => 'Party Packages & Event Buffets',   'rating' => '4.7', 'reviews' => 156, 'price' => '₱350-550/head', 'img' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600&q=80'],
            ['name' => 'Bahay Kubo Kitchen',     'location' => 'Mankilam',          'cuisine' => 'Organic Farm-to-Table Dishes',     'rating' => '4.6', 'reviews' => 73,  'price' => '₱380-520/head', 'img' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80'],
            ['name' => 'Sarap Pinoy Express',    'location' => 'New Balamban',      'cuisine' => 'Quick Service Party Trays',        'rating' => '4.5', 'reviews' => 89,  'price' => '₱250-450/head', 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&q=80'],
            ['name' => 'DeliciaHaus Catering',   'location' => 'Pagsabangan',       'cuisine' => 'Premium Seafood Feasts',           'rating' => '4.9', 'reviews' => 112, 'price' => '₱500-800/head', 'img' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=600&q=80'],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($caterers as $index => $caterer)
            <article
                class="card-lift animate-fade-up bg-white rounded-2xl overflow-hidden cursor-pointer shadow-lg"
                style="animation-delay: {{ 0.05 + $index * 0.07 }}s"
            >
                {{-- Image --}}
                <div class="relative h-48 overflow-hidden">
                    <img
                        src="{{ $caterer['img'] }}"
                        alt="{{ $caterer['name'] }}"
                        loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                    >
                    <div class="img-overlay absolute inset-0"></div>
                    <span class="price-badge absolute top-3 right-3 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                        {{ $caterer['price'] }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h3 class="font-display font-bold text-lg leading-tight text-brand-dark">
                            {{ $caterer['name'] }}
                        </h3>
                        <div class="flex items-center gap-1 shrink-0">
                            <svg class="size-4 fill-amber-400" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-bold text-brand-dark">{{ $caterer['rating'] }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 text-xs text-brand-muted mb-2">
                        <svg class="size-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $caterer['location'] }}
                    </div>

                    <p class="text-sm text-brand-muted mb-3">{{ $caterer['cuisine'] }}</p>

                    <p class="text-xs text-brand-muted/70">{{ number_format($caterer['reviews']) }} reviews</p>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>


{{-- ── FOOTER ──────────────────────────────────────────────────── --}}
<footer class="bg-brand-dark text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">

            <div>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="size-7 fill-brand-orange" viewBox="0 0 32 32">
                        <path d="M16 2a12 12 0 100 24A12 12 0 0016 2zm0 4a3 3 0 110 6 3 3 0 010-6zm-4 9h8l-1.2 7H13.2L12 15z"/>
                    </svg>
                    <span class="text-lg font-black tracking-wider">PLATEPAL</span>
                </div>
                <p class="text-sm text-white/45 leading-relaxed max-w-xs">
                    Connecting Tagum City's best home-based caterers with the community since 2026.
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/80 mb-4">For Clients</p>
                <ul class="flex flex-col gap-2.5">
                    @foreach(['Browse Caterers', 'How It Works', 'Client Reviews'] as $link)
                    <li><a href="#" class="text-sm text-white/45 hover:text-white transition-colors">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/80 mb-4">For Caterers</p>
                <ul class="flex flex-col gap-2.5">
                    @foreach(['Join as Caterer', 'Pricing', 'Success Stories'] as $link)
                    <li><a href="#" class="text-sm text-white/45 hover:text-white transition-colors">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

        </div>

        <div class="border-t border-white/10 pt-6 text-center text-xs text-white/25">
            © 2026 PlatePal Tagum City. All rights reserved.
        </div>

    </div>
</footer>

@endsection
