<x-dashboard-layout
    title="Reviews - PlatePal"
    heading="Reviews"
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

        $starText = fn ($rating) => str_repeat('&#9733;', (int) $rating) . str_repeat('&#9734;', max(0, 5 - (int) $rating));
    @endphp

    <div class="max-w-7xl mx-auto space-y-5">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="rounded-2xl border border-[#EDE4D8] bg-white p-5 sm:p-6 shadow-sm">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                <div class="min-w-0">
                    <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17]">Reviews</h1>
                    <p class="mt-2 max-w-2xl text-sm sm:text-base text-[#8A6D3F]">Reply to clients, choose what appears on your profile, and highlight your best feedback.</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-y-4 sm:divide-x sm:divide-[#EDE4D8] xl:min-w-[560px]">
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Rating</div>
                        <div class="mt-1 text-2xl font-black text-[#1C1A17]">{{ number_format($averageRating, 1) }}</div>
                        <div class="text-xs text-[#E8642A]">{!! $starText(round($averageRating)) !!}</div>
                    </div>
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Reviews</div>
                        <div class="mt-1 text-2xl font-black text-[#1C1A17]">{{ number_format($reviewCounts['all']) }}</div>
                        <div class="text-xs text-[#8A7F72]">Total</div>
                    </div>
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Public</div>
                        <div class="mt-1 text-2xl font-black text-[#2E7D32]">{{ number_format($reviewCounts['public']) }}</div>
                        <div class="text-xs text-[#8A7F72]">On profile</div>
                    </div>
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Featured</div>
                        <div class="mt-1 text-2xl font-black text-[#E8642A]">{{ number_format($reviewCounts['featured']) }}</div>
                        <div class="text-xs text-[#8A7F72]">Highlighted</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-[#EDE4D8] bg-white p-4 shadow-sm">
            <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                <form method="GET" action="{{ route('caterer.reviews') }}" class="relative">
                    <input type="hidden" name="tab" value="{{ $selectedTab }}">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Search by client, title, package, or comment..."
                        class="w-full rounded-xl border border-[#EDE4D8] bg-[#FDF6EE] py-3 pl-10 pr-4 text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                </form>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <form method="POST" action="{{ route('caterer.reviews.auto-feature') }}" class="flex items-center justify-between gap-3 rounded-xl border border-[#EDE4D8] px-3.5 py-2.5">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="auto_feature_reviews" value="{{ $user->auto_feature_reviews ? 0 : 1 }}">
                        <span class="text-xs font-black text-[#1C1A17]">Auto-feature 5-star</span>
                        <button type="submit" class="relative h-7 w-12 rounded-full transition-colors {{ $user->auto_feature_reviews ? 'bg-[#E8642A]' : 'bg-[#CFC8BF]' }}" aria-label="Toggle auto feature">
                            <span class="absolute top-1 size-5 rounded-full bg-white shadow transition-all {{ $user->auto_feature_reviews ? 'left-6' : 'left-1' }}"></span>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="min-w-0 space-y-4">
            <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-2 overflow-x-auto">
                <div class="grid grid-cols-4 min-w-[560px] gap-2">
                    @foreach($tabs as $tab => $label)
                        <a href="{{ route('caterer.reviews', array_filter(['tab' => $tab === 'all' ? null : $tab, 'search' => $search ?: null])) }}"
                            class="text-center rounded-xl px-3 py-3 text-sm font-bold transition-colors {{ $selectedTab === $tab ? 'bg-[#E8642A] text-white shadow-sm' : 'text-[#8A6D3F] hover:bg-[#FDF6EE]' }}">
                            {{ $label }}
                            <span class="{{ $selectedTab === $tab ? 'text-white/80' : 'text-[#8A7F72]' }} text-xs font-bold ml-1">({{ $reviewCounts[$tab] }})</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @if($reviews->isEmpty())
                <div class="bg-white rounded-2xl border border-dashed border-[#EDE4D8] shadow-sm p-10 text-center">
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
                            $statusLabel = $review->is_auto_featured
                                ? 'Auto-featured'
                                : ($review->is_featured ? 'Featured' : ucfirst($review->status));
                            $statusClass = $review->status === 'public'
                                ? ($review->is_featured ? 'bg-[#FEF3EC] text-[#E8642A] border-[#F6B78E]' : 'bg-[#EAF5E9] text-[#2E7D32] border-[#CDE8CC]')
                                : 'bg-[#F6EFE7] text-[#8A6D3F] border-[#EDE4D8]';
                        @endphp

                        <article class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-5 sm:p-6" x-data="{ replying: false, more: false }">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="text-lg leading-none text-[#E8642A]">{!! $starText($review->rating) !!}</span>
                                        <span class="rounded-full border px-2.5 py-1 text-[11px] font-black {{ $statusClass }}">{{ $statusLabel }}</span>
                                    </div>

                                    <h2 class="text-xl font-black leading-tight text-[#1C1A17]">{{ $review->title }}</h2>
                                    <div class="mt-2 flex flex-wrap items-center gap-x-5 gap-y-1 text-sm text-[#8A7F72]">
                                        <span class="font-bold text-[#1C1A17]">{{ $reviewer }}</span>
                                        <span>{{ $reviewedAt->format('M d, Y') }}</span>
                                        @if($review->package_name)
                                            <span>{{ $review->package_name }}</span>
                                        @endif
                                    </div>

                                    <p class="mt-4 text-sm leading-relaxed text-[#1C1A17]">{{ $review->body }}</p>

                                    @if($review->caterer_reply)
                                        <div class="mt-4 rounded-xl border border-[#EDE4D8] bg-[#FDF6EE] px-4 py-3">
                                            <div class="text-xs font-black uppercase text-[#8A6D3F] mb-1">Your reply</div>
                                            <p class="text-sm text-[#1C1A17]">{{ $review->caterer_reply }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col sm:flex-row lg:flex-col gap-2 lg:w-40">
                                    <button type="button" @click="replying = !replying" class="w-full rounded-xl bg-[#E8642A] px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#F07C42]">
                                        Reply
                                    </button>

                                    @if($review->status === 'public')
                                        <form method="POST" action="{{ route('caterer.reviews.visibility', $review) }}" class="w-full">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="hidden">
                                            <button type="submit" class="w-full rounded-xl border border-[#EDE4D8] px-4 py-2.5 text-sm font-bold text-[#8A6D3F] transition-colors hover:bg-[#FDF6EE]">Hide</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('caterer.reviews.visibility', $review) }}" class="w-full">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="public">
                                            <button type="submit" class="w-full rounded-xl border border-[#EDE4D8] px-4 py-2.5 text-sm font-bold text-[#8A6D3F] transition-colors hover:bg-[#FDF6EE]">Make Public</button>
                                        </form>
                                    @endif

                                    <button type="button" @click="more = !more" class="w-full rounded-xl border border-[#EDE4D8] px-4 py-2.5 text-sm font-bold text-[#8A6D3F] transition-colors hover:bg-[#FDF6EE]">
                                        More
                                    </button>
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

                            <div x-show="more" x-transition class="mt-4 rounded-xl border border-[#EDE4D8] bg-[#FFFCF8] p-4">
                                <div class="grid gap-3 lg:grid-cols-[auto_minmax(0,1fr)] lg:items-start">
                                    @if($review->status === 'public')
                                        <form method="POST" action="{{ route('caterer.reviews.featured', $review) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_featured" value="{{ $review->is_featured ? 0 : 1 }}">
                                            <button type="submit" class="w-full rounded-xl border border-[#D7B79E] px-4 py-2.5 text-sm font-bold text-[#8A4A22] transition-colors hover:bg-[#FDF6EE] lg:w-auto">
                                                {{ $review->is_featured ? 'Remove Featured' : 'Feature Review' }}
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('caterer.reviews.report', $review) }}" class="grid gap-2 sm:grid-cols-[minmax(0,1fr)_auto]">
                                        @csrf
                                        <textarea name="report_reason" rows="1" class="w-full rounded-xl border border-[#EDE4D8] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8642A]" placeholder="Optional report note">{{ old('report_reason') }}</textarea>
                                        <button type="submit" class="rounded-xl bg-[#1C1A17] px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-black">Report</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-dashboard-layout>
