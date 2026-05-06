<section class="bg-white px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto overflow-hidden rounded-3xl bg-brand-dark text-white">
        <div class="grid lg:grid-cols-[1.2fr_0.8fr]">
            <div class="p-6 sm:p-8 lg:p-10">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-cream-dark mb-3">For home-based caterers</p>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-black mb-4">Get discovered by clients planning real events in Tagum City</h2>
                <p class="text-sm sm:text-base text-white/65 leading-relaxed max-w-2xl mb-6">
                    Create a profile for your business, show your best dishes, list your price range, and receive inquiries from people looking for local catering.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('caterer.register') }}" class="inline-flex justify-center px-5 py-3 rounded-xl bg-brand-orange text-white text-sm font-bold hover:bg-brand-orange-light transition-colors">
                        Join as caterer
                    </a>
                    <a href="{{ route('for.caterers') }}" class="inline-flex justify-center px-5 py-3 rounded-xl border border-white/20 text-white text-sm font-bold hover:bg-white/10 transition-colors">
                        Learn more
                    </a>
                </div>
            </div>

            <div class="bg-brand-orange/15 p-6 sm:p-8 lg:p-10 flex items-center">
                <div class="grid grid-cols-2 gap-3 w-full">
                    @foreach([
                        ['value' => 'Free', 'label' => 'profile setup'],
                        ['value' => '24', 'label' => 'Tagum barangays'],
                        ['value' => 'Direct', 'label' => 'client inquiries'],
                        ['value' => 'Local', 'label' => 'food community'],
                    ] as $stat)
                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <p class="font-display text-2xl font-black">{{ $stat['value'] }}</p>
                            <p class="text-xs text-white/60">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
