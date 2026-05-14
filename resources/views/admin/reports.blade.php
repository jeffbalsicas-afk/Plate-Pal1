@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Reports – PlatePal"
    heading="Reports & Analytics"
    :username="$user->name"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Overview
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Users
        </a>
        <a href="{{ route('admin.featured-caterers.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Featured Caterers
        </a>
        <a href="{{ route('admin.bookings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Bookings
        </a>
        <a href="{{ route('admin.reports') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Reports
        </a>
    </x-slot:sidebar>

    <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8] mb-6">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex items-center gap-4">
            <div class="flex-1">
                <label class="block text-xs font-bold text-[#8A7F72] uppercase mb-2">Filter by Caterer</label>
                <select name="caterer_id" class="w-full px-4 py-2.5 border border-[#EDE4D8] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E8642A] text-sm">
                    <option value="">All Caterers (Platform-wide)</option>
                    @foreach($allCaterers as $caterer)
                        <option value="{{ $caterer->id }}" {{ request('caterer_id') == $caterer->id ? 'selected' : '' }}>
                            {{ $caterer->business_name ?? $caterer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="pt-6">
                <button type="submit" class="px-6 py-2.5 bg-[#E8642A] text-white rounded-lg font-bold text-sm hover:bg-[#D55A24] transition-colors">
                    Apply Filter
                </button>
            </div>
            @if(request('caterer_id'))
                <div class="pt-6">
                    <a href="{{ route('admin.reports') }}" class="px-6 py-2.5 bg-[#8A7F72] text-white rounded-lg font-bold text-sm hover:bg-[#6B5F54] transition-colors">
                        Clear
                    </a>
                </div>
            @endif
        </form>
        @if($selectedCaterer)
            <div class="mt-4 p-3 bg-[#FDF6EE] rounded-lg">
                <p class="text-sm text-[#8A7F72]">Showing stats for: <span class="font-bold text-[#E8642A]">{{ $selectedCaterer->business_name ?? $selectedCaterer->name }}</span></p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#8A7F72] font-bold uppercase">Total Revenue</p>
                    <p class="text-3xl font-black text-[#1C1A17] mt-2">₱{{ number_format($totalRevenue, 0) }}</p>
                </div>
                <svg class="size-12 text-[#E8642A] opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#8A7F72] font-bold uppercase">Total Bookings</p>
                    <p class="text-3xl font-black text-[#1C1A17] mt-2">{{ $totalBookings }}</p>
                </div>
                <svg class="size-12 text-[#E8642A] opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7h-2v5h2zm-4-2h-2v7h2zm8-1h-2v8h2z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#8A7F72] font-bold uppercase">Completed Events</p>
                    <p class="text-3xl font-black text-[#1C1A17] mt-2">{{ $completedBookings }}</p>
                </div>
                <svg class="size-12 text-[#2E7D32] opacity-20" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#8A7F72] font-bold uppercase">Avg Rating</p>
                    <p class="text-3xl font-black text-[#1C1A17] mt-2">{{ number_format($avgRating, 1) }}/5</p>
                </div>
                <svg class="size-12 text-[#FBBF24] opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <h3 class="text-lg font-black text-[#1C1A17] mb-4">Bookings by Status</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-bold text-[#1C1A17]">Confirmed</span>
                        <span class="text-sm font-bold text-[#2E7D32]">{{ $confirmedBookings }}</span>
                    </div>
                    <div class="w-full bg-[#EDE4D8] rounded-full h-2">
                        <div class="bg-[#2E7D32] h-2 rounded-full" style="width: {{ ($confirmedBookings / max($totalBookings, 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-bold text-[#1C1A17]">Pending</span>
                        <span class="text-sm font-bold text-[#F57F17]">{{ $pendingBookings }}</span>
                    </div>
                    <div class="w-full bg-[#EDE4D8] rounded-full h-2">
                        <div class="bg-[#F57F17] h-2 rounded-full" style="width: {{ ($pendingBookings / max($totalBookings, 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-bold text-[#1C1A17]">Cancelled</span>
                        <span class="text-sm font-bold text-red-500">{{ $cancelledBookings }}</span>
                    </div>
                    <div class="w-full bg-[#EDE4D8] rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ ($cancelledBookings / max($totalBookings, 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <h3 class="text-lg font-black text-[#1C1A17] mb-4">Top Caterers</h3>
            @if($selectedCaterer)
                <p class="text-sm text-[#8A7F72] text-center py-4">Top caterers only shown in platform-wide view</p>
            @else
                <div class="space-y-3">
                    @forelse($topCaterers as $caterer)
                    <div class="flex items-center justify-between p-3 bg-[#FDF6EE] rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-[#E8642A] text-white text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(substr($caterer->business_name ?? $caterer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#1C1A17]">{{ $caterer->business_name ?? $caterer->name }}</p>
                                <p class="text-xs text-[#8A7F72]">{{ $caterer->bookings_count }} bookings</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="size-4 fill-[#FBBF24]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-xs font-bold text-[#1C1A17]">{{ number_format($caterer->rating, 1) }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-[#8A7F72] text-center py-4">No caterers yet.</p>
                    @endforelse
                </div>
            @endif
        </div>
    </div>

</x-dashboard-layout>
