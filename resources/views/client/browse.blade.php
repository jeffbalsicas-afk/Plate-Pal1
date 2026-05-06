@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Browse Caterers – PlatePal"
    heading="Browse Caterers"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        <a href="{{ route('client.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Dashboard
            </div>
        </a>
        <a href="{{ route('client.browse') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#E8642A] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse Caterers
            </div>
        </a>
        <a href="{{ route('client.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                My Bookings
            </div>
        </a>
        <a href="#" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            Saved Caterers
        </a>
        <a href="#" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                Messages
            </div>
        </a>
        <a href="#" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            My Reviews
        </a>
    </x-slot:sidebar>
    <x-slot:sidebarFooter></x-slot:sidebarFooter>

    <div>
        {{-- Search & Title --}}
        <div class="mb-6">
            <h2 class="text-2xl font-black text-[#1C1A17] mb-1">Browse Caterers in Tagum City</h2>
            <p class="text-sm text-[#8A7F72] mb-4">Discover trusted local caterers for your special events</p>
            <form action="{{ route('client.browse') }}" method="GET" class="flex gap-3">
                <input type="text" name="search" placeholder="Search caterers or specialties..." value="{{ request('search') }}"
                    class="flex-1 px-[18px] py-3 rounded-xl bg-white border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A] transition-colors">
                <button type="button" class="px-5 py-3 rounded-xl border border-[#EDE4D8] text-[#1C1A17] text-sm font-medium hover:bg-[#FDF6EE] transition-colors flex items-center gap-2">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filters
                </button>
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                    Search
                </button>
            </form>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Filter Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8] h-fit sticky top-20">
                    <h3 class="text-lg font-black text-[#1C1A17] mb-4">Filter Results</h3>

                    {{-- Location --}}
                    <div class="mb-6">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Location</label>
                        <input type="text" placeholder="Enter barangay..."
                            class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-xs text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]">
                    </div>

                    {{-- Price Range --}}
                    <div class="mb-6 pb-6 border-b border-[#EDE4D8]">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Price Range</label>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">All Prices</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">Budget (₱200-400)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">Mid-Range (₱400-600)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">Premium (₱600+)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Cuisine Type --}}
                    <div class="mb-6 pb-6 border-b border-[#EDE4D8]">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Cuisine Type</label>
                        <input type="text" placeholder="Search cuisine..."
                            class="w-full px-3 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-xs text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]">
                    </div>

                    {{-- Minimum Rating --}}
                    <div class="mb-6">
                        <label class="text-sm font-bold text-[#1C1A17] mb-3 block">Minimum Rating</label>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">4.5+ Stars</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">4+ Stars</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">3.5+ Stars</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded">
                                <span class="text-[#8A7F72]">All Ratings</span>
                            </label>
                        </div>
                    </div>

                    <a href="#" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">Clear All Filters</a>
                </div>
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
                <p class="text-sm text-[#8A7F72]">Showing {{ $caterers->count() }} caterers</p>
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

</x-dashboard-layout>
