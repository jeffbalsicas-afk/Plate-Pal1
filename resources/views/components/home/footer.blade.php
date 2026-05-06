<footer class="bg-brand-dark text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-10 mb-8 sm:mb-10">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2 mb-3 sm:mb-4">
                    <img src="/assets/PlatePal_logo.jpg" alt="PlatePal" class="size-6 sm:size-7 rounded-lg object-cover">
                    <span class="text-base sm:text-lg font-black tracking-wider">PLATEPAL</span>
                </div>
                <p class="text-xs sm:text-sm text-white/45 leading-relaxed max-w-xs">
                    Connecting Tagum City's best home-based caterers with the community since 2026.
                </p>
            </div>

            {{-- For Clients --}}
            <div>
                <p class="text-[0.65rem] sm:text-xs font-bold uppercase tracking-widest text-white/80 mb-3 sm:mb-4">For Clients</p>
                <ul class="flex flex-col gap-2">
                    @foreach(['Browse Caterers', 'How It Works', 'Client Reviews'] as $link)
                        <li>
                            <a href="#" class="text-xs sm:text-sm text-white/45 hover:text-white transition-colors">
                                {{ $link }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- For Caterers --}}
            <div>
                <p class="text-[0.65rem] sm:text-xs font-bold uppercase tracking-widest text-white/80 mb-3 sm:mb-4">For Caterers</p>
                <ul class="flex flex-col gap-2">
                    @foreach(['Join as Caterer', 'Pricing', 'Success Stories'] as $link)
                        <li>
                            <a href="#" class="text-xs sm:text-sm text-white/45 hover:text-white transition-colors">
                                {{ $link }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        <div class="border-t border-white/10 pt-5 sm:pt-6 text-center text-[0.7rem] sm:text-xs text-white/25">
            © {{ date('Y') }} PlatePal Tagum City. All rights reserved.
        </div>

    </div>
</footer>
