@php
    $initials = strtoupper(substr($user?->name ?? 'U', 0, 1) . (str_contains($user?->name ?? 'U', ' ') ? substr($user?->name ?? 'U', strpos($user?->name ?? 'U', ' ') + 1, 1) : ''));
    $profileImageUrl = $caterer->profile_image_url;
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
    $isClient = $user?->role === 'client';
    $isOwner = $caterer->id === auth()->id();
    $defaultGuests = min(
        max(50, (int) ($caterer->min_guest ?? 1)),
        (int) ($caterer->max_guest ?? 10000)
    );
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $caterer->business_name }} – PlatePal</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/PlatePal_logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/PlatePal_logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @livewireStyles
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
        {{-- NAVBAR - Always visible for guests --}}
        <x-home.navbar />

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
                        @if($profileImageUrl)
                            <img src="{{ $profileImageUrl }}" alt="{{ $caterer->business_name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <svg class="hidden size-32 text-[#D3CCBE]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        @else
                            <svg class="size-32 text-[#D3CCBE]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        @endif
                    </div>

                    {{-- Info Section --}}
                    <div>
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h1 class="text-3xl font-black text-[#1C1A17] mb-1">{{ $caterer->business_name }}</h1>
                                <p class="text-sm text-[#8A7F72] flex items-center gap-1 mb-3">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $caterer->barangay }}, Tagum City
                                </p>
                                @if($caterer->description)
                                <p class="text-sm text-[#1C1A17] leading-relaxed"><span class="font-semibold text-md text-[#000000]">Description:​</span> {{ $caterer->description }}</p>
                                @endif
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
                        @if($isClient)
                            <div class="grid grid-cols-2 gap-3">
                                <a href="#booking-request" class="text-center px-6 py-3 rounded-xl bg-[#E8642A] text-white font-bold hover:bg-[#F07C42] transition-colors">
                                    Book Now
                                </a>
                                <a href="{{ route('messages.show', $caterer) }}" class="text-center px-6 py-3 rounded-xl border border-[#E8642A] text-[#E8642A] font-bold hover:bg-[#FDF6EE] transition-colors">
                                    Send Message
                                </a>
                            </div>
                        @elseif($isOwner)
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

                    <div
                        class="rounded-2xl border border-[#EDE4D8] bg-white p-6"
                        x-data="{
                            galleryViewerOpen: false,
                            galleryViewerImage: '',
                            galleryViewerAlt: 'Gallery image',
                            openGalleryViewer(image, alt) {
                                this.galleryViewerImage = image;
                                this.galleryViewerAlt = alt;
                                this.galleryViewerOpen = true;
                            },
                            closeGalleryViewer() {
                                this.galleryViewerOpen = false;
                                this.galleryViewerImage = '';
                            }
                        }"
                    >
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
                                    @php
                                        $galleryImageUrl = str_starts_with($galleryImage, 'http') || str_starts_with($galleryImage, '/storage/')
                                            ? $galleryImage
                                            : asset(ltrim($galleryImage, '/'));
                                        $galleryImageAlt = 'Gallery image '.$loop->iteration;
                                    @endphp
                                    <button
                                        type="button"
                                        @click="openGalleryViewer(@js($galleryImageUrl), @js($galleryImageAlt))"
                                        class="group h-40 overflow-hidden rounded-3xl bg-[#FDF6EE] focus:outline-none focus:ring-2 focus:ring-[#E8642A] focus:ring-offset-2"
                                        aria-label="View {{ strtolower($galleryImageAlt) }}"
                                    >
                                        <img src="{{ $galleryImageUrl }}" alt="{{ $galleryImageAlt }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    </button>
                                @endforeach
                            </div>
                            <template x-teleport="body">
                                <div
                                    x-show="galleryViewerOpen"
                                    x-cloak
                                    x-transition.opacity
                                    x-effect="window.PlatePalModals?.toggle('guest-gallery-viewer', galleryViewerOpen)"
                                    @keydown.escape.window="closeGalleryViewer()"
                                    @click.self="closeGalleryViewer()"
                                    class="fixed inset-0 z-[90] flex items-center justify-center bg-black/80 p-4"
                                    role="dialog"
                                    aria-modal="true"
                                >
                                    <div class="relative w-full max-w-5xl">
                                        <button type="button" @click="closeGalleryViewer()" class="absolute right-2 top-2 z-10 rounded-full bg-white/95 p-2 text-[#1C1A17] shadow-lg transition-colors hover:bg-[#FDF6EE]" aria-label="Close gallery image">
                                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        <img :src="galleryViewerImage" :alt="galleryViewerAlt" class="max-h-[86vh] w-full rounded-2xl object-contain shadow-2xl">
                                    </div>
                                </div>
                            </template>
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
                                <p class="text-sm text-[#8A7F72]">{{ $reviewsCount }} {{ $reviewsCount === 1 ? 'review' : 'reviews' }} from completed events</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="size-5 fill-[#F59E0B]" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-lg font-black text-[#1C1A17]">{{ number_format($averageRating, 1) }}</span>
                            </div>
                        </div>
                        
                        <div class="space-y-4" id="reviewsContainer">
                            @foreach($publicReviews as $index => $review)
                                @php
                                    $reviewer = $review->reviewer_name ?? $review->client?->name ?? 'Client';
                                    $reviewedAt = $review->reviewed_at ?? $review->created_at;
                                    $initials = strtoupper(substr($reviewer, 0, 1) . (str_contains($reviewer, ' ') ? substr($reviewer, strpos($reviewer, ' ') + 1, 1) : substr($reviewer, 1, 1)));
                                @endphp
                                
                                <div class="review-item bg-white rounded-2xl border border-[#EDE4D8] shadow-sm overflow-hidden {{ $index >= 3 ? 'hidden' : '' }}">
                                    <!-- Comment Header -->
                                    <div class="flex items-start gap-3 p-5 pb-4">
                                        <div class="flex size-10 items-center justify-center rounded-full bg-[#E8642A] text-white text-sm font-black flex-shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-black text-[#1C1A17]">{{ $reviewer }}</span>
                                                @if($review->is_featured)
                                                    <span class="rounded-full bg-[#FFF2DF] px-2 py-0.5 text-[10px] font-black text-[#A94A18]">FEATURED</span>
                                                @endif
                                                <span class="text-xs text-[#8A7F72]">•</span>
                                                <span class="text-xs text-[#8A7F72]">{{ $reviewedAt->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="size-4 {{ $i <= $review->rating ? 'fill-[#F59E0B]' : 'fill-[#E5E7EB]' }}" viewBox="0 0 24 24">
                                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Comment Body -->
                                    <div class="px-5 pb-5">
                                        @if($review->title)
                                            <h3 class="font-bold text-[#1C1A17] mb-2">"{{ $review->title }}"</h3>
                                        @endif
                                        <p class="text-sm text-[#1C1A17] leading-relaxed">{{ $review->body }}</p>
                                    </div>
                                    
                                    <!-- Caterer Reply -->
                                    @if($review->caterer_reply)
                                        <div class="bg-[#FDF6EE] border-t border-[#EDE4D8] p-5">
                                            <div class="flex items-start gap-3">
                                                <div class="flex size-8 items-center justify-center rounded-full bg-[#8A6D3F] text-white text-xs font-black flex-shrink-0">
                                                    {{ strtoupper(substr($caterer->business_name ?? $caterer->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-sm font-black text-[#1C1A17]">{{ $caterer->business_name ?? $caterer->name }}</span>
                                                        <span class="rounded-full bg-[#E8642A] px-2 py-0.5 text-[10px] font-black text-white">CATERER</span>
                                                    </div>
                                                    <p class="text-sm text-[#1C1A17] leading-relaxed">{{ $review->caterer_reply }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($publicReviews->count() > 3)
                            <div class="mt-4 text-center">
                                <button 
                                    id="viewAllReviewsBtn" 
                                    onclick="toggleReviews()" 
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-[#E8642A] text-[#E8642A] text-sm font-bold hover:bg-[#E8642A] hover:text-white transition-colors"
                                >
                                    <span id="btnText">View All {{ $publicReviews->count() }} Reviews</span>
                                    <svg id="btnIcon" class="size-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>

                            <script>
                                let reviewsExpanded = false;
                                
                                function toggleReviews() {
                                    const hiddenReviews = document.querySelectorAll('.review-item.hidden');
                                    const btnText = document.getElementById('btnText');
                                    const btnIcon = document.getElementById('btnIcon');
                                    
                                    if (reviewsExpanded) {
                                        // Collapse - hide reviews after index 2
                                        document.querySelectorAll('.review-item').forEach((item, index) => {
                                            if (index >= 3) {
                                                item.classList.add('hidden');
                                            }
                                        });
                                        btnText.textContent = 'View All {{ $publicReviews->count() }} Reviews';
                                        btnIcon.style.transform = 'rotate(0deg)';
                                        reviewsExpanded = false;
                                        
                                        // Scroll to reviews section
                                        document.getElementById('reviewsContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    } else {
                                        // Expand - show all reviews
                                        hiddenReviews.forEach(review => {
                                            review.classList.remove('hidden');
                                        });
                                        btnText.textContent = 'Show Less';
                                        btnIcon.style.transform = 'rotate(180deg)';
                                        reviewsExpanded = true;
                                    }
                                }
                            </script>
                        @endif
                    </div>
                @endif

                <div id="booking-request" class="mb-8 bg-gradient-to-br from-[#FFF8F0] to-[#FDF6EE] rounded-2xl p-1 shadow-lg scroll-mt-24">
                    <div class="bg-white rounded-xl p-6">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                            <div>
                                <div class="inline-flex items-center gap-2 mb-2">
                                    <div class="size-8 rounded-lg bg-gradient-to-br from-[#E8642A] to-[#F07C42] flex items-center justify-center">
                                        <svg class="size-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-2xl font-black text-[#1C1A17]">Request a Booking</h2>
                                </div>
                                <p class="text-sm text-[#8A7F72]">Send your event details to {{ $caterer->business_name ?? $caterer->name }}.</p>
                            </div>
                            <span class="text-xs font-bold text-[#8A6D3F] bg-[#FFF8E1] border border-[#F3D68B] rounded-full px-3 py-1.5 flex items-center gap-1.5">
                                <span class="size-1.5 rounded-full bg-[#F59E0B] animate-pulse"></span>
                                Pending until confirmed
                            </span>
                        </div>

                    @if(!$isClient)
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
                                <input id="guests" name="guests" value="{{ old('guests', $defaultGuests) }}" type="number" required min="1" max="10000" placeholder="Caterer typically serves {{ $caterer->min_guest ?? 20 }}-{{ $caterer->max_guest ?? 100 }} guests"
                                    class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                                <p class="mt-1 text-xs text-[#8A7F72]">Typical range: {{ $caterer->min_guest ?? 20 }}-{{ $caterer->max_guest ?? 100 }} guests (flexible)</p>
                            </div>
                        </div>

                        <div>
                            <label for="client_budget" class="text-xs font-bold text-[#1C1A17] mb-1.5 block">Your Budget (Optional)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-[#8A7F72]">₱</span>
                                <input id="client_budget" name="client_budget" value="{{ old('client_budget') }}" type="number" step="0.01" min="0" max="9999999.99" placeholder="e.g., 15000.00"
                                    class="w-full pl-8 pr-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]">
                            </div>
                            <p class="mt-1.5 text-xs text-[#8A7F72]">Let the caterer know your expected budget. This helps them provide accurate quotes.</p>
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

                        @include('caterer.partials.detail-menu-selector')

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

                <!-- Success Modal -->
                @if(session('booking_success'))
                <dialog id="bookingSuccessModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 m-0 max-w-md rounded-2xl p-0 backdrop:bg-black/50">
                    <div class="p-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="size-16 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="size-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-xl font-black text-[#1C1A17] mb-2 text-center">Booking Request Sent!</h2>
                        <p class="text-sm text-[#8A6D3F] mb-6 text-center">Your booking request has been sent to {{ $caterer->business_name ?? $caterer->name }}. They will review and respond soon.</p>
                        
                        <div class="rounded-xl bg-[#FFF8E1] border border-[#F3D68B] px-4 py-3 mb-4">
                            <div class="flex items-start gap-2">
                                <svg class="size-5 text-[#B26A00] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-xs text-[#B26A00]">
                                    <p class="font-bold">What's next?</p>
                                    <p class="mt-1">Check your bookings page for updates. You can also message the caterer to discuss details.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('client.bookings') }}" class="flex-1 px-4 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors text-center">
                                View My Bookings
                            </a>
                            <button type="button" onclick="document.getElementById('bookingSuccessModal').close()" class="flex-1 px-4 py-3 rounded-xl border border-[#EDE4D8] text-[#8A6D3F] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">
                                Stay Here
                            </button>
                        </div>
                    </div>
                </dialog>

                <script>
                    // Show modal if there's a booking success message
                    @if(session('booking_success'))
                        document.addEventListener('DOMContentLoaded', function() {
                            const modal = document.getElementById('bookingSuccessModal');
                            if (modal) {
                                modal.showModal();
                            }
                        });
                    @endif
                </script>
                @endif

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
                            <div class="bg-white rounded-2xl overflow-hidden border border-[#EDE4D8] hover:shadow-lg transition-shadow">
                                @if($package->image)
                                <div class="relative w-full h-72 overflow-hidden">
                                    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                        <h3 class="text-2xl font-black mb-1">{{ $package->name }}</h3>
                                        <p class="text-3xl font-black text-[#FF8C5A]">₱{{ number_format($package->price, 0) }} <span class="text-base font-semibold text-white/90">bundle</span></p>
                                    </div>
                                </div>
                                @endif
                                <div class="p-6">
                                    @if(!$package->image)
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-[#1C1A17]">{{ $package->name }}</h3>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-black text-[#E8642A]">₱{{ number_format($package->price, 0) }} bundle</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="flex items-center gap-4 mb-4 text-sm text-[#8A7F72]">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span class="font-semibold">Suggested: {{ $package->min_guests ?? $caterer->min_guest }}+ guests</span>
                                        </div>
                                    </div>

                                    @if($package->description)
                                    <div class="mb-4 pb-4 border-b border-[#EDE4D8]">
                                        <p class="text-sm text-[#8A7F72] leading-relaxed">{{ $package->description }}</p>
                                    </div>
                                    @endif

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

                                @if($isClient)
                                    @php
                                        $packageMinGuests = 1; // Allow any guest count
                                        $packageDefaultGuests = $defaultGuests;
                                    @endphp
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
                                                <input id="guests_{{ $package->id }}" name="guests" type="number" required min="1" max="10000" value="{{ $packageDefaultGuests }}" placeholder="Suggested: {{ $package->min_guests ?? 20 }}+"
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
                                @elseif($isOwner)
                                    <div class="pt-4 border-t border-[#EDE4D8]">
                                        <a href="{{ route('caterer.menu-pricing') }}" class="block w-full rounded-lg border border-[#E8642A] px-4 py-2.5 text-center text-sm font-bold text-[#E8642A] transition-colors hover:bg-[#FDF6EE]">
                                            Manage Package
                                        </a>
                                    </div>
                                @else
                                    <div class="pt-4 border-t border-[#EDE4D8]">
                                        <a href="{{ route('login') }}" onclick="sessionStorage.setItem('return_to', window.location.href); return true;" class="block w-full rounded-lg bg-[#E8642A] px-4 py-2.5 text-center text-sm font-bold text-white transition-colors hover:bg-[#F07C42]">
                                            Login to Book This Package
                                        </a>
                                    </div>
                                @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @include('caterer.partials.detail-menu-catalog', ['modalPrefix' => 'guest'])
            </div>
        </main>
    </div>

    @include('client.partials.remove-saved-modal')

    <script>
        function requestItem(type, id) {
            const checkbox = document.querySelector(`input[name="${type === 'menu' ? 'menu_items' : 'addons'}[]"][value="${id}"]`);
            const bookingSection = document.getElementById('booking-request');

            if (checkbox) {
                checkbox.checked = true;
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                bookingSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                checkbox.closest('label')?.classList.add('ring-2', 'ring-[#E8642A]', 'bg-white');
                setTimeout(() => checkbox.closest('label')?.classList.remove('ring-2', 'ring-[#E8642A]', 'bg-white'), 1400);
                return;
            }

            bookingSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            bookingSection?.querySelector('a, button, input, select, textarea')?.focus({ preventScroll: true });
        }
    </script>
    @livewireScripts
</body>
</html>
