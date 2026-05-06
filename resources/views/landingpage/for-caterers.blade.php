<x-layout title="For Caterers – PlatePal">

    <x-home.navbar />

    <div class="min-h-screen bg-white">
        {{-- Hero Section --}}
        <div class="bg-gradient-to-br from-white to-brand-cream-light border-b border-brand-cream-dark">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20 text-center">
                <h1 class="text-5xl lg:text-6xl font-display font-black text-brand-dark mb-4">
                    Grow Your Catering <span class="text-brand-orange">Business</span>
                </h1>
                <p class="text-xl text-brand-muted max-w-2xl mx-auto">
                    Join PlatePal and reach more customers in Tagum City. No commissions. Direct bookings. Your rules.
                </p>
                <div class="mt-8">
                    <a href="{{ route('caterer.register') }}" class="inline-block px-8 py-4 rounded-lg bg-brand-orange text-white font-bold text-lg hover:bg-brand-orange-light transition-colors shadow-lg">
                        Get Started as Caterer
                    </a>
                </div>
            </div>
        </div>

        {{-- Benefits Section --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 bg-white">
            <h2 class="text-3xl font-black text-brand-dark mb-12 text-center">Why Join PlatePal?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach([
                    ['title' => 'Keep 100% of Your Earnings', 'desc' => 'No platform fees. No commissions. We keep 0%. You set your own prices and keep everything you earn.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Direct Customer Connection', 'desc' => 'Message customers directly. Build relationships. Grow repeat business. No middleman communication.', 'icon' => 'M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM16 12a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['title' => 'Easy to Get Started', 'desc' => 'Simple registration. Showcase your menu and specialties. Start receiving bookings from day one.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['title' => 'Community & Reviews', 'desc' => 'Build your reputation. Verified reviews from real customers. Grow your credibility and trust.', 'icon' => 'M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Flexible Scheduling', 'desc' => 'You control when you work. Accept or decline bookings. Run your business your way.', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                    ['title' => 'Full Control', 'desc' => 'Manage your profile, menu, pricing, and availability. Update anytime. Complete control over your business.', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                ] as $benefit)
                    <div class="bg-brand-cream-light rounded-2xl border border-brand-cream-dark/50 p-8 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-lg bg-brand-orange/10 flex items-center justify-center mb-4">
                            <svg class="size-6 text-brand-orange" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $benefit['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-brand-dark mb-3">{{ $benefit['title'] }}</h3>
                        <p class="text-brand-muted">{{ $benefit['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- How It Works for Caterers --}}
        <div class="bg-brand-cream-light border-t border-brand-cream-dark/30">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
                <h2 class="text-3xl font-black text-brand-dark mb-12 text-center">How to Get Started</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach([
                        ['num' => '1', 'title' => 'Register Your Business', 'desc' => 'Sign up with your basic business information, contact, and location.'],
                        ['num' => '2', 'title' => 'Set Up Your Profile', 'desc' => 'Add your specialties, menu items, pricing, and photos of your cuisine.'],
                        ['num' => '3', 'title' => 'Get Approved', 'desc' => 'Our team verifies your information. Typically approved within 24 hours.'],
                        ['num' => '4', 'title' => 'Start Getting Bookings', 'desc' => 'Customers find and message you directly. Accept bookings and grow!'],
                    ] as $step)
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-brand-orange text-white flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl font-black">{{ $step['num'] }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-brand-dark mb-2">{{ $step['title'] }}</h3>
                            <p class="text-sm text-brand-muted">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- FAQ Section --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 bg-white">
            <h2 class="text-3xl font-black text-brand-dark mb-12 text-center">Caterer FAQs</h2>
            <div class="space-y-4 max-w-3xl mx-auto bg-brand-cream-light rounded-2xl border border-brand-cream-dark/50 p-8">
                @foreach([
                    ['q' => 'Is there a membership fee?', 'a' => 'No! PlatePal is completely free. Zero registration fees, zero membership fees, zero platform commissions. You keep 100% of what you earn.'],
                    ['q' => 'How do I set my own prices?', 'a' => 'When setting up your profile, you can add menu items with individual prices. You can also negotiate pricing directly with customers through messages. You have full control.'],
                    ['q' => 'What if I\'m not available for a booking request?', 'a' => 'You can decline booking requests through the platform. There\'s no penalty or obligation. Accept only the bookings that work for you.'],
                    ['q' => 'How do customers pay?', 'a' => 'You arrange payment directly with your customers. Common methods include bank transfers, cash on delivery, or GCash. Completely up to you.'],
                    ['q' => 'Can I upload food photos?', 'a' => 'Yes! You can add photos of your dishes to showcase your cuisine. High-quality photos help attract more customers.'],
                    ['q' => 'Is my business information private?', 'a' => 'We never share your personal information with third parties. Your customers only see what you choose to display on your profile.'],
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
        <div class="bg-gradient-to-r from-brand-orange via-brand-orange to-[#f07c42] rounded-3xl p-12 text-center text-white mx-6 lg:mx-8 mb-20 shadow-lg">
            <h2 class="text-3xl font-black mb-4">Ready to Grow Your Business?</h2>
            <p class="text-lg mb-8 text-white/90">Join hundreds of caterers already on PlatePal and reach more customers in Tagum City</p>
            <a href="{{ route('caterer.register') }}" class="inline-block px-8 py-4 rounded-lg bg-white text-brand-orange font-bold text-lg hover:bg-brand-cream transition-colors">
                Register as Caterer
            </a>
        </div>

        {{-- How It Works Link --}}
        <div class="text-center mb-20">
            <p class="text-brand-muted mb-4">Want to learn how customers use PlatePal?</p>
            <a href="{{ route('how.it.works') }}" class="inline-block text-brand-orange font-bold hover:text-brand-orange-light transition-colors">
                Read How It Works →
            </a>
        </div>
    </div>

    <x-home.footer />

</x-layout>
