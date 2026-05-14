<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'PlatePal – Tagum City\'s Home Kitchen Marketplace' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-brand-cream-light font-body antialiased">
    <div class="flex flex-col min-h-screen">
        {{-- NAVBAR --}}
        <nav class="sticky top-0 z-40 bg-white border-b border-white shadow-sm">
            <div class="px-6 lg:px-8 py-4 flex items-center justify-between">
                {{-- Logo --}}
                <a href="{{ auth()->user()->role === 'caterer' ? route('caterer.dashboard') : (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('client.dashboard')) }}" class="flex items-center gap-2">
                    <img src="/assets/PlatePal_logo.jpg" alt="PlatePal" class="size-8 rounded-lg object-cover">
                    <div class="flex flex-col">
                        <span class="text-lg font-display tracking-tight text-brand-dark leading-none">PLATE<span class="text-[#f44e08] ">PAL</span></span>
                    </div>
                </a>

                {{-- Title --}}
                @isset($heading)
                <div class="hidden md:block text-lg font-bold text-brand-dark">{{ $heading }}</div>
                @endisset

                {{-- Right side --}}
                <div>
                    @isset($username)
                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="flex items-center gap-2.5 px-3 py-1.5 rounded-full border border-[#EDE4D8] bg-white hover:bg-[#FDF6EE] transition-colors">
                            <div class="w-[34px] h-[34px] rounded-full bg-[#E8642A] text-white text-xs font-bold flex items-center justify-center shrink-0">{{ $initials ?? 'U' }}</div>
                            <div class="hidden sm:flex flex-col items-start">
                                <span class="text-[12.5px] font-bold text-[#1C1A17] leading-tight">{{ $username }}</span>
                                @isset($usersub)<span class="text-[10.5px] text-[#8A7F72]">{{ $usersub }}</span>@endisset
                            </div>
                            <svg class="size-3.5 text-[#8A7F72] transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        @php
                            $role = auth()->user()->role;
                            $settingsRoute = match ($role) {
                                'caterer' => route('caterer.profile'),
                                'admin' => route('admin.dashboard'),
                                default => route('client.profile'),
                            };
                            $settingsLabel = $role === 'admin' ? 'Dashboard' : 'Profile Settings';
                            $feedbackRoute = route('feedback.create');
                        @endphp

                        {{-- Dropdown Menu --}}
                        <div x-show="open" @click.outside="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white border border-[#EDE4D8] rounded-2xl shadow-lg py-1.5 z-50">
                            @if(auth()->user()->role === 'client')
                                <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">
                                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/></svg>
                                    Welcome Page
                                </a>
                            @endif
                            <a href="{{ $settingsRoute }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">
                                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $settingsLabel }}
                            </a>
                            <a href="{{ $feedbackRoute }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">
                                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Feedback
                            </a>
                            <div class="border-t border-[#EDE4D8] my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="size-4 stroke-red-400" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                    @endisset
                </div>
            </div>
        </nav>

        {{-- MAIN LAYOUT --}}
        <div class="flex flex-1" x-data="{ sidebarOpen: false }">
            {{-- SIDEBAR --}}
            @isset($sidebar)
            <aside class="w-48 bg-[#FFF4ED] border-r border-[#e7dfdf] fixed inset-y-0 top-[73px] left-0 z-30 flex flex-col pt-6 px-4 lg:static lg:inset-auto lg:top-auto lg:z-auto" :class="sidebarOpen ? 'flex' : 'hidden lg:flex'">
                <nav class="space-y-2 flex-1">
                    {{ $sidebar }}
                </nav>
                @isset($sidebarFooter)
                {{ $sidebarFooter }}
                @endisset
            </aside>
            @endisset

            {{-- MAIN CONTENT --}}
            <main class="bg-[#FAFAFA] flex-1 p-6 lg:p-8">
                {{-- Mobile Sidebar Toggle --}}
                @isset($sidebar)
                <button type="button" @click="sidebarOpen = !sidebarOpen" class="lg:hidden mb-4 flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-[#EDE4D8] text-[#1C1A17] font-medium text-sm hover:bg-[#FDF6EE] transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    Menu
                </button>
                @endisset
                {{ $slot }}
            </main>

            {{-- Mobile Sidebar Overlay --}}
            @isset($sidebar)
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 lg:hidden" x-transition></div>
            @endisset
        </div>
    </div>
    {{ $scripts ?? '' }}
</body>
</html>
