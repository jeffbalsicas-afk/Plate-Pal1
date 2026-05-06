<section class="bg-[linear-gradient(#F1DEC5_30%,#F4EEEE_100%)] py-12 sm:py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-6 sm:mb-8 gap-3 sm:gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-orange mb-2">Featured near you</p>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-black text-brand-dark">
                    Featured Local Caterers
                </h2>
                <p class="text-sm text-brand-muted mt-2 max-w-2xl">
                    Start with trusted caterers that show the key details clients compare first: location, specialty, price, group size, and response time.
                </p>
            </div>
            <a href="{{ route('browse.caterers') }}" class="group flex items-center gap-1 text-xs sm:text-sm font-bold text-brand-orange hover:text-brand-orange-light transition-colors">
                View All
                <svg class="size-3 sm:size-4 transition-transform group-hover:translate-x-1"
                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Add caterer cards manually here --}}
        </div>

    </div>
</section>
