<div class="bg-white rounded-2xl overflow-hidden border border-brand-cream-dark/50 hover:shadow-lg transition-shadow">
    {{-- Featured Badge + Image --}}
    <div class="relative h-40 bg-gradient-to-br from-brand-cream-light to-brand-cream-light/50 flex items-center justify-center overflow-hidden">
        @if($caterer->is_featured ?? false)
            <span class="absolute top-3 left-3 bg-brand-orange text-white text-xs font-bold px-2 py-1 rounded-full">FEATURED</span>
        @endif
        <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center shadow hover:shadow-md transition">
            <svg class="size-4 stroke-brand-muted" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>
        @if($caterer->profile_image ?? false)
            <img src="{{ $caterer->profile_image }}" alt="{{ $caterer->business_name }}" class="w-full h-full object-cover">
        @else
            <svg class="size-16 text-brand-muted/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-1.804 9-4.025V9.25c0-2.221-4.03-4.025-9-4.025S3 7.029 3 9.25v6.7c0 2.221 4.03 4.025 9 4.025z"/></svg>
        @endif
    </div>

    {{-- Caterer Info --}}
    <div class="p-4">
        <div class="flex items-start justify-between mb-2">
            <div>
                <h3 class="text-sm font-bold text-brand-dark">{{ $caterer->business_name ?? $caterer->name }}</h3>
                <p class="text-xs text-brand-muted">{{ $caterer->barangay ?? $caterer->location ?? 'Tagum City' }}</p>
            </div>
            <div class="flex items-center gap-1">
                <svg class="size-4 fill-brand-orange" viewBox="0 0 24 24"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="text-xs font-bold text-brand-dark">{{ number_format($caterer->rating ?? 4.8, 1) }}</span>
            </div>
        </div>

        {{-- Specialty --}}
        <p class="text-xs text-brand-orange font-medium mb-2">{{ $caterer->cuisine ?? $caterer->cuisine_type ?? $caterer->specialties ?? 'Catering Services' }}</p>

        {{-- Price & Guests --}}
        <div class="flex items-center justify-between text-xs text-brand-muted mb-3 pb-3 border-b border-brand-cream-dark/50">
            <span>₱{{ $caterer->price_min ?? '200' }}-{{ $caterer->price_max ?? '600' }}/head</span>
            <span>{{ $caterer->min_guest ?? '10' }}-{{ $caterer->max_guest ?? '100' }} guests</span>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2">
            <a href="{{ route('caterer.detail', $caterer->id) }}" class="flex-1 px-3 py-2 rounded-lg border border-brand-orange text-brand-orange text-xs font-bold hover:bg-brand-cream-light transition-colors text-center">
                View Details
            </a>
            <a href="{{ auth()->check() ? route('caterer.detail', $caterer->id) . '#booking-request' : route('login', ['redirect' => route('caterer.detail', $caterer->id)]) }}" class="flex-1 px-3 py-2 rounded-lg bg-brand-orange text-white text-xs font-bold hover:bg-brand-orange-light transition-colors text-center">
                Book
            </a>
        </div>
    </div>
</div>
