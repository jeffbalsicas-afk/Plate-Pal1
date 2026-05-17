@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Earnings – PlatePal"
    heading="Earnings"
    :username="$displayName"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        <a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            Dashboard
        </a>
        <a href="{{ route('caterer.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Bookings
            </div>
            @if($pendingBookings > 0)
                <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $pendingBookings }}</span>
            @endif
        </a>
        <a href="{{ route('caterer.menu-pricing') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Menu & Pricing
        </a>
        <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Messages
            </div>
            @if($unreadMessages > 0)
                <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
            @endif
        </a>
        <a href="{{ route('caterer.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            Reviews
        </a>
        <a href="{{ route('caterer.earnings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Earnings
        </a>
    </x-slot:sidebar>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
        <div class="relative overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white p-5 shadow-sm">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#E8642A]"></div>
            <div class="mb-4 flex items-start justify-between gap-3">
                <div class="flex size-11 items-center justify-center rounded-xl bg-[#FEF3EC]">
                    <svg class="size-[22px] stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="rounded-full bg-[#FEF3EC] px-2.5 py-1 text-[11px] font-bold text-[#E8642A]">Lifetime</span>
            </div>
            <div class="text-[32px] font-black leading-none text-[#1C1A17]">&#8369;{{ number_format($totalEarnings, 0) }}</div>
            <div class="mt-1 text-sm font-black text-[#1C1A17]">Total Earnings</div>
            <div class="mt-2 text-xs leading-4 text-[#8A7F72]">Completed booking revenue</div>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white p-5 shadow-sm">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#2E7D32]"></div>
            <div class="mb-4 flex items-start justify-between gap-3">
                <div class="flex size-11 items-center justify-center rounded-xl bg-[#EAF5E9]">
                    <svg class="size-[22px] stroke-[#2E7D32]" fill="none" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="rounded-full bg-[#EAF5E9] px-2.5 py-1 text-[11px] font-bold text-[#2E7D32]">Current</span>
            </div>
            <div class="text-[32px] font-black leading-none text-[#1C1A17]">&#8369;{{ number_format($monthlyEarnings, 0) }}</div>
            <div class="mt-1 text-sm font-black text-[#1C1A17]">This Month</div>
            <div class="mt-2 text-xs leading-4 text-[#8A7F72]">Revenue from recent events</div>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white p-5 shadow-sm sm:col-span-2 lg:col-span-1">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#8A6D3F]"></div>
            <div class="mb-4 flex items-start justify-between gap-3">
                <div class="flex size-11 items-center justify-center rounded-xl bg-[#F6EFE7]">
                    <svg class="size-[22px] stroke-[#8A6D3F]" fill="none" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="rounded-full bg-[#F6EFE7] px-2.5 py-1 text-[11px] font-bold text-[#8A6D3F]">Done</span>
            </div>
            <div class="text-[32px] font-black leading-none text-[#1C1A17]">{{ $completedBookings }}</div>
            <div class="mt-1 text-sm font-black text-[#1C1A17]">Completed Events</div>
            <div class="mt-2 text-xs leading-4 text-[#8A7F72]">Finished catering jobs</div>
        </div>
    </div>

    {{-- Earnings History --}}
    <div class="bg-white rounded-2xl border border-[#EDE4D8] overflow-hidden">
        <div class="p-6 border-b border-[#EDE4D8]">
            <h2 class="text-lg font-black text-[#1C1A17]">Earnings History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#EDE4D8] bg-[#FDF6EE]">
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Client</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Event</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Date</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Package</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Guests</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Price</th>
                        <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Total Earnings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EDE4D8]">
                    @forelse($earningsHistory as $booking)
                    @php
                        $bookingTotal = $booking->estimated_total;
                    @endphp
                    <tr class="hover:bg-[#FDF6EE] transition-colors">
                        <td class="px-6 py-4 font-medium text-[#1C1A17]">{{ $booking->user->name }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">{{ $booking->event_title }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">{{ $booking->event_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">
                            {{ $booking->package_name ?? 'Custom booking' }}
                            @if($booking->bookingItems->count() > 0)
                                <span class="text-xs text-[#E8642A]">+ {{ $booking->bookingItems->count() }} items</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[#8A7F72]">{{ number_format($booking->guests) }}</td>
                        <td class="px-6 py-4 text-[#8A7F72]">
                            @if($booking->package_price)
                                ₱{{ number_format($booking->package_price, 2) }}
                            @elseif($booking->client_budget)
                                ₱{{ number_format($booking->client_budget, 2) }}
                            @else
                                Custom
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-[#E8642A]">
                            ₱{{ number_format($bookingTotal, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-[#8A7F72]">No completed bookings yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-[#EDE4D8]">
            {{ $earningsHistory->links() }}
        </div>
    </div>

</x-dashboard-layout>
