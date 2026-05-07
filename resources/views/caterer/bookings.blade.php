<x-dashboard-layout
    title="Caterer Bookings - PlatePal"
    heading="Caterer Bookings"
    :username="$displayName"
    :usersub="$user->barangay ?? ''"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            Dashboard
        </a>
        <a href="{{ route('caterer.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Bookings
            </div>
            @if(($statusCounts['pending'] ?? 0) > 0)
                <span class="bg-red-500 text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $statusCounts['pending'] }}</span>
            @endif
        </a>
        <a href="{{ route('caterer.menu-pricing') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Menu & Pricing
        </a>
        <a href="{{ route('caterer.messages') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Messages
            </div>
            @if($unreadMessages > 0)
                <span class="bg-red-500 text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
            @endif
        </a>
        <a href="{{ route('caterer.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 011.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            Reviews
        </a>
        <a href="{{ route('caterer.earnings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Earnings
        </a>
    </x-slot:sidebar>

    @php
        $tabs = [
            'all' => 'All',
            'pending' => 'Requests',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        $statusStyles = [
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'completed' => 'bg-blue-100 text-blue-800 border-blue-200',
            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
        ];

        $activeBookings = ($statusCounts['pending'] ?? 0) + ($statusCounts['confirmed'] ?? 0);
    @endphp

    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17] mb-2">Bookings</h1>
                <p class="text-base text-[#8A6D3F]">Review new requests, prepare confirmed events, and track completed catering jobs.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2.5">
                <button type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-[#EDE4D8] bg-white text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">
                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 12h10M10 20h4"/></svg>
                    Filter
                </button>
                <button type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                    <svg class="size-4 stroke-white" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Calendar View
                </button>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-3.5 mb-6">
            <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8] shadow-sm">
                <div class="text-xs font-bold uppercase text-[#8A7F72] mb-2">Active events</div>
                <div class="text-3xl font-black text-[#1C1A17] leading-none">{{ $activeBookings }}</div>
                <div class="text-xs text-[#8A7F72] mt-2">Pending and confirmed bookings</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8] shadow-sm">
                <div class="text-xs font-bold uppercase text-[#8A7F72] mb-2">Pending requests</div>
                <div class="text-3xl font-black text-[#E8642A] leading-none">{{ $statusCounts['pending'] ?? 0 }}</div>
                <div class="text-xs text-[#8A7F72] mt-2">Need your response</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8] shadow-sm">
                <div class="text-xs font-bold uppercase text-[#8A7F72] mb-2">Guests to serve</div>
                <div class="text-3xl font-black text-[#1C1A17] leading-none">{{ number_format($totalGuests) }}</div>
                <div class="text-xs text-[#8A7F72] mt-2">From active bookings</div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8] shadow-sm">
                <div class="text-xs font-bold uppercase text-[#8A7F72] mb-2">Next event</div>
                <div class="text-xl font-black text-[#1C1A17] leading-tight">
                    {{ $nextBooking ? $nextBooking->event_date->format('M d') : 'None yet' }}
                </div>
                <div class="text-xs text-[#8A7F72] mt-2 truncate">{{ $nextBooking->event_title ?? 'No active events scheduled' }}</div>
            </div>
        </div>

        <div class="grid xl:grid-cols-[1fr_340px] gap-6">
            <section class="min-w-0">
                <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-2 mb-5 overflow-x-auto">
                    <div class="grid grid-cols-5 min-w-[680px] gap-2">
                        @foreach($tabs as $status => $label)
                            <a href="{{ route('caterer.bookings', $status === 'all' ? [] : ['status' => $status]) }}"
                                class="text-center rounded-xl px-3 py-3 text-sm font-bold transition-colors {{ $selectedStatus === $status ? 'bg-[#E8642A] text-white shadow-sm' : 'text-[#8A6D3F] hover:bg-[#FDF6EE]' }}">
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
                        <h2 class="text-xl font-black text-[#1C1A17] mb-2">No {{ $selectedStatus === 'all' ? '' : $selectedStatus }} bookings</h2>
                        <p class="text-sm text-[#8A7F72]">Bookings from clients will appear here once they send a request.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($bookings as $booking)
                            @php
                                $status = strtolower($booking->status ?? 'pending');
                                $clientName = $booking->user->name ?? 'Client';
                                $clientInitials = strtoupper(substr($clientName, 0, 1) . (str_contains($clientName, ' ') ? substr($clientName, strpos($clientName, ' ') + 1, 1) : ''));
                            @endphp

                            <article class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm px-5 sm:px-6 py-5">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        <div class="size-12 rounded-xl bg-[#E8642A] text-white text-sm font-black flex items-center justify-center shrink-0">
                                            {{ $clientInitials }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                                <h2 class="text-xl font-black text-[#1C1A17] leading-tight">{{ $booking->event_title }}</h2>
                                                <span class="px-2.5 py-1 rounded-full border text-xs font-bold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm font-bold text-[#8A6D3F] mb-3">{{ $clientName }}</p>
                                            <div class="grid sm:grid-cols-3 gap-2.5 text-sm text-[#8A7F72]">
                                                <span class="inline-flex items-center gap-2">
                                                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ $booking->event_date->format('M d, Y') }}
                                                </span>
                                                <span class="inline-flex items-center gap-2">
                                                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                                    {{ number_format($booking->guests) }} guests
                                                </span>
                                                <span class="inline-flex items-center gap-2">
                                                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Requested {{ $booking->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row lg:flex-col gap-2 lg:w-36">
                                        @if($status === 'pending')
                                            <form method="POST" action="{{ route('bookings.accept', $booking) }}" class="w-full">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">Accept</button>
                                            </form>
                                            <button type="button" onclick="document.getElementById('declineModal{{ $booking->id }}').showModal()" class="w-full px-4 py-2.5 rounded-xl border border-[#EDE4D8] text-[#8A6D3F] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">Decline</button>
                                        @elseif($status === 'confirmed')
                                            <form method="POST" action="{{ route('bookings.complete', $booking) }}" class="w-full">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-[#1C1A17] text-white text-sm font-bold hover:bg-black transition-colors">Mark Complete</button>
                                            </form>
                                            <button type="button" class="w-full px-4 py-2.5 rounded-xl border border-[#EDE4D8] text-[#8A6D3F] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">Message</button>
                                        @else
                                            <button type="button" class="w-full px-4 py-2.5 rounded-xl border border-[#EDE4D8] text-[#8A6D3F] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">View</button>
                                        @endif
                                    </div>

                                    @if($status === 'pending')
                                        <dialog id="declineModal{{ $booking->id }}" class="rounded-2xl backdrop:bg-black/50 w-full max-w-md">
                                            <form method="POST" action="{{ route('bookings.decline', $booking) }}" class="p-6">
                                                @csrf
                                                <h2 class="text-xl font-black text-[#1C1A17] mb-4">Decline Booking</h2>
                                                <p class="text-sm text-[#8A6D3F] mb-4">Tell the client why you're declining this booking request.</p>
                                                <textarea name="reason" rows="4" placeholder="Optional reason..." class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A] mb-4"></textarea>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="document.getElementById('declineModal{{ $booking->id }}').close()" class="flex-1 px-4 py-3 rounded-xl border border-[#EDE4D8] text-[#8A6D3F] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">Cancel</button>
                                                    <button type="submit" class="flex-1 px-4 py-3 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors">Decline</button>
                                                </div>
                                            </form>
                                        </dialog>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <aside class="space-y-5">
                <div class="bg-[#1C1A17] rounded-2xl p-5 text-white shadow-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-black">Today</h2>
                        <span class="text-xs font-bold text-white/70">{{ now()->format('M d, Y') }}</span>
                    </div>
                    @if($nextBooking)
                        <div class="rounded-xl bg-white/10 p-4 border border-white/10">
                            <div class="text-xs font-bold uppercase text-white/60 mb-2">Next active booking</div>
                            <div class="text-xl font-black leading-tight mb-1">{{ $nextBooking->event_title }}</div>
                            <div class="text-sm text-white/70">{{ $nextBooking->event_date->format('M d, Y') }} - {{ number_format($nextBooking->guests) }} guests</div>
                        </div>
                    @else
                        <p class="text-sm text-white/70">No active booking is scheduled yet.</p>
                    @endif
                </div>

                <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-5">
                    <h2 class="text-lg font-black text-[#1C1A17] mb-4">Booking Flow</h2>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="size-7 rounded-full bg-amber-100 text-amber-700 text-xs font-black flex items-center justify-center shrink-0">1</div>
                            <div>
                                <div class="text-sm font-black text-[#1C1A17]">Review request</div>
                                <div class="text-xs text-[#8A7F72]">Check date, guest count, and client details.</div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="size-7 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black flex items-center justify-center shrink-0">2</div>
                            <div>
                                <div class="text-sm font-black text-[#1C1A17]">Confirm booking</div>
                                <div class="text-xs text-[#8A7F72]">Move accepted events into your prep list.</div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="size-7 rounded-full bg-blue-100 text-blue-700 text-xs font-black flex items-center justify-center shrink-0">3</div>
                            <div>
                                <div class="text-sm font-black text-[#1C1A17]">Complete event</div>
                                <div class="text-xs text-[#8A7F72]">Mark the booking done after service.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-dashboard-layout>
