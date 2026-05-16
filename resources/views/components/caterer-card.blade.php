@props(['caterer', 'savedCatererIds' => []])

@php
    $isUserProfile = $caterer instanceof \App\Models\User || ($caterer->role ?? null) === 'caterer';
    $hasPersistedProfile = ! empty($caterer->id) && $isUserProfile;
    $detailsUrl = $hasPersistedProfile ? route('caterer.detail', $caterer->id) : route('browse.caterers');
    $bookingUrl = $hasPersistedProfile
        ? (auth()->check() ? route('caterer.detail', $caterer->id).'#booking-request' : route('login', ['redirect' => route('caterer.detail', $caterer->id)]))
        : route('login', ['redirect' => route('browse.caterers')]);
    $displayName = $caterer->business_name ?? $caterer->name ?? 'Caterer';
    $isSaved = in_array($caterer->id, $savedCatererIds);
    $toggleFormId = 'toggle-caterer-' . $caterer->id;
@endphp

<div class="bg-white rounded-2xl overflow-hidden border border-brand-cream-dark/50 hover:shadow-lg transition-shadow">
    <div class="relative h-40 bg-gradient-to-br from-brand-cream-light to-brand-cream-light/50 flex items-center justify-center overflow-hidden">
        @if($caterer->is_featured ?? false)
            <span class="absolute top-3 left-3 bg-brand-orange text-white text-xs font-bold px-2 py-1 rounded-full">FEATURED</span>
        @endif

        @if(auth()->check() && auth()->user()->role === 'client' && $hasPersistedProfile)
            <form id="{{ $toggleFormId }}" method="POST" action="{{ route('client.saved-caterers.toggle', $caterer) }}" class="absolute top-3 right-3">
                @csrf
                <button 
                    type="button"
                    @click.prevent="$dispatch('open-saved-caterer-modal', { formId: @js($toggleFormId), name: @js($displayName), action: @js($isSaved ? 'remove' : 'save') })"
                    class="w-8 h-8 rounded-full bg-white/95 flex items-center justify-center shadow-lg backdrop-blur-sm transition-all hover:scale-110 hover:text-[#BE3455] {{ $isSaved ? 'text-[#BE3455]' : 'text-gray-400' }}" 
                    aria-label="{{ $isSaved ? 'Remove' : 'Save' }} {{ $displayName }}">
                    <svg class="size-4" fill="{{ $isSaved ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </form>
        @elseif(!auth()->check())
            <a href="{{ route('login', ['redirect' => url()->full()]) }}" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/95 flex items-center justify-center shadow-lg backdrop-blur-sm transition-all hover:scale-110 text-gray-400 hover:text-[#BE3455]" aria-label="Login to save caterer">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </a>
        @endif

        @php
            $imageUrl = $caterer->profile_image_url ?? null;
        @endphp
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $displayName }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <svg class="size-16 text-brand-muted/30" style="display:none;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
        @else
            <svg class="size-16 text-brand-muted/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
        @endif
    </div>

    <div class="p-4">
        <div class="flex items-start justify-between gap-3 mb-2">
            <div class="min-w-0">
                <h3 class="truncate text-sm font-bold text-brand-dark">{{ $displayName }}</h3>
                <p class="truncate text-xs text-brand-muted">{{ $caterer->barangay ?? $caterer->location ?? 'Tagum City' }}</p>
            </div>
            <div class="flex shrink-0 items-center gap-1">
                <svg class="size-4 fill-brand-orange" viewBox="0 0 24 24"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="text-xs font-bold text-brand-dark">{{ number_format($caterer->rating ?? 4.8, 1) }}</span>
            </div>
        </div>

        <p class="text-xs text-brand-orange font-medium mb-2">{{ $caterer->cuisine ?? $caterer->cuisine_type ?? $caterer->specialties ?? 'Catering Services' }}</p>

        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-brand-muted mb-3 pb-3 border-b border-brand-cream-dark/50">
            <span>PHP {{ $caterer->price_min ?? '200' }}-{{ $caterer->price_max ?? '600' }}/head</span>
            <span>{{ $caterer->min_guest ?? '10' }}-{{ $caterer->max_guest ?? '100' }} guests</span>
        </div>

        <div class="flex gap-2">
            <a href="{{ $detailsUrl }}" class="flex-1 px-4 py-2.5 rounded-lg bg-brand-orange text-white text-xs font-bold hover:bg-brand-orange-light transition-colors text-center">
                View Details
            </a>
        </div>
    </div>
</div>
