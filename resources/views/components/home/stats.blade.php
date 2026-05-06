<div class="animate-fade-up flex flex-col sm:flex-row justify-center mt-6 sm:mt-10 gap-4 sm:gap-0" style="animation-delay: .25s">
    @foreach([
        ['value' => '48+',  'label' => 'Home caterers'],
        ['value' => '12',   'label' => 'Barangays Covered'],
        ['value' => '320+', 'label' => 'Events Served'],
    ] as $index => $stat)
        <div class="{{ $index > 0 ? 'sm:border-l sm:border-[color-mix(in_srgb,#6B5E4E_25%,transparent)] sm:pl-8 md:pl-12' : '' }} px-4 sm:px-8 md:px-12 text-center">
            <p class="font-display text-2xl sm:text-3xl md:text-4xl font-black text-brand-dark">
                {{ $stat['value'] }}
            </p>
            <p class="text-[0.7rem] sm:text-xs md:text-sm text-brand-muted font-medium mt-0.5">
                {{ $stat['label'] }}
            </p>
        </div>
    @endforeach
</div>
