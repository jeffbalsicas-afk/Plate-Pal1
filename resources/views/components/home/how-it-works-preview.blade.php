<section class="bg-brand-white px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto">
        <div class="max-w-2xl mb-8 sm:mb-10">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-orange mb-2">Simple booking flow</p>
            <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-black text-brand-dark">Plan your event without the back-and-forth guessing</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
            @foreach([
                ['step' => '01', 'title' => 'Browse by need', 'body' => 'Search caterers by barangay, food specialty, price range, and event size.'],
                ['step' => '02', 'title' => 'Send event details', 'body' => 'Share your date, guest count, location, preferred dishes, and special requests.'],
                ['step' => '03', 'title' => 'Confirm with confidence', 'body' => 'Compare options, message directly, and finalize the caterer that fits your event.'],
            ] as $item)
                <article class="bg-white border border-brand-cream-dark rounded-2xl p-5 sm:p-6 shadow-sm">
                    <div class="size-10 rounded-xl bg-brand-orange text-white font-black flex items-center justify-center mb-5">
                        {{ $item['step'] }}
                    </div>
                    <h3 class="text-lg font-black text-brand-dark mb-2">{{ $item['title'] }}</h3>
                    <p class="text-sm text-brand-muted leading-relaxed">{{ $item['body'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
