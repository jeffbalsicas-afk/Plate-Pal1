@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Admin Dashboard – PlatePal"
    heading="Admin Dashboard"
    :username="$user->name"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
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
        <a href="{{ route('admin.reports') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Reports
        </a>
    </x-slot:sidebar>

    <x-slot:sidebarFooter>
        <div class="border-t border-[#EDE4D8] pt-3 mt-3">
            <form action="{{ route('logout') }}" method="POST" style="display: none;" id="logout-form">
                @csrf
            </form>
            <a href="{{ route('logout') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </a>
        </div>
    </x-slot:sidebarFooter>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
        <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8]">
            <div class="w-11 h-11 rounded-xl bg-[#FDF6EE] flex items-center justify-center mb-3.5">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="#E8642A" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $totalUsers }}</div>
            <div class="text-xs text-[#8A7F72]">Total Users</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8]">
            <div class="w-11 h-11 rounded-xl bg-[#FDF6EE] flex items-center justify-center mb-3.5">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="#E8642A" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $totalCaterers }}</div>
            <div class="text-xs text-[#8A7F72]">Active Caterers</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8]">
            <div class="w-11 h-11 rounded-xl bg-[#FDF6EE] flex items-center justify-center mb-3.5">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="#E8642A" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $totalBookings }}</div>
            <div class="text-xs text-[#8A7F72]">Total Bookings</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8]">
            <div class="w-11 h-11 rounded-xl bg-[#FDF6EE] flex items-center justify-center mb-3.5">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="#E8642A" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">₱{{ number_format($totalRevenue / 1000, 0) }}K</div>
            <div class="text-xs text-[#8A7F72]">Total Revenue</div>
        </div>
    </div>

    {{-- Recent Users + Recent Caterers --}}
    <div class="grid lg:grid-cols-2 gap-5 mb-5">
        <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-black text-[#1C1A17]">Recent Users</h3>
            </div>
            @if($recentUsers->isEmpty())
                <p class="text-sm text-[#8A7F72] text-center py-6">No users yet.</p>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach($recentUsers as $u)
                    <div class="flex items-center justify-between px-3.5 py-3 border border-[#EDE4D8] rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#E8642A] text-white text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-[#1C1A17]">{{ $u->name }}</div>
                                <div class="text-xs text-[#8A7F72]">{{ $u->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-[#EAF5E9] text-[#2E7D32]">
                                Client
                            </span>
                            <div class="text-xs text-[#8A7F72] mt-1">{{ $u->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-black text-[#1C1A17]">Pending Caterer Approvals</h3>
                <span class="text-xs font-bold text-[#E8642A]">{{ $pendingCaterers->count() }} pending</span>
            </div>
            @if($pendingCaterers->isEmpty())
                <p class="text-sm text-[#8A7F72] text-center py-6">No pending approvals.</p>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach($pendingCaterers as $caterer)
                    <div class="flex items-center justify-between px-3.5 py-3 border border-[#EDE4D8] rounded-xl">
                        <div>
                            <div class="text-sm font-bold text-[#1C1A17]">{{ $caterer->business_name ?? $caterer->name }}</div>
                            <div class="text-xs text-[#8A7F72]">📍 {{ $caterer->barangay }} · {{ $caterer->cuisine ?? 'No cuisine set' }}</div>
                            <div class="text-xs text-[#8A7F72]">{{ $caterer->email }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.caterer.approve', $caterer) }}">
                                @csrf
                                <button class="px-3 py-1 rounded-lg bg-[#EAF5E9] text-[#2E7D32] text-xs font-bold hover:bg-green-200 transition-colors">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.caterer.reject', $caterer) }}">
                                @csrf
                                <button class="px-3 py-1 rounded-lg bg-red-50 text-red-500 text-xs font-bold hover:bg-red-100 transition-colors">Reject</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-black text-[#1C1A17]">Recent Bookings</h3>
        </div>
        @if($recentBookings->isEmpty())
            <p class="text-sm text-[#8A7F72] text-center py-6">No bookings yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#EDE4D8]">
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase pb-3">Client</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase pb-3">Caterer</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase pb-3">Event</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase pb-3">Date</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EDE4D8]">
                        @foreach($recentBookings as $b)
                        <tr>
                            <td class="py-3 font-medium text-[#1C1A17]">{{ $b->user->name }}</td>
                            <td class="py-3 text-[#8A7F72]">{{ $b->caterer->business_name ?? $b->caterer->name }}</td>
                            <td class="py-3 text-[#8A7F72]">{{ $b->event_title }}</td>
                            <td class="py-3 text-[#8A7F72]">{{ $b->event_date->format('M d, Y') }}</td>
                            <td class="py-3"><span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $b->status === 'confirmed' ? 'bg-[#EAF5E9] text-[#2E7D32]' : ($b->status === 'pending' ? 'bg-[#FFF8E1] text-[#F57F17]' : 'bg-gray-100 text-gray-600') }}">{{ ucfirst($b->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-dashboard-layout>
