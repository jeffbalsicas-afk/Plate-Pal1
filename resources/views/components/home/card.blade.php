@props([
    'name'    => '',
    'location'=> '',
    'cuisine' => '',
    'rating'  => '',
    'reviews' => 0,
    'price'   => '',
    'image'   => '',
    'badge'   => 'Featured',
    'guests'  => '',
    'response'=> '',
    'delay'   => '0s',
])

<article
    class="animate-fade-up bg-white rounded-xl sm:rounded-2xl overflow-hidden cursor-pointer transition-[transform,box-shadow] duration-300 ease-out shadow-lg hover:-translate-y-1.5 hover:shadow-xl"
    style="animation-delay: {{ $delay }}"
>
    {{-- Image --}}
    <div class="relative h-40 sm:h-48 overflow-hidden">
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 block"
        >
        <div class="absolute inset-0 bg-linear-to-b from-transparent via-transparent to-[rgba(26,18,8,0.5)]"></div>
        <span class="absolute top-2 sm:top-3 left-2 sm:left-3 text-brand-dark text-[0.65rem] sm:text-xs font-black px-2 sm:px-3 py-1 sm:py-1.5 rounded-full bg-white/90 backdrop-blur-sm">
            {{ $badge }}
        </span>
        <span class="absolute top-2 sm:top-3 right-2 sm:right-3 text-white text-[0.7rem] sm:text-xs font-bold px-2 sm:px-3 py-1 sm:py-1.5 rounded-full bg-[rgba(26,18,8,0.75)] backdrop-blur-sm">
            {{ $price }}
        </span>
    </div>

    {{-- Body --}}
    <div class="p-3 sm:p-5">
        <div class="flex items-start justify-between gap-2 mb-1">
            <h3 class="font-display font-bold text-base sm:text-lg leading-tight text-brand-dark">
                {{ $name }}
            </h3>
            <div class="flex items-center gap-1 shrink-0">
                <svg class="size-3 sm:size-4 fill-amber-400" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-xs sm:text-sm font-bold text-brand-dark">{{ $rating }}</span>
            </div>
        </div>

        <div class="flex items-center gap-1 text-[0.7rem] sm:text-xs text-brand-muted mb-2">
            <svg class="size-2.5 sm:size-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $location }}
        </div>

        <p class="text-xs sm:text-sm text-brand-muted mb-3">{{ $cuisine }}</p>

        <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="rounded-xl bg-brand-cream/40 px-3 py-2">
                <p class="text-[0.62rem] uppercase tracking-wide text-brand-muted/70 font-bold">Group size</p>
                <p class="text-xs font-bold text-brand-dark">{{ $guests }}</p>
            </div>
            <div class="rounded-xl bg-brand-cream/40 px-3 py-2">
                <p class="text-[0.62rem] uppercase tracking-wide text-brand-muted/70 font-bold">Response</p>
                <p class="text-xs font-bold text-brand-dark">{{ $response }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 pt-3 border-t border-brand-cream-dark">
            <p class="text-[0.65rem] sm:text-xs text-brand-muted/70">{{ number_format($reviews) }} reviews</p>
            <a href="{{ route('browse.caterers') }}" class="text-xs font-black text-brand-orange hover:text-brand-orange-light transition-colors">View details</a>
        </div>
    </div>
</article>
