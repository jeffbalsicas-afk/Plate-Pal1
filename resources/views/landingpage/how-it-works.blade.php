<x-layout title="How It Works – PlatePal">

    <x-home.navbar />

    <div class="min-h-screen bg-white">
        {{-- Hero Section --}}
        <div class="bg-gradient-to-br from-white to-brand-cream-light border-b border-brand-cream-dark">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20 text-center">
                <h1 class="text-5xl lg:text-6xl font-display font-black text-brand-dark mb-4">
                    How <span class="text-brand-orange">PlatePal</span> Works
                </h1>
                <p class="text-xl text-brand-muted max-w-2xl mx-auto">
                    Connect with talented home-based caterers in Tagum City in just a few simple steps
                </p>
            </div>
        </div>

        {{-- Steps Section --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
                @foreach([
                    ['num' => '1', 'title' => 'Browse & Discover', 'desc' => 'Explore verified caterers in Tagum City. Filter by cuisine, price, ratings, and location to find your perfect match.'],
                    ['num' => '2', 'title' => 'Send Request', 'desc' => 'Message your chosen caterer directly with your event details, date, guest count, and dietary requirements.'],
                    ['num' => '3', 'title' => 'Confirm Booking', 'desc' => 'Finalize menu options, pricing, and event details. Secure your booking with confirmation from your caterer.'],
                    ['num' => '4', 'title' => 'Enjoy Your Event', 'desc' => 'Relax and enjoy delicious food at your event. Rate your caterer and share your experience with our community.'],
                ] as $step)
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full bg-brand-orange/10 flex items-center justify-center mx-auto mb-6">
                            <span class="text-3xl font-black text-brand-orange">{{ $step['num'] }}</span>
                        </div>
                        <h3 class="text-xl font-black text-brand-dark mb-3">{{ $step['title'] }}</h3>
                        <p class="text-brand-muted">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Why Choose PlatePal --}}
            <div class="bg-brand-cream-light rounded-2xl border border-brand-cream-dark/50 p-12 mb-20">
                <h2 class="text-3xl font-black text-brand-dark mb-12 text-center">Why Choose PlatePal?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['title' => 'Verified Caterers', 'desc' => 'All caterers are verified and reviewed by our community. Safety first, always.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['title' => 'Direct Pricing', 'desc' => 'No hidden fees. Transparent pricing directly from home-based caterers means better value.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['title' => 'Local Flavor', 'desc' => 'Support home-based entrepreneurs. Authentic recipes, real people, genuine service.', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
                    ] as $benefit)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <svg class="size-6 text-brand-orange mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $benefit['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-brand-dark mb-2">{{ $benefit['title'] }}</h3>
                                <p class="text-brand-muted">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- FAQ Section --}}
            <div class="mb-20 bg-brand-cream-light rounded-2xl border border-brand-cream-dark/50 p-8">
                <h2 class="text-3xl font-black text-brand-dark mb-12 text-center">Frequently Asked Questions</h2>
                <div class="space-y-4 max-w-3xl mx-auto">
                    @foreach([
                        ['q' => 'Do I need to create an account to browse caterers?', 'a' => 'No! You can browse all our caterers without an account. However, you\'ll need to create one to send booking requests and messages.'],
                        ['q' => 'What if a caterer is not available for my date?', 'a' => 'Contact the caterer directly through PlatePal messaging! They\'ll let you know about availability or can recommend other dates or alternatives.'],
                        ['q' => 'Can I make special dietary requests?', 'a' => 'Absolutely! Most of our caterers are happy to accommodate dietary preferences and restrictions. Mention them when sending your booking request.'],
                        ['q' => 'What payment methods are accepted?', 'a' => 'Payment methods are arranged directly between you and your caterer. We typically recommend bank transfers or cash on delivery for security.'],
                    ] as $faq)
                        <details class="group bg-white rounded-xl border border-brand-cream-dark/50 p-6 cursor-pointer hover:shadow-sm transition-shadow">
                            <summary class="flex items-center justify-between text-lg font-bold text-brand-dark">
                                {{ $faq['q'] }}
                                <svg class="size-5 text-brand-orange group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </summary>
                            <p class="text-brand-muted mt-4">{{ $faq['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="bg-gradient-to-r from-brand-orange via-brand-orange to-[#f07c42] rounded-3xl p-12 text-center text-white shadow-lg">
                <h2 class="text-3xl font-black mb-4">Ready to find your perfect caterer?</h2>
                <p class="text-lg mb-8 text-white/90">Start browsing or create an account to book your next event</p>
                <div class="flex gap-4 justify-center flex-wrap">
                    <a href="{{ route('browse.caterers') }}" class="px-8 py-3 rounded-lg bg-white text-brand-orange font-bold hover:bg-brand-cream transition-colors">
                        Browse Caterers
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3 rounded-lg border-2 border-white text-white font-bold hover:bg-white/10 transition-colors">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-home.footer />

</x-layout>
