@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Browse Caterers - PlatePal"
    heading="Browse Caterers"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        @include('client.partials.sidebar')
    </x-slot:sidebar>

    {{-- Stats --}}
    <x-client-stats 
        :activeBookings="$activeBookings" 
        :savedCaterersCount="count($savedCatererIds ?? [])" 
        :unreadMessages="$unreadMessages" 
        :completedEvents="$completedEvents" 
    />

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-green-700 hover:text-green-900">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div x-data="{
        showRemoveSavedModal: false,
        removeTarget: { formId: '', name: '' },
        openRemoveSavedModal(formId, name) {
            this.removeTarget = { formId, name };
            this.showRemoveSavedModal = true;
        },
        closeRemoveSavedModal() {
            this.showRemoveSavedModal = false;
            this.removeTarget = { formId: '', name: '' };
        }
    }" @open-remove-modal.window="openRemoveSavedModal($event.detail.formId, $event.detail.name)">
        {{-- Search & Title --}}
        <div class="mb-6">
            <h2 class="text-2xl font-black text-[#1C1A17] mb-1">Browse Caterers in Tagum City</h2>
            <p class="text-sm text-[#8A7F72] mb-4">Discover trusted local caterers for your special events</p>
            <form id="filterForm" action="{{ route('client.browse') }}" method="GET" class="flex gap-3">
                @foreach(['barangay', 'price_range', 'cuisine', 'rating', 'sort'] as $param)
                    @if(request($param))
                        <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                    @endif
                @endforeach
                <input type="text" name="search" placeholder="Search caterers or specialties..." value="{{ request('search') }}"
                    class="flex-1 px-[18px] py-3 rounded-xl bg-white border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A] transition-colors">
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                    Search
                </button>
            </form>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Filter Sidebar --}}
            <div class="lg:col-span-1">
                <form action="{{ route('client.browse') }}" method="GET" class="bg-white rounded-2xl p-6 border border-[#EDE4D8] h-fit sticky top-20">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <h3 class="text-lg font-black text-[#1C1A17] mb-4">Filter Results</h3>

                    {{-- Location --}}
                    <div class="mb-6">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Location</label>
                        <select name="barangay" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-xs text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                            <option value="">All Barangays</option>
                            @foreach($barangays as $barangay)
                                @if($barangay)
                                    <option value="{{ $barangay }}" {{ request('barangay') == $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div class="mb-6 pb-6 border-b border-[#EDE4D8]">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Price Range (per head)</label>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" value="" onchange="this.form.submit()" {{ !request('price_range') ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">All Prices</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" value="200-400" onchange="this.form.submit()" {{ request('price_range') == '200-400' ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">Budget (PHP 200-400)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" value="400-600" onchange="this.form.submit()" {{ request('price_range') == '400-600' ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">Mid-Range (PHP 400-600)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="price_range" value="600-9999" onchange="this.form.submit()" {{ request('price_range') == '600-9999' ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">Premium (PHP 600+)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Cuisine Type --}}
                    <div class="mb-6 pb-6 border-b border-[#EDE4D8]">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Cuisine Type</label>
                        <select name="cuisine" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-xs text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                            <option value="">All Cuisines</option>
                            @foreach($cuisines as $cuisine)
                                @if($cuisine)
                                    <option value="{{ $cuisine }}" {{ request('cuisine') == $cuisine ? 'selected' : '' }}>{{ $cuisine }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Minimum Rating --}}
                    <div class="mb-6">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Minimum Rating</label>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="rating" value="" onchange="this.form.submit()" {{ !request('rating') ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">All Ratings</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="rating" value="4.5" onchange="this.form.submit()" {{ request('rating') == '4.5' ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">4.5+ Stars</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="rating" value="4" onchange="this.form.submit()" {{ request('rating') == '4' ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">4+ Stars</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="rating" value="3.5" onchange="this.form.submit()" {{ request('rating') == '3.5' ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-[#8A7F72]">3.5+ Stars</span>
                            </label>
                        </div>
                    </div>

                    <a href="{{ route('client.browse') }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">Clear All Filters</a>
                </form>
            </div>

            {{-- Caterers Grid --}}
            <div class="lg:col-span-3">
        @if($caterers->isEmpty())
            <div class="bg-white rounded-2xl p-12 border border-[#EDE4D8] text-center">
                <svg class="size-16 text-[#D3CCBE] mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-[#8A7F72] font-medium">Showing 0 caterers</p>
            </div>
        @else
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-[#8A7F72]">Showing {{ $caterers->count() }} of {{ $caterers->total() }} caterers</p>
                    <form action="{{ route('client.browse') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="barangay" value="{{ request('barangay') }}">
                        <input type="hidden" name="price_range" value="{{ request('price_range') }}">
                        <input type="hidden" name="cuisine" value="{{ request('cuisine') }}">
                        <input type="hidden" name="rating" value="{{ request('rating') }}">
                        <label class="text-xs text-[#8A7F72]">Sort by:</label>
                        <select name="sort" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg bg-white border border-[#EDE4D8] text-xs text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                            <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Lowest Rated</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </form>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($caterers as $caterer)
                        <x-caterer-card :caterer="$caterer" :savedCatererIds="$savedCatererIds ?? []" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center items-center gap-2 mt-6">
                    {{ $caterers->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
            </div>
        </div>
        
        @include('client.partials.remove-saved-modal')
    </div>

</x-dashboard-layout>
