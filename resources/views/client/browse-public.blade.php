<x-layout title="Browse Caterers - PlatePal">

    <x-home.navbar />

    <div class="min-h-screen bg-[linear-gradient(#E3BC8C_0%,#F1DEC5_70%)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-brand-dark mb-1">Browse Caterers in Tagum City</h2>
                <p class="text-sm text-brand-muted mb-4">Discover trusted local caterers for your special events</p>
                <form action="{{ route('browse.caterers') }}" method="GET" class="flex gap-3">
                    @foreach(['barangay', 'price_range', 'cuisine', 'rating', 'sort'] as $param)
                        @if(request($param))
                            <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                        @endif
                    @endforeach
                    <input type="text" name="search" placeholder="Search caterers or specialties..." value="{{ request('search') }}"
                        class="flex-1 px-[18px] py-3 rounded-xl bg-white border border-brand-cream-dark text-sm text-brand-dark placeholder:text-brand-muted focus:outline-none focus:border-brand-orange transition-colors">
                    <button type="submit" class="px-6 py-3 rounded-xl bg-brand-orange text-white text-sm font-bold hover:bg-brand-orange-light transition-colors">
                        Search
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-1">
                    <form id="filters" action="{{ route('browse.caterers') }}" method="GET" class="bg-white rounded-2xl p-6 border border-brand-cream-dark/50 h-fit sticky top-20">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">

                        <h3 class="text-lg font-black text-brand-dark mb-4">Filter Results</h3>

                        <div class="mb-6">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Location</label>
                            <select name="barangay" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-brand-white border border-brand-cream-dark text-xs text-brand-dark focus:outline-none focus:border-brand-orange">
                                <option value="">All Barangays</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay }}" @selected(request('barangay') === $barangay)>{{ $barangay }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6 pb-6 border-b border-brand-cream-dark">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Price Range</label>
                            <div class="space-y-2 text-xs">
                                @foreach([
                                    '' => 'All Prices',
                                    '200-400' => 'Budget (PHP 200-400)',
                                    '400-600' => 'Mid-Range (PHP 400-600)',
                                    '600-9999' => 'Premium (PHP 600+)',
                                ] as $value => $label)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="price_range" value="{{ $value }}" onchange="this.form.submit()" @checked(request('price_range', '') === $value) class="w-4 h-4 rounded">
                                        <span class="text-brand-muted">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b border-brand-cream-dark">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Cuisine Type</label>
                            <select name="cuisine" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-brand-white border border-brand-cream-dark text-xs text-brand-dark focus:outline-none focus:border-brand-orange">
                                <option value="">All Cuisines</option>
                                @foreach($cuisines as $cuisine)
                                    <option value="{{ $cuisine }}" @selected(request('cuisine') === $cuisine)>{{ $cuisine }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Minimum Rating</label>
                            <div class="space-y-2 text-xs">
                                @foreach([
                                    '' => 'All Ratings',
                                    '4.5' => '4.5+ Stars',
                                    '4' => '4+ Stars',
                                    '3.5' => '3.5+ Stars',
                                ] as $value => $label)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $value }}" onchange="this.form.submit()" @checked(request('rating', '') === $value) class="w-4 h-4 rounded">
                                        <span class="text-brand-muted">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('browse.caterers') }}" class="text-xs font-bold text-brand-orange hover:text-brand-orange-light transition-colors">Clear All Filters</a>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-3">
                    @if($caterers->isEmpty())
                        <div class="bg-white rounded-2xl p-12 border border-brand-cream-dark/50 text-center">
                            <svg class="size-16 text-brand-muted/30 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <p class="text-brand-muted font-medium">No caterers match the current filters.</p>
                            <a href="{{ route('browse.caterers') }}" class="inline-block mt-4 text-sm font-bold text-brand-orange hover:text-brand-orange-light">Clear filters</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <p class="text-sm text-brand-muted">
                                    Showing {{ $caterers->firstItem() }}-{{ $caterers->lastItem() }} of {{ $caterers->total() }} caterers
                                </p>
                                <form action="{{ route('browse.caterers') }}" method="GET" class="flex items-center gap-2">
                                    @foreach(['search', 'barangay', 'price_range', 'cuisine', 'rating'] as $param)
                                        @if(request($param))
                                            <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                                        @endif
                                    @endforeach
                                    <label class="text-xs text-brand-muted">Sort by:</label>
                                    <select name="sort" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg bg-white border border-brand-cream-dark text-xs text-brand-dark focus:outline-none focus:border-brand-orange">
                                        <option value="rating_high" @selected(request('sort', 'rating_high') === 'rating_high')>Highest Rated</option>
                                        <option value="rating_low" @selected(request('sort') === 'rating_low')>Lowest Rated</option>
                                        <option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to High</option>
                                        <option value="price_high" @selected(request('sort') === 'price_high')>Price: High to Low</option>
                                    </select>
                                </form>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                @foreach($caterers as $caterer)
                                    <x-caterer-card :caterer="$caterer" />
                                @endforeach
                            </div>

                            <div class="flex justify-center items-center gap-2 mt-6">
                                {{ $caterers->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-home.footer />

</x-layout>
