@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Bookings – PlatePal"
    heading="All Bookings"
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
        <a href="{{ route('admin.bookings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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

    <div class="bg-white rounded-2xl border border-[#EDE4D8] overflow-hidden">
        <div class="p-6 border-b border-[#EDE4D8]">
            <h2 class="text-lg font-black text-[#1C1A17]">All Bookings ({{ $totalBookings }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#EDE4D8] bg-[#FDF6EE]">
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Client</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Caterer</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Event</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Date</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Guests</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EDE4D8]">
                    @forelse($allBookings as $booking)
                    <tr class="hover:bg-[#FDF6EE] transition-colors">
                        <td class="px-6 py-4 font-medium text-[#1C1A17]">{{ $booking->user->name }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">{{ $booking->caterer->business_name ?? $booking->caterer->name }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">{{ $booking->event_title }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">{{ $booking->event_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">{{ $booking->guests }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $booking->status === 'confirmed' ? 'bg-[#EAF5E9] text-[#2E7D32]' : ($booking->status === 'pending' ? 'bg-[#FFF8E1] text-[#F57F17]' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-[#8A7F72]">No bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-[#EDE4D8]">
            {{ $allBookings->links() }}
        </div>
    </div>

</x-dashboard-layout>
