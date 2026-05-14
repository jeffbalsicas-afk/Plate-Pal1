@php
    $initials = strtoupper(substr($user?->name ?? 'U', 0, 1) . (str_contains($user?->name ?? 'U', ' ') ? substr($user?->name ?? 'U', strpos($user?->name ?? 'U', ' ') + 1, 1) : ''));
    $toArray = function ($value) {
        for ($i = 0; $i < 2; $i++) {
            if (is_array($value)) {
                return array_values(array_filter($value, fn ($item) => $item !== null && $item !== ''));
            }

            if (! is_string($value) || $value === '') {
                return [];
            }

            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }

            $value = $decoded;
        }

        return is_array($value) ? array_values($value) : [];
    };
    $servicesOffered = $toArray($caterer->services_offered ?? []);
    $galleryImages = $toArray($caterer->gallery_images ?? []);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $caterer->business_name }} – PlatePal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body
    class="bg-white font-body antialiased"
    x-data="{
        showRemoveSavedModal: false,
        removeTarget: { formId: '', name: '', action: 'remove' },
        openSavedCatererModal(formId, name, action = 'remove') {
            this.removeTarget = { formId, name, action };
            this.showRemoveSavedModal = true;
        },
        closeRemoveSavedModal() {
            this.showRemoveSavedModal = false;
            this.removeTarget = { formId: '', name: '', action: 'remove' };
        }
    }"
>
    <div class="flex flex-col min-h-screen">
        {{-- NAVBAR --}}
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
                    <a href="{{ route('browse.caterers') }}" class="relative text-sm font-medium text-brand-dark transition-colors after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 after:w-full after:bg-brand-orange after:transition-all after:duration-500">Browse caterers</a>
                    <a href="{{ route('how.it.works') }}" class="relative text-sm font-medium text-brand-muted hover:text-brand-dark transition-colors after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 after:w-0 hover:after:w-full after:bg-brand-orange after:transition-all after:duration-500">How it works</a>
                    <a href="{{ route('for.caterers') }}" class="relative text-sm font-medium text-brand-muted hover:text-brand-dark transition-colors after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 after:w-0 hover:after:w-full after:bg-brand-orange after:transition-all after:duration-500">For caterers</a>
                    @if($user)
                        @php
                            $role = $user->role;
                            $dashboardRoute = match ($role) {
                                'caterer' => route('caterer.dashboard'),
                                'admin' => route('admin.dashboard'),
                                default => route('client.dashboard'),
                            };
                            $profileRoute = match ($role) {
                                'caterer' => route('caterer.profile'),
                                'client' => route('client.profile'),
                                default => null,
                            };
                        @endphp
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-brand-cream-dark hover:bg-brand-cream transition-colors">
                                <div class="w-7 h-7 rounded-full bg-brand-orange text-white text-xs font-bold flex items-center justify-center">{{ $initials }}</div>
                                <span class="text-sm font-bold text-brand-dark">{{ $user->name }}</span>
                                <svg class="size-3.5 text-brand-muted transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white border border-brand-cream-dark rounded-2xl shadow-lg py-1.5 z-50">
                                <a href="{{ $dashboardRoute }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-brand-dark hover:bg-brand-cream transition-colors">
                                    <svg class="size-4 stroke-brand-muted" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </a>
                                @if($profileRoute)
                                    <a href="{{ $profileRoute }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-brand-dark hover:bg-brand-cream transition-colors">
                                        <svg class="size-4 stroke-brand-muted" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profile Settings
                                    </a>
                                @endif
                                <a href="{{ route('feedback.create') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-brand-dark hover:bg-brand-cream transition-colors">
                                    <svg class="size-4 stroke-brand-muted" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    Website Feedback
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
                    @endif
                </div>

                {{-- Mobile toggle --}}
                <button
                    type="button"
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
                <a href="{{ route('browse.caterers') }}" class="text-sm font-medium text-brand-dark font-bold">Browse caterers</a>
                <a href="{{ route('how.it.works') }}" class="text-sm font-medium text-brand-muted">How it works</a>
                <a href="{{ route('for.caterers') }}" class="text-sm font-medium text-brand-muted">For caterers</a>
                @if($user)
                    @php
                        $dashboardRoute = match ($user->role) {
                            'caterer' => route('caterer.dashboard'),
                            'admin' => route('admin.dashboard'),
                            default => route('client.dashboard'),
                        };
                        $profileRoute = match ($user->role) {
                            'caterer' => route('caterer.profile'),
                            'client' => route('client.profile'),
                            default => null,
                        };
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="w-fit px-5 py-2 rounded-full bg-brand-orange text-white text-sm font-bold">Dashboard</a>
                    @if($profileRoute)
                        <a href="{{ $profileRoute }}" class="text-sm font-medium text-brand-muted">Profile Settings</a>
                    @endif
                    <a href="{{ route('feedback.create') }}" class="text-sm font-medium text-brand-muted">Website Feedback</a>
                @else
                    <a href="{{ route('login') }}" class="w-fit px-5 py-2 rounded-full bg-brand-orange text-white text-sm font-bold">Sign In</a>
                @endif
            </div>
        </nav>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 py-8">
            <div class="max-w-6xl mx-auto px-4">
                @if($caterer->approval_status === 'pending')
                    <div class="alert alert-warning">
                    ⏳ Your profile is pending admin approval
                    </div>
                @elseif($caterer->approval_status === 'rejected')
                    <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-red-50 border border-red-300 flex items-start gap-2 sm:gap-3">
                        <svg class="size-4 sm:size-5 stroke-red-500 shrink-0 mt-0.5" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-xs sm:text-sm font-bold text-red-600">Profile Rejected</p>
                            <p class="text-[0.7rem] sm:text-xs text-red-500">{{ $caterer->rejection_reason ?? 'Please contact support for details.' }}</p>
                        </div>
                    </div>
                @endif

                {{-- Back Link --}}
                <a href="{{ route('browse.caterers') }}" class="text-sm font-medium text-[#E8642A] hover:text-[#F07C42] transition-colors inline-flex items-center gap-1 mb-6">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Back to Browse
                </a>

                {{-- Header Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    {{-- Image --}}
                    <div class="rounded-2xl overflow-hidden h-80 lg:h-96 bg-[#FDF6EE] flex items-center justify-center">
                        @if($caterer->profile_image)
                            @if(str_starts_with($caterer->profile_image, 'http'))
                                <img src="{{ $caterer->profile_image }}" alt="{{ $caterer->business_name }}" class="w-full h-full object-cover">
                            @elseif(str_starts_with($caterer->profile_image, '/storage/'))
                                <img src="{{ $caterer->profile_image }}" alt="{{ $caterer->business_name }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset($caterer->profile_image) }}" alt="{{ $caterer->business_name }}" class="w-full h-full object-cover">
                            @endif
                        @else
                            <svg class="size-32 text-[#D3CCBE]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        @endif
                    </div>

                    {{-- Info Section --}}
                    <div>
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h1 class="text-3xl font-black text-[#1C1A17] mb-1">{{ $caterer->business_name }}</h1>
                                <p class="text-sm text-[#8A7F72] flex items-center gap-1">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $caterer->barangay }}, Tagum City
                                </p>
                            </div>
                            @if($user && $user->role === 'client')
                            @php
                                $isSaved = isset($savedCatererIds) && in_array($caterer->id, $savedCatererIds);
                                $toggleFormId = 'toggle-detail-caterer-' . $caterer->id;
                            @endphp
                            <form id="{{ $toggleFormId }}" method="POST" action="{{ route('client.saved-caterers.toggle', $caterer) }}">
                                @csrf
                                <button
                                    type="button"
                                    @click.prevent="openSavedCatererModal(@js($toggleFormId), @js($caterer->business_name ?? $caterer->name), @js($isSaved ? 'remove' : 'save'))"
                                    class="p-3 rounded-full border border-[#EDE4D8] transition-all hover:scale-110 {{ $isSaved ? 'text-[#BE3455] bg-[#FCECEF]' : 'text-gray-400 bg-white hover:text-[#BE3455] hover:bg-[#FCECEF]' }}"
                                    aria-label="{{ $isSaved ? 'Unsave' : 'Save' }} caterer"
                                >
                                    @if($isSaved)
                                        <svg class="size-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @else
                                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                            @elseif(!$user)
                            <a href="{{ route('login') }}" onclick="sessionStorage.setItem('return_to', window.location.href); return true;" class="p-3 rounded-full border border-[#EDE4D8] text-gray-400 bg-white hover:text-[#BE3455] hover:bg-[#FCECEF] transition-all hover:scale-110" aria-label="Login to save caterer">
                                <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </a>
                            @endif
                        </div>

                        {{-- Rating & Reviews --}}
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center gap-1">
                                <svg class="size-5 fill-[#F59E0B]" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-lg font-black text-[#1C1A17]">{{ number_format($averageRating ?? $caterer->rating ?? 0, 1) }}</span>
                                <span class="text-sm text-[#8A7F72]">({{ number_format($reviewsCount ?? $caterer->reviews_count ?? 0) }} reviews)</span>
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-[#EDE4D8]">
                            <div>
                                <p class="text-xs text-[#8A7F72] mb-1">Price Range</p>
                                <p class="text-lg font-bold text-[#E8642A]">₱{{ $caterer->price_min }}-{{ $caterer->price_max }}/head</p>
                            </div>
                            <div>
                                <p class="text-xs text-[#8A7F72] mb-1">Serving Capacity</p>
                                <p class="text-lg font-bold text-[#1C1A17]">{{ $caterer->min_guest }}-{{ $caterer->max_guest }} guests</p>
                            </div>
                        </div>

                        {{-- Contact Info --}}
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="size-5 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $caterer->phone }}</span>
                            </div>
                            @if($caterer->email)
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="size-5 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>{{ $caterer->email }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        @if($user && $user->role === 'client')
                            <div class="grid grid-cols-2 gap-3">
                                <a href="#booking-request" class="text-center px-6 py-3 rounded-xl bg-[#E8642A] text-white font-bold hover:bg-[#F07C42] transition-colors">
                                    Book Now
                                </a>
                                <a href="{{ route('messages.show', $caterer) }}" class="text-center px-6 py-3 rounded-xl border border-[#E8642A] text-[#E8642A] font-bold hover:bg-[#FDF6EE] transition-colors">
                                    Send Message
                                </a>
                            </div>
                        @elseif($caterer->id === auth()->id())
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('caterer.profile') }}" class="text-center px-6 py-3 rounded-xl bg-[#E8642A] text-white font-bold hover:bg-[#F07C42] transition-colors">
                                    Edit Profile
                                </a>
                                <a href="{{ route('caterer.dashboard') }}" class="text-center px-6 py-3 rounded-xl border border-[#E8642A] text-[#E8642A] font-bold hover:bg-[#FDF6EE] transition-colors">
                                    Dashboard
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3 px-6 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-[#8A7F72] text-sm">
                                Please <a href="{{ route('login') }}" onclick="sessionStorage.setItem('return_to', window.location.href); return true;" class="text-[#E8642A] font-bold hover:underline">login</a> to book or message this caterer
                            </div>
                        @endif
                    </div>
                </div>

                {{-- About + Gallery --}}
                <div class="mb-8 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="rounded-2xl border border-[#EDE4D8] bg-[#FDF6EE] p-6">
                        <h2 class="text-2xl font-black text-[#1C1A17] mb-4">About {{ $caterer->business_name }}</h2>

                        @if($caterer->our_story)
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-[#1C1A17] mb-2">Our Story</h3>
                                <p class="text-sm text-[#1C1A17] leading-relaxed whitespace-pre-line">{{ $caterer->our_story }}</p>
                            </div>
                        @else
                            <p class="text-sm text-[#1C1A17] leading-relaxed mb-6">{{ $caterer->description ?? 'No description is available yet. Please contact the caterer for more details about their service, menu, and event offerings.' }}</p>
                        @endif

                        @if($caterer->what_makes_special)
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-[#1C1A17] mb-3">What Makes Us Special</h3>
                                <ul class="space-y-2">
                                    @foreach(explode("\n", $caterer->what_makes_special) as $point)
                                        @if(trim($point))
                                            <li class="flex items-start gap-2 text-sm text-[#1C1A17]">
                                                <svg class="size-5 text-[#E8642A] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>{{ trim($point) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(!empty($servicesOffered))
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-[#1C1A17] mb-3">Services Offered</h3>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($servicesOffered as $service)
                                        <div class="bg-white rounded-lg px-3 py-2 text-sm text-[#1C1A17] border border-[#EDE4D8]">
                                            {{ $service }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 text-sm text-[#1C1A17]">
                            <div class="rounded-2xl bg-white p-4 border border-[#EDE4D8]">
                                <p class="text-xs text-[#8A7F72] uppercase tracking-[0.2em] mb-2">Cuisine</p>
                                <p class="font-bold">{{ $caterer->cuisine ?? 'Filipino' }}</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 border border-[#EDE4D8]">
                                <p class="text-xs text-[#8A7F72] uppercase tracking-[0.2em] mb-2">Service Area</p>
                                <p class="font-bold">{{ $caterer->barangay ?? 'Tagum City' }}</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 border border-[#EDE4D8]">
                                <p class="text-xs text-[#8A7F72] uppercase tracking-[0.2em] mb-2">Guest Capacity</p>
                                <p class="font-bold">{{ $caterer->min_guest ?? 1 }}–{{ $caterer->max_guest ?? 100 }} guests</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 border border-[#EDE4D8]">
                                <p class="text-xs text-[#8A7F72] uppercase tracking-[0.2em] mb-2">Price Range</p>
                                <p class="font-bold">₱{{ $caterer->price_min ?? 'TBD' }}–{{ $caterer->price_max ?? 'TBD' }}/head</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-[#EDE4D8] bg-white p-6">
                        <h2 class="text-2xl font-black text-[#1C1A17] mb-4">Gallery</h2>
                        @php
                            if (empty($galleryImages)) {
                                $galleryImages = [
                                    'assets/Buds.png',
                                    'assets/Guisado.png',
                                    'assets/Kubo.png',
                                    'assets/Nena.png',
                                ];
                            }
                        @endphp
                        @if(!empty($galleryImages))
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($galleryImages as $galleryImage)
                                    <div class="overflow-hidden rounded-3xl h-40 bg-[#FDF6EE]">
                                        @if(str_starts_with($galleryImage, 'http'))
                                            <img src="{{ $galleryImage }}" alt="Gallery image" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                        @elseif(str_starts_with($galleryImage, '/storage/'))
                                            <img src="{{ $galleryImage }}" alt="Gallery image" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                        @elseif(str_starts_with($galleryImage, 'assets/'))
                                            <img src="{{ asset($galleryImage) }}" alt="Gallery image" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                        @else
                                            <img src="{{ asset($galleryImage) }}" alt="Gallery image" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-40 text-center">
                                <svg class="size-12 text-[#D3CCBE] mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                <p class="text-sm text-[#8A7F72]">No gallery images yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if(($publicReviews ?? collect())->isNotEmpty())
                    <div class="mb-8">
                        <div class="flex items-end justify-between gap-4 mb-5">
                            <div>
                                <h2 class="text-2xl font-black text-[#1C1A17]">Client Reviews</h2>
                                <p class="text-sm text-[#8A7F72]">Featured public feedback from completed events.</p>
                            </div>
                            <div class="text-sm font-black text-[#E8642A]">{{ number_format($averageRating, 1) }} / 5.0</div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($publicReviews as $review)
                                @php
                                    $reviewer = $review->reviewer_name ?? $review->client?->name ?? 'Client';
                                    $reviewedAt = $review->reviewed_at ?? $review->created_at;
                                @endphp
                                <article class="bg-white rounded-2xl p-5 border border-[#EDE4D8] shadow-sm">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div>
                                            <div class="text-[#E8642A] text-lg leading-none">
                                                {!! str_repeat('&#9733;', (int) $review->rating) . str_repeat('&#9734;', max(0, 5 - (int) $review->rating)) !!}
                                            </div>
                                            <h3 class="text-base font-black text-[#1C1A17] mt-1">"{{ $review->title }}"</h3>
                                        </div>
                                        @if($review->is_featured)
                                            <span class="shrink-0 rounded-full bg-[#FFF2DF] px-2.5 py-1 text-[11px] font-bold text-[#A94A18]">Featured</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-[#1C1A17] leading-relaxed mb-3">{{ $review->body }}</p>
                                    <div class="text-xs text-[#8A7F72] font-bold">{{ $reviewer }} - {{ $reviewedAt->format('M d, Y') }}</div>
                                    @if($review->caterer_reply)
                                        <div class="mt-4 rounded-xl bg-[#FDF6EE] border border-[#F2DCCB] p-3">
                                            <div class="text-xs font-black uppercase text-[#8A6D3F] mb-1">{{ $caterer->business_name ?? $caterer->name }} replied</div>
                                            <p class="text-sm text-[#1C1A17]">{{ $review->caterer_reply }}</p>
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div id="booking-request" class="mb-8 bg-white rounded-2xl p-6 border border-[#EDE4D8] shadow-sm scroll-mt-24">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-2xl font-black text-[#1C1A17] mb-1">Request a Booking</h2>
                            <p class="text-sm text-[#8A7F72]">Send your event details to {{ $caterer->business_name ?? $caterer->name }}.</p>
                        </div>
                        <span class="text-xs font-bold text-[#8A7F72] bg-[#FDF6EE] border border-[#EDE4D8] rounded-full px-3 py-1">
                            Pending until confirmed
                        </span>
                    </div>

                    @if(!$user || $user->role !== 'client')
                        <div class="text-center py-12 px-6 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8]">
                            <svg class="size-16 text-[#D3CCBE] mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <p class="text-lg font-bold text-[#1C1A17] mb-2">Login Required</p>
                            <p class="text-sm text-[#8A7F72] mb-6">Please login as a client to send booking requests</p>
                            <a href="{{ route('login') }}" onclick="sessionStorage.setItem('return_to', window.location.href); return true;" class="inline-block px-6 py-3 rounded-xl bg-[#E8642A] text-white font-bold hover:bg-[#F07C42] transition-colors">
                                Login to Book
                            </a>
                        </div>
                    @else
                        @if($errors->any())
                            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('client.bookings.store', $caterer) }}" class="space-y-5">
                        @csrf
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label for="event_title" class="text-xs font-bold text-[#1C1A17] mb-1.5 block">Event name</label>
                                <input id="event_title" name="event_title" value="{{ old('event_title') }}" type="text" required placeholder="Birthday Party"
                                    class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]">
                            </div>
                            <div>
                                <label for="event_date" class="text-xs font-bold text-[#1C1A17] mb-1.5 block">Event date</label>
                                <input id="event_date" name="event_date" value="{{ old('event_date') }}" type="date" min="{{ now()->toDateString() }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                            </div>
                            <div>
                                <label for="guests" class="text-xs font-bold text-[#1C1A17] mb-1.5 block">Guests</label>
                                <input id="guests" name="guests" value="{{ old('guests', 50) }}" type="number" required min="1"
                                    class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                            </div>
                        </div>

                        @if($packages->isNotEmpty())
                        <div>
                            <label for="package_id" class="text-xs font-bold text-[#1C1A17] mb-1.5 block">Select Package (Optional)</label>
                            <select id="package_id" name="package_id" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                                <option value="">-- Choose a package or customize later --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" @selected(old('package_id') == $package->id)>
                                        {{ $package->name }} - ₱{{ number_format($package->price, 0) }} bundle
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if($menuItems->isNotEmpty())
                        <div>
                            <label class="text-xs font-bold text-[#1C1A17] mb-2 block">Select Menu Items (Optional)</label>
                            <div class="grid md:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8]">
                                @foreach($menuItems as $item)
                                <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-white cursor-pointer">
                                    <input type="checkbox" name="menu_items[]" value="{{ $item->id }}" class="rounded border-[#EDE4D8] text-[#E8642A] focus:ring-[#E8642A]">
                                    <span class="text-sm text-[#1C1A17] flex-1">{{ $item->name }}</span>
                                    <span class="text-xs font-bold text-[#E8642A]">₱{{ number_format($item->price, 0) }}/{{ $item->unit }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($addOns->isNotEmpty())
                        <div>
                            <label class="text-xs font-bold text-[#1C1A17] mb-2 block">Select Add-ons (Optional)</label>
                            <div class="grid md:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8]">
                                @foreach($addOns as $addon)
                                <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-white cursor-pointer">
                                    <input type="checkbox" name="addons[]" value="{{ $addon->id }}" class="rounded border-[#EDE4D8] text-[#E8642A] focus:ring-[#E8642A]">
                                    <span class="text-sm text-[#1C1A17] flex-1">{{ $addon->name }}</span>
                                    <span class="text-xs font-bold text-[#E8642A]">₱{{ number_format($addon->price, 0) }}/{{ $addon->unit }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div>
                            <label for="special_requests" class="text-xs font-bold text-[#1C1A17] mb-1.5 block">Event notes</label>
                            <textarea id="special_requests" name="special_requests" rows="4" placeholder="Menu preferences, allergies, setup needs, venue details..."
                                class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]">{{ old('special_requests') }}</textarea>
                        </div>

                        <button type="submit" class="w-full px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                            Send Booking Request
                        </button>
                    </form>
                    @endif
                </div>

                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
                        <div>
                            <h2 class="text-2xl font-black text-[#1C1A17]">Our Packages</h2>
                            <p class="text-sm text-[#8A7F72]">{{ $packages->count() }} package{{ $packages->count() === 1 ? '' : 's' }} available for booking.</p>
                        </div>
                    </div>

                    @if($packages->isEmpty())
                        <div class="bg-white rounded-2xl p-12 border border-[#EDE4D8] text-center">
                            <svg class="size-16 text-[#D3CCBE] mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m3-6v6m3-6v2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H2.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            <p class="text-[#8A7F72] font-medium mb-4">No packages available yet</p>
                            <a href="#booking-request" class="inline-block px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                                Send Custom Request
                            </a>
                        </div>
                    @else
                        <div class="space-y-4 mb-8">
                            @foreach($packages as $package)
                            <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8] hover:shadow-lg transition-shadow">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-[#1C1A17]">{{ $package->name }}</h3>
                                        @if($package->description)
                                        <p class="text-sm text-[#8A7F72] mt-1">{{ $package->description }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-black text-[#E8642A]">₱{{ number_format($package->price, 0) }} bundle</p>
                                        <p class="text-xs text-[#8A7F72]">Min: {{ $package->min_guests ?? $caterer->min_guest }} guests</p>
                                    </div>
                                </div>

                                @if($package->includes && is_array($package->includes))
                                <div class="mb-4">
                                    <p class="text-sm font-bold text-[#1C1A17] mb-2">Package Includes:</p>
                                    <ul class="space-y-1 text-sm text-[#8A7F72]">
                                        @foreach($package->includes as $item)
                                        <li class="flex items-center gap-2">
                                            <svg class="size-4 text-[#E8642A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            {{ $item }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form method="POST" action="{{ route('client.bookings.store', $caterer) }}" class="pt-4 border-t border-[#EDE4D8]">
                                    @csrf
                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                    <div class="grid md:grid-cols-3 gap-3 mb-3">
                                        <div>
                                            <label for="event_title_{{ $package->id }}" class="text-xs font-bold text-[#1C1A17] mb-1 block">Event name</label>
                                            <input id="event_title_{{ $package->id }}" name="event_title" type="text" required placeholder="Birthday Party"
                                                class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]">
                                        </div>
                                        <div>
                                            <label for="event_date_{{ $package->id }}" class="text-xs font-bold text-[#1C1A17] mb-1 block">Event date</label>
                                            <input id="event_date_{{ $package->id }}" name="event_date" type="date" min="{{ now()->toDateString() }}" required
                                                class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                                        </div>
                                        <div>
                                            <label for="guests_{{ $package->id }}" class="text-xs font-bold text-[#1C1A17] mb-1 block">Guests</label>
                                            <input id="guests_{{ $package->id }}" name="guests" type="number" required min="1" value="50"
                                                class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="special_requests_{{ $package->id }}" class="text-xs font-bold text-[#1C1A17] mb-1 block">Event notes</label>
                                        <textarea id="special_requests_{{ $package->id }}" name="special_requests" rows="3" placeholder="Menu preferences, allergies, setup needs..."
                                            class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]"></textarea>
                                    </div>
                                    <button type="submit" class="w-full px-4 py-2.5 rounded-lg bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                                        Book This Package
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>



                {{-- Menu Items Section --}}
                @if($menuItems->isNotEmpty())
                <div class="mb-8" x-data="{ selectedItem: null }">
                    <h2 class="text-2xl font-black text-[#1C1A17] mb-6">Menu Items</h2>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($menuItems as $item)
                        <button @click="selectedItem = {{ $item->id }}" class="bg-white rounded-2xl p-5 border border-[#EDE4D8] hover:shadow-lg transition-shadow text-left w-full">
                            @if($item->image_path)
                                <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="w-full h-40 object-cover rounded-xl mb-4">
                            @else
                                <div class="w-full h-40 bg-[#FDF6EE] rounded-xl mb-4 flex items-center justify-center">
                                    <svg class="size-12 text-[#D3CCBE]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </div>
                            @endif
                            <h3 class="font-bold text-[#1C1A17] mb-1">{{ $item->name }}</h3>
                            @if($item->description)
                                <p class="text-xs text-[#8A7F72] mb-3 line-clamp-2">{{ $item->description }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-black text-[#E8642A]">₱{{ number_format($item->price, 0) }}/{{ $item->unit }}</span>
                                <span class="text-xs font-bold text-[#8A7F72] bg-[#FDF6EE] px-2 py-1 rounded">{{ ucfirst($item->category) }}</span>
                            </div>
                        </button>
                        @endforeach
                    </div>

                    {{-- Menu Item Modal --}}
                    @foreach($menuItems as $item)
                    <div x-show="selectedItem === {{ $item->id }}" x-cloak @click.self="selectedItem = null" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
                        <div @click.stop x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-2xl font-black text-[#1C1A17]">{{ $item->name }}</h3>
                                    <button @click="selectedItem = null" class="p-2 hover:bg-[#FDF6EE] rounded-lg transition-colors">
                                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @if($item->image_path)
                                    <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="w-full h-64 object-cover rounded-xl mb-4">
                                @endif
                                <div class="mb-4">
                                    <span class="text-3xl font-black text-[#E8642A]">₱{{ number_format($item->price, 0) }}/{{ $item->unit }}</span>
                                    <span class="ml-3 text-sm font-bold text-[#8A7F72] bg-[#FDF6EE] px-3 py-1 rounded-full">{{ ucfirst($item->category) }}</span>
                                </div>
                                @if($item->description)
                                    <p class="text-sm text-[#1C1A17] leading-relaxed mb-6">{{ $item->description }}</p>
                                @endif
                                <button onclick="requestItem('menu', {{ $item->id }})" @click="selectedItem = null" class="block w-full text-center px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                                    Request This Item
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Add-ons Section --}}
                @if($addOns->isNotEmpty())
                <div class="mb-8" x-data="{ selectedAddon: null }">
                    <h2 class="text-2xl font-black text-[#1C1A17] mb-6">Add-ons</h2>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($addOns as $addon)
                        <button @click="selectedAddon = {{ $addon->id }}" class="bg-white rounded-2xl p-5 border border-[#EDE4D8] hover:shadow-lg transition-shadow text-left w-full">
                            @if($addon->image_path)
                                <img src="{{ $addon->image_path }}" alt="{{ $addon->name }}" class="w-full h-32 object-cover rounded-xl mb-4">
                            @else
                                <div class="w-full h-32 bg-[#FDF6EE] rounded-xl mb-4 flex items-center justify-center">
                                    <svg class="size-10 text-[#D3CCBE]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </div>
                            @endif
                            <h3 class="font-bold text-[#1C1A17] mb-1">{{ $addon->name }}</h3>
                            @if($addon->description)
                                <p class="text-xs text-[#8A7F72] mb-3 line-clamp-2">{{ $addon->description }}</p>
                            @endif
                            <p class="text-lg font-black text-[#E8642A]">₱{{ number_format($addon->price, 0) }}/{{ $addon->unit }}</p>
                        </button>
                        @endforeach
                    </div>

                    {{-- Add-on Modal --}}
                    @foreach($addOns as $addon)
                    <div x-show="selectedAddon === {{ $addon->id }}" x-cloak @click.self="selectedAddon = null" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
                        <div @click.stop x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-2xl font-black text-[#1C1A17]">{{ $addon->name }}</h3>
                                    <button @click="selectedAddon = null" class="p-2 hover:bg-[#FDF6EE] rounded-lg transition-colors">
                                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @if($addon->image_path)
                                    <img src="{{ $addon->image_path }}" alt="{{ $addon->name }}" class="w-full h-64 object-cover rounded-xl mb-4">
                                @endif
                                <div class="mb-4">
                                    <span class="text-3xl font-black text-[#E8642A]">₱{{ number_format($addon->price, 0) }}/{{ $addon->unit }}</span>
                                </div>
                                @if($addon->description)
                                    <p class="text-sm text-[#1C1A17] leading-relaxed mb-6">{{ $addon->description }}</p>
                                @endif
                                <button onclick="requestItem('addon', {{ $addon->id }})" @click="selectedAddon = null" class="block w-full text-center px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                                    Request This Add-on
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </main>
    </div>

    @include('client.partials.remove-saved-modal')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function requestItem(type, id) {
            const checkbox = document.querySelector(`input[name="${type === 'menu' ? 'menu_items' : 'addons'}[]"][value="${id}"]`);
            if (checkbox) {
                checkbox.checked = true;
                checkbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    </script>
</body>
</html>

