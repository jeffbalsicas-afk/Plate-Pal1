<x-layout title="Browse Caterers – PlatePal">

    <x-home.navbar class=""/>

    <div class="min-h-screen bg-[linear-gradient(#E3BC8C_0%,#F1DEC5_70%)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            {{-- Search & Title --}}
            <div class="mb-6">
                <h2 class="text-2xl font-black text-brand-dark mb-1">Browse Caterers in Tagum City</h2>
                <p class="text-sm text-brand-muted mb-4">Discover trusted local caterers for your special events</p>
                <form action="{{ route('browse.caterers') }}" method="GET" class="flex gap-3">
                    <input type="text" name="search" placeholder="🔍 Search caterers or specialties..." value="{{ request('search') }}"
                        class="flex-1 px-[18px] py-3 rounded-xl bg-white border border-brand-cream-dark text-sm text-brand-dark placeholder:text-brand-muted focus:outline-none focus:border-brand-orange transition-colors">
                    <button type="button" class="px-5 py-3 rounded-xl border bg-brand-white border-brand-cream-dark text-brand-dark text-sm font-medium hover:bg-brand-cream transition-colors flex items-center gap-2">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filters
                    </button>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-brand-orange text-white text-sm font-bold hover:bg-brand-orange-light transition-colors">
                        Search
                    </button>
                </form>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                {{-- Filter Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 border border-brand-cream-dark/50 h-fit sticky top-20">
                        <h3 class="text-lg font-black text-brand-dark mb-4">Filter Results</h3>

                        {{-- Location --}}
                        <div class="mb-6">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Location</label>
                            <input type="text" placeholder="Enter barangay..."
                                class="w-full px-3 py-2 rounded-lg bg-brand-white border border-brand-cream-dark text-xs text-brand-dark placeholder:text-brand-muted focus:outline-none focus:border-brand-orange">
                        </div>

                        {{-- Price Range --}}
                        <div class="mb-6 pb-6 border-b border-brand-cream-dark">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Price Range</label>
                            <div class="space-y-2 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">All Prices</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">Budget (₱200-400)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">Mid-Range (₱400-600)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">Premium (₱600+)</span>
                                </label>
                            </div>
                        </div>

                        {{-- Cuisine Type --}}
                        <div class="mb-6 pb-6 border-b border-brand-cream-dark">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Cuisine Type</label>
                            <input type="text" placeholder="Search cuisine..."
                                class="w-full px-3 py-2 rounded-lg bg-brand-white border border-brand-cream-dark text-xs text-brand-dark placeholder:text-brand-muted focus:outline-none focus:border-brand-orange">
                        </div>

                        {{-- Minimum Rating --}}
                        <div class="mb-6">
                            <label class="text-sm font-bold text-brand-dark mb-3 block">Minimum Rating</label>
                            <div class="space-y-2 text-xs">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">4.5+ Stars</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">4+ Stars</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">3.5+ Stars</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded">
                                    <span class="text-brand-muted">All Ratings</span>
                                </label>
                            </div>
                        </div>

                        <a href="#" class="text-xs font-bold text-brand-orange hover:text-brand-orange-light transition-colors">Clear All Filters</a>
                    </div>
                </div>

                {{-- Caterers Grid --}}
                <div class="lg:col-span-3">
            @if($caterers->isEmpty())
                <div class="bg-white rounded-2xl p-12 border border-brand-cream-dark/50 text-center">
                    <svg class="size-16 text-brand-muted/30 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="text-brand-muted font-medium">Showing 0 caterers</p>
                </div>
            @else
                <div class="space-y-4">
                    <p class="text-sm text-brand-muted">Showing {{ $caterers->count() }} caterers</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($caterers as $caterer)
                        <x-caterer-card :caterer="$caterer" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
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
