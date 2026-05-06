<x-dashboard-layout
    title="My Bookings - PlatePal"
    heading="Client Dashboard"
    :username="$user->name"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('client.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Dashboard
            </div>
        </a>
        <a href="{{ route('client.browse') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse Caterers
            </div>
        </a>
        <a href="{{ route('client.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg  text-[#E8642A] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                My Bookings
            </div>
            @if(($statusCounts['all'] ?? 0) > 0)
                <span class="bg-[#E8642A] text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $statusCounts['all'] }}</span>
            @endif
        </a>
        <a href="{{ route('client.saved-caterers') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            Saved Caterers
        </a>
        <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                Messages
            </div>
            @if($unreadMessages > 0)
                <span class="bg-[#E8642A] text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
            @endif
        </a>
        <a href="{{ route('client.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 011.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            My Reviews
        </a>
    </x-slot:sidebar>


    <div class="max-w-6xl mx-auto">
        <div class="mb-7">
            <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17] mb-2">My Bookings</h1>
            <p class="text-base sm:text-lg text-[#8A6D3F]">Manage and track all your catering bookings</p>
        </div>

        @php
            $tabs = [
                'all' => 'All Bookings',
                'confirmed' => 'Confirmed',
                'pending' => 'Pending',
                'completed' => 'Completed',
            ];

            $statusStyles = [
                'confirmed' => 'bg-emerald-100 text-emerald-800',
                'pending' => 'bg-amber-100 text-amber-800',
                'completed' => 'bg-blue-100 text-blue-800',
                'cancelled' => 'bg-red-100 text-red-800',
            ];
        @endphp

        <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-lg p-2 mb-7 overflow-x-auto">
            <div class="grid grid-cols-4 min-w-[620px] gap-2">
                @foreach($tabs as $status => $label)
                    <a href="{{ route('client.bookings', $status === 'all' ? [] : ['status' => $status]) }}"
                        class="text-center rounded-xl px-4 py-3 text-sm sm:text-base font-bold transition-colors {{ $selectedStatus === $status ? 'bg-[#E8642A] text-white shadow-sm' : 'text-[#8A6D3F] hover:bg-[#FDF6EE]' }}">
                        {{ $label }}
                        <span class="{{ $selectedStatus === $status ? 'text-white/80' : 'text-[#8A7F72]' }} text-xs font-bold ml-1">({{ $statusCounts[$status] ?? 0 }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        @if($bookings->isEmpty())
            <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-10 text-center">
                <div class="size-14 rounded-2xl bg-[#FDF0EA] flex items-center justify-center mx-auto mb-4">
                    <svg class="size-7 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-xl font-black text-[#1C1A17] mb-2">No {{ $selectedStatus === 'all' ? '' : $selectedStatus }} bookings found</h2>
                <p class="text-sm text-[#8A7F72] mb-5">Browse caterers and start a booking request for your next event.</p>
                <a href="{{ route('client.browse') }}" class="inline-flex px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">Browse Caterers</a>
            </div>
        @else
            <div class="space-y-5">
                @foreach($bookings as $booking)
                    @php
                        $status = strtolower($booking->status ?? 'pending');
                        $catererName = $booking->caterer->business_name ?? $booking->caterer->name ?? 'Selected caterer';
                    @endphp

                    <article class="bg-white rounded-2xl border border-[#EDE4D8] shadow-lg px-5 sm:px-7 py-6">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-5 lg:gap-7">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <div class="size-16 rounded-2xl bg-[#FDF0EA] flex items-center justify-center shrink-0">
                                    <svg class="size-8 stroke-[#E8642A]" fill="none" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-xl sm:text-2xl font-black text-[#1C1A17] leading-tight mb-1">{{ $booking->event_title }}</h2>
                                    <p class="text-base sm:text-lg text-[#8A6D3F] mb-4">Catered by {{ $catererName }}</p>
                                    <div class="flex flex-wrap items-center gap-x-7 gap-y-2 text-sm sm:text-base text-[#8A6D3F] font-medium">
                                        <span class="inline-flex items-center gap-2">
                                            <svg class="size-5 stroke-[#8A6D3F]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $booking->event_date->format('M d, Y') }}
                                        </span>
                                        <span class="inline-flex items-center gap-2">
                                            <svg class="size-5 stroke-[#8A6D3F]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                            {{ number_format($booking->guests) }} guests
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex sm:flex-row lg:flex-col items-start sm:items-center lg:items-end gap-3 lg:ml-auto">
                                <span class="px-4 py-2 rounded-xl text-sm font-bold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($status) }}
                                </span>
                                <a href="{{ route('client.bookings.show', $booking) }}" class="inline-flex justify-center px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors whitespace-nowrap">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-dashboard-layout>
