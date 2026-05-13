<x-dashboard-layout
    title="My Bookings - PlatePal"
    heading="Client Bookings"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        @include('client.partials.sidebar')
    </x-slot:sidebar>


    <div class="max-w-6xl mx-auto">
        <div class="mb-5">
            <h1 class="text-2xl font-black text-[#1C1A17] mb-1">My Bookings</h1>
            <p class="text-sm text-[#8A6D3F]">Manage and track all your catering bookings</p>
        </div>

        @php
            $tabs = [
                'all' => 'All Bookings',
                'confirmed' => 'Confirmed',
                'pending' => 'Pending',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ];

            $statusStyles = [
                'confirmed' => 'bg-emerald-100 text-emerald-800',
                'pending' => 'bg-amber-100 text-amber-800',
                'completed' => 'bg-blue-100 text-blue-800',
                'cancelled' => 'bg-red-100 text-red-800',
            ];
        @endphp

        <div class="bg-white rounded-xl border border-[#EDE4D8] shadow-sm p-1.5 mb-5 overflow-x-auto">
            <div class="grid grid-cols-5 min-w-[760px] gap-1.5">
                @foreach($tabs as $status => $label)
                    <a href="{{ route('client.bookings', $status === 'all' ? [] : ['status' => $status]) }}"
                        class="text-center rounded-lg px-3 py-2 text-sm font-bold transition-colors {{ $selectedStatus === $status ? 'bg-[#E8642A] text-white' : 'text-[#8A6D3F] hover:bg-[#FDF6EE]' }}">
                        {{ $label }}
                        <span class="{{ $selectedStatus === $status ? 'text-white/80' : 'text-[#8A7F72]' }} text-xs font-bold ml-1">({{ $statusCounts[$status] ?? 0 }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        @if($bookings->isEmpty())
            <div class="bg-white rounded-xl border border-[#EDE4D8] shadow-sm p-8 text-center">
                <div class="size-12 rounded-xl bg-[#FDF0EA] flex items-center justify-center mx-auto mb-3">
                    <svg class="size-6 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-lg font-black text-[#1C1A17] mb-1">No {{ $selectedStatus === 'all' ? '' : $selectedStatus }} bookings found</h2>
                <p class="text-sm text-[#8A7F72] mb-4">Browse caterers and start a booking request for your next event.</p>
                <a href="{{ route('client.browse') }}" class="inline-flex px-4 py-2 rounded-lg bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">Browse Caterers</a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($bookings as $booking)
                    @php
                        $status = strtolower($booking->status ?? 'pending');
                        $catererName = $booking->caterer->business_name ?? $booking->caterer->name ?? 'Selected caterer';
                        $packageName = $booking->selected_package_name;
                    @endphp

                    <article class="bg-white rounded-xl border border-[#EDE4D8] shadow-sm px-4 py-4">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div class="size-12 rounded-xl bg-[#FDF0EA] flex items-center justify-center shrink-0">
                                    <svg class="size-6 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-lg font-black text-[#1C1A17] leading-tight mb-0.5">{{ $booking->event_title }}</h2>
                                    <p class="text-sm text-[#8A6D3F] mb-2">Catered by {{ $catererName }}</p>
                                    <div class="flex flex-wrap items-center gap-x-5 gap-y-1 text-xs text-[#8A6D3F] font-medium">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="size-4 stroke-[#8A6D3F]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $booking->event_date->format('M d, Y') }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="size-4 stroke-[#8A6D3F]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                            {{ number_format($booking->guests) }} guests
                                        </span>
                                        @if($packageName)
                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="size-4 stroke-[#8A6D3F]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632A2.25 2.25 0 0117.378 20.25H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M3 7.5h18M9.75 11.25v5.25m4.5-5.25v5.25"/></svg>
                                                {{ $packageName }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex sm:flex-row lg:flex-col items-start sm:items-center lg:items-end gap-2 lg:ml-auto">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($status) }}
                                </span>
                                <a href="{{ route('client.bookings.show', $booking) }}" class="inline-flex justify-center px-4 py-2 rounded-lg bg-[#E8642A] text-white text-xs font-bold hover:bg-[#F07C42] transition-colors whitespace-nowrap">
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
