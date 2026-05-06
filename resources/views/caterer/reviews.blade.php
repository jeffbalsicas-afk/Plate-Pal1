<x-dashboard-layout
    title="Reviews & Reputation - PlatePal"
    heading="Reviews & Reputation"
    :username="$displayName"
    :usersub="$user->barangay ?? ''"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            Dashboard
        </a>
        <a href="{{ route('caterer.bookings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Bookings
        </a>
        <a href="{{ route('caterer.menu-pricing') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Menu & Pricing
        </a>
        <a href="{{ route('caterer.messages') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Messages
            </div>
            @if($unreadMessages > 0)
                <span class="bg-red-500 text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
            @endif
        </a>
        <a href="{{ route('caterer.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            Reviews
        </a>
        <a href="{{ route('caterer.earnings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Earnings
        </a>
    </x-slot:sidebar>

    @php
        $tabs = [
            'all' => 'All',
            'public' => 'Public',
            'hidden' => 'Hidden',
            'featured' => 'Featured',
        ];

        $maxRatingCount = max($ratingCounts ?: [0]);
        $starText = fn ($rating) => str_repeat('&#9733;', (int) $rating) . str_repeat('&#9734;', max(0, 5 - (int) $rating));
    @endphp

    <div class="max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 mb-5">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#1C1A17] mb-1">Reviews & Reputation</h1>
                <p class="text-sm text-[#8A6D3F]">Manage visibility, replies, featured testimonials, and review quality signals.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2.5">
                <form method="GET" action="{{ route('caterer.reviews') }}" class="relative">
                    <input type="hidden" name="tab" value="{{ $selectedTab }}">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Search reviews..."
                        class="w-full sm:w-80 pl-10 pr-3 py-3 rounded-xl border border-[#EDE4D8] bg-white text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                </form>
                <form method="POST" action="{{ route('caterer.reviews.request-feedback') }}">
                    @csrf
                    <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                        <svg class="size-4 stroke-white" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                        Request Feedback
                    </button>
                </form>
            </div>
        </div>

        <div class="grid xl:grid-cols-[320px_1fr] gap-5">
            <aside class="space-y-4">
                <section class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-5">
                    <h2 class="text-lg font-black text-[#1C1A17] mb-4">Reviews Overview</h2>
                    <div class="flex items-end gap-3 mb-2">
                        <span class="text-5xl font-black leading-none text-[#1C1A17]">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-2xl leading-none text-[#E8642A]">{!! $starText(round($averageRating)) !!}</span>
                    </div>
                    <p class="text-sm text-[#1C1A17] mb-4">{{ number_format($reviewCounts['all']) }} Total Reviews</p>

                    <div class="space-y-2">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            @php
                                $ratingWidth = $maxRatingCount > 0 ? ($ratingCounts[$rating] / $maxRatingCount) * 100 : 0;
                            @endphp
                            <div class="grid grid-cols-[42px_1fr_28px] items-center gap-2 text-xs text-[#8A7F72]">
                                <span>{{ $rating }} star</span>
                                <div class="h-2 rounded-full bg-[#F7E9DC] overflow-hidden">
                                    <div class="h-full rounded-full bg-[#E8642A]" style="width: {{ $ratingWidth }}%"></div>
                                </div>
                                <span class="text-right font-bold text-[#1C1A17]">{{ $ratingCounts[$rating] }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-base font-black text-[#1C1A17]">Auto-Feature 5-Star Reviews</h2>
                            <p class="text-sm text-[#1C1A17] mt-1">Instantly showcase your best public feedback on your profile.</p>
                        </div>
                        <form method="POST" action="{{ route('caterer.reviews.auto-feature') }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="auto_feature_reviews" value="{{ $user->auto_feature_reviews ? 0 : 1 }}">
                            <button type="submit" class="relative w-14 h-8 rounded-full transition-colors {{ $user->auto_feature_reviews ? 'bg-[#E8642A]' : 'bg-[#CFC8BF]' }}" aria-label="Toggle auto feature">
                                <span class="absolute top-1 size-6 rounded-full bg-white shadow transition-all {{ $user->auto_feature_reviews ? 'left-7' : 'left-1' }}"></span>
                            </button>
                        </form>
                    </div>
                </section>
            </aside>

            <section class="min-w-0">
                <div class="border-b border-[#EDE4D8] mb-4 overflow-x-auto">
                    <div class="flex min-w-max">
                        @foreach($tabs as $tab => $label)
                            <a href="{{ route('caterer.reviews', array_filter(['tab' => $tab === 'all' ? null : $tab, 'search' => $search ?: null])) }}"
                                class="px-5 py-3 text-sm font-bold border-b-2 transition-colors {{ $selectedTab === $tab ? 'border-[#E8642A] text-[#1C1A17] bg-[#FDF6EE]' : 'border-transparent text-[#1C1A17] hover:bg-[#FDF6EE]' }}">
                                {{ $label }} <span class="text-[#8A7F72]">({{ $reviewCounts[$tab] }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($reviews->isEmpty())
                    <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-10 text-center">
                        <div class="size-14 rounded-2xl bg-[#FDF0EA] flex items-center justify-center mx-auto mb-4">
                            <svg class="size-7 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <h2 class="text-xl font-black text-[#1C1A17] mb-2">No reviews found</h2>
                        <p class="text-sm text-[#8A7F72]">Client feedback will appear here after completed events collect reviews.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            @php
                                $reviewer = $review->reviewer_name ?? $review->client?->name ?? 'Client';
                                $reviewedAt = $review->reviewed_at ?? $review->created_at;
                            @endphp

                            <article class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-5" x-data="{ replying: false, reporting: false }">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="text-xl leading-none text-[#E8642A]">{!! $starText($review->rating) !!}</span>
                                            <h2 class="text-lg font-black text-[#1C1A17] leading-tight">"{{ $review->title }}"</h2>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-5 gap-y-1 text-sm mb-2">
                                            <span class="font-black text-[#1C1A17]">{{ $reviewer }}</span>
                                            <span><span class="font-bold text-[#1C1A17]">Date:</span> {{ $reviewedAt->format('M d, Y') }}</span>
                                            @if($review->package_name)
                                                <span><span class="font-bold text-[#1C1A17]">Package:</span> {{ $review->package_name }}</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-[#1C1A17] leading-relaxed">{{ $review->body }}</p>

                                        @if($review->caterer_reply)
                                            <div class="mt-4 rounded-xl bg-[#FDF6EE] border border-[#F2DCCB] p-3">
                                                <div class="text-xs font-black uppercase text-[#8A6D3F] mb-1">Your reply</div>
                                                <p class="text-sm text-[#1C1A17]">{{ $review->caterer_reply }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col items-start lg:items-end gap-3 lg:min-w-48">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $review->status === 'public' ? 'bg-[#FFF2DF] text-[#A94A18]' : 'bg-[#EAF2FB] text-[#2E5F89]' }}">
                                            @if($review->is_auto_featured)
                                                &#9733; Public (Auto-Featured) &#9733;
                                            @elseif($review->is_featured)
                                                Featured
                                            @elseif($review->status === 'public')
                                                Public
                                            @else
                                                Hidden
                                            @endif
                                        </span>

                                        <div class="text-sm text-[#1C1A17]">
                                            {{ $review->status === 'public' ? 'Currently Visible on Profile' : 'Not Visible on Profile' }}
                                        </div>

                                        <div class="flex flex-wrap justify-start lg:justify-end gap-2">
                                            <button type="button" @click="replying = !replying" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">
                                                <svg class="size-4 stroke-[#1C1A17]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.82L3 20l1.18-3.14A7.3 7.3 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                Reply
                                            </button>

                                            @if($review->status === 'public')
                                                <form method="POST" action="{{ route('caterer.reviews.visibility', $review) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="hidden">
                                                    <button type="submit" class="px-3 py-2 rounded-lg text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">Hide</button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('caterer.reviews.visibility', $review) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="public">
                                                    <button type="submit" class="px-3 py-2 rounded-lg text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">Make Public</button>
                                                </form>
                                            @endif

                                            @if($review->status === 'public')
                                                <form method="POST" action="{{ route('caterer.reviews.featured', $review) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_featured" value="{{ $review->is_featured ? 0 : 1 }}">
                                                    <button type="submit" class="px-3 py-2 rounded-lg border border-[#D7B79E] text-sm font-bold text-[#8A4A22] hover:bg-[#FDF6EE] transition-colors">
                                                        {{ $review->is_featured ? 'Remove Featured' : 'Add to Featured List' }}
                                                    </button>
                                                </form>
                                            @endif

                                            <button type="button" @click="reporting = !reporting" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">
                                                <svg class="size-4 stroke-[#1C1A17]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                                Report
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <form x-show="replying" x-transition method="POST" action="{{ route('caterer.reviews.reply', $review) }}" class="mt-4 rounded-xl border border-[#EDE4D8] bg-[#FAFAFA] p-4">
                                    @csrf
                                    <label class="block text-sm font-black text-[#1C1A17] mb-2" for="reply-{{ $review->id }}">Reply to {{ $reviewer }}</label>
                                    <textarea id="reply-{{ $review->id }}" name="caterer_reply" rows="3" required class="w-full rounded-xl border border-[#EDE4D8] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8642A]">{{ old('caterer_reply', $review->caterer_reply) }}</textarea>
                                    <div class="flex justify-end gap-2 mt-3">
                                        <button type="button" @click="replying = false" class="px-3 py-2 rounded-lg border border-[#EDE4D8] text-sm font-bold text-[#8A6D3F]">Cancel</button>
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-[#E8642A] text-white text-sm font-bold">Save Reply</button>
                                    </div>
                                </form>

                                <form x-show="reporting" x-transition method="POST" action="{{ route('caterer.reviews.report', $review) }}" class="mt-4 rounded-xl border border-[#EDE4D8] bg-[#FAFAFA] p-4">
                                    @csrf
                                    <label class="block text-sm font-black text-[#1C1A17] mb-2" for="report-{{ $review->id }}">Report reason</label>
                                    <textarea id="report-{{ $review->id }}" name="report_reason" rows="2" class="w-full rounded-xl border border-[#EDE4D8] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8642A]" placeholder="Optional note for admin review">{{ old('report_reason') }}</textarea>
                                    <div class="flex justify-end gap-2 mt-3">
                                        <button type="button" @click="reporting = false" class="px-3 py-2 rounded-lg border border-[#EDE4D8] text-sm font-bold text-[#8A6D3F]">Cancel</button>
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-[#1C1A17] text-white text-sm font-bold">Submit Report</button>
                                    </div>
                                </form>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-dashboard-layout>
