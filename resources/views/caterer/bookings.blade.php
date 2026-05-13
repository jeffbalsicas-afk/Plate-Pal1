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
            'pending' => [
                'label' => 'Pending',
                'badge' => 'bg-[#FFF8E1] text-[#B26A00] border-[#F3D68B]',
                'dot' => 'bg-[#B26A00]',
                'rail' => 'bg-[#E8642A]',
                'soft' => 'bg-[#FEF3EC] text-[#E8642A]',
            ],
            'confirmed' => [
                'label' => 'Confirmed',
                'badge' => 'bg-[#EAF5E9] text-[#2E7D32] border-[#CDE8CC]',
                'dot' => 'bg-[#2E7D32]',
                'rail' => 'bg-[#2E7D32]',
                'soft' => 'bg-[#EAF5E9] text-[#2E7D32]',
            ],
            'completed' => [
                'label' => 'Completed',
                'badge' => 'bg-[#EAF2FF] text-[#1F5FA8] border-[#CFE0F6]',
                'dot' => 'bg-[#1F5FA8]',
                'rail' => 'bg-[#1F5FA8]',
                'soft' => 'bg-[#EAF2FF] text-[#1F5FA8]',
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'badge' => 'bg-red-50 text-red-700 border-red-200',
                'dot' => 'bg-red-600',
                'rail' => 'bg-red-500',
                'soft' => 'bg-red-50 text-red-700',
            ],
        ];

    @endphp

    <div class="max-w-7xl mx-auto space-y-5">
        <section class="group relative overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white p-5 sm:p-6 shadow-sm">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#926b01]"></div>
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                <div class="min-w-0">
                    <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17]">Bookings</h1>
                    <p class="mt-2 max-w-2xl text-sm sm:text-base text-[#8A6D3F]">Review client requests, keep confirmed events visible, and move completed jobs out of the prep queue.</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-y-4 sm:divide-x sm:divide-[#EDE4D8] xl:min-w-[560px]">
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Requests</div>
                        <div class="mt-1 text-2xl font-black text-[#E8642A]">{{ number_format($statusCounts['pending'] ?? 0) }}</div>
                        <div class="text-xs text-[#8A7F72]">Need reply</div>
                    </div>
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Confirmed</div>
                        <div class="mt-1 text-2xl font-black text-[#2E7D32]">{{ number_format($statusCounts['confirmed'] ?? 0) }}</div>
                        <div class="text-xs text-[#8A7F72]">Scheduled</div>
                    </div>
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Guests</div>
                        <div class="mt-1 text-2xl font-black text-[#1C1A17]">{{ number_format($totalGuests) }}</div>
                        <div class="text-xs text-[#8A7F72]">To serve</div>
                    </div>
                    <div class="sm:px-4">
                        <div class="text-[11px] font-black uppercase text-[#8A7F72]">Next</div>
                        <div class="mt-1 truncate text-2xl font-black text-[#1C1A17]">
                            {{ $nextBooking ? $nextBooking->event_date->format('M d') : 'None' }}
                        </div>
                        <div class="truncate text-xs text-[#8A7F72]">{{ $nextBooking->event_title ?? 'No upcoming event' }}</div>
                    </div>
                </div>
            </div>
        </section>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="min-w-0 space-y-4">
                <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-2 overflow-x-auto">
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
                    <div class="bg-white rounded-2xl border border-dashed border-[#EDE4D8] shadow-sm p-10 text-center">
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
                                $style = $statusStyles[$status] ?? $statusStyles['pending'];
                                $clientName = $booking->user->name ?? 'Client';
                                $clientInitials = strtoupper(substr($clientName, 0, 1) . (str_contains($clientName, ' ') ? substr($clientName, strpos($clientName, ' ') + 1, 1) : ''));
                                $packageName = $booking->selected_package_name;
                                $eventTiming = $booking->event_date->isToday()
                                    ? 'Today'
                                    : ($booking->event_date->isFuture() ? $booking->event_date->diffForHumans() : 'Past event');
                            @endphp

                            <article class="group relative overflow-hidden rounded-2xl border bg-white shadow-sm transition-all hover:shadow-md {{ $status === 'pending' ? 'border-[#F6B78E]' : 'border-[#EDE4D8]' }}">
                                <div class="absolute inset-y-0 left-0 w-1 {{ $style['rail'] }}"></div>
                                <div class="grid lg:grid-cols-[108px_minmax(0,1fr)_180px]">
                                    <div class="border-b border-[#F4E7D8] p-5 lg:border-b-0 lg:border-r">
                                        <div class="flex items-center justify-between gap-4 lg:block">
                                            <div>
                                                <div class="text-xs font-black uppercase text-[#8A7F72]">{{ $booking->event_date->format('D') }}</div>
                                                <div class="mt-1 text-4xl font-black leading-none text-[#1C1A17]">{{ $booking->event_date->format('d') }}</div>
                                                <div class="mt-1 text-xs font-bold text-[#8A6D3F]">{{ $booking->event_date->format('M Y') }}</div>
                                            </div>
                                            <div class="rounded-full {{ $style['soft'] }} px-2.5 py-1 text-[11px] font-black lg:mt-4 lg:inline-flex">
                                                {{ $eventTiming }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="min-w-0 p-5 sm:p-6">
                                        <div class="mb-3 flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-black {{ $style['badge'] }}">
                                                <span class="size-1.5 rounded-full {{ $style['dot'] }}"></span>
                                                {{ $style['label'] }}
                                            </span>
                                            <span class="text-xs font-bold text-[#8A7F72]">Requested {{ $booking->created_at->diffForHumans() }}</span>
                                        </div>

                                        <h2 class="text-xl font-black leading-tight text-[#1C1A17]">{{ $booking->event_title }}</h2>
                                        <div class="mt-2 flex items-center gap-2 text-sm font-bold text-[#8A6D3F]">
                                            <span class="flex size-7 items-center justify-center rounded-full bg-[#FDF6EE] text-[11px] font-black text-[#E8642A]">{{ $clientInitials }}</span>
                                            {{ $clientName }}
                                        </div>

                                        <div class="mt-4 grid sm:grid-cols-3 gap-2.5 text-sm">
                                            <div class="rounded-xl bg-[#FDF6EE] px-3 py-2.5">
                                                <div class="text-[11px] font-black uppercase text-[#8A7F72]">Guests</div>
                                                <div class="mt-0.5 font-black text-[#1C1A17]">{{ number_format($booking->guests) }}</div>
                                            </div>
                                            <div class="rounded-xl bg-[#FDF6EE] px-3 py-2.5">
                                                <div class="text-[11px] font-black uppercase text-[#8A7F72]">Package</div>
                                                <div class="mt-0.5 truncate font-black text-[#1C1A17]">{{ $packageName ?? 'Custom request' }}</div>
                                            </div>
                                            <div class="rounded-xl bg-[#FDF6EE] px-3 py-2.5">
                                                <div class="text-[11px] font-black uppercase text-[#8A7F72]">Bundle Price</div>
                                                <div class="mt-0.5 font-black text-[#1C1A17]">
                                                    @if($booking->package_price)
                                                        &#8369;{{ number_format($booking->package_price, 0) }}
                                                    @else
                                                        To quote
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($booking->special_requests || $booking->decline_reason)
                                            <div class="mt-4 space-y-2">
                                                @if($booking->special_requests)
                                                    <div class="rounded-xl border border-[#EDE4D8] bg-white px-3.5 py-3 text-sm text-[#8A7F72]">
                                                        <span class="font-black text-[#1C1A17]">Client notes:</span>
                                                        {{ $booking->special_requests }}
                                                    </div>
                                                @endif
                                                @if($booking->decline_reason)
                                                    <div class="rounded-xl border border-red-200 bg-red-50 px-3.5 py-3 text-sm text-red-700">
                                                        <span class="font-black">Decline reason:</span>
                                                        {{ $booking->decline_reason }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col justify-center gap-2 border-t border-[#F4E7D8] bg-[#FFFCF8] p-5 sm:p-6 lg:border-l lg:border-t-0">
                                        <div class="mb-1">
                                            <div class="text-[11px] font-black uppercase text-[#8A7F72]">Next action</div>
                                            <div class="mt-1 text-sm font-black text-[#1C1A17]">
                                                @if($status === 'pending')
                                                    Reply to request
                                                @elseif($status === 'confirmed')
                                                    Finish event
                                                @else
                                                    Keep in touch
                                                @endif
                                            </div>
                                        </div>

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
                                            <a href="{{ route('messages.show', $booking->user) }}" class="w-full text-center px-4 py-2.5 rounded-xl border border-[#EDE4D8] text-[#8A6D3F] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">Message</a>
                                        @else
                                            <a href="{{ route('messages.show', $booking->user) }}" class="w-full text-center px-4 py-2.5 rounded-xl border border-[#EDE4D8] text-[#8A6D3F] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">Message</a>
                                        @endif
                                    </div>
                                </div>

                                @if($status === 'pending')
                                    <dialog id="declineModal{{ $booking->id }}" class="w-full max-w-md rounded-2xl backdrop:bg-black/50">
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
                            </article>
                        @endforeach
                    </div>
                @endif
        </section>
    </div>
</x-dashboard-layout>
