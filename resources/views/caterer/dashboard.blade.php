@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
    $displayName = $user->business_name ?? $user->name;
@endphp

<x-dashboard-layout
    title="Caterer Dashboard - PlatePal"
    heading="Caterer Dashboard"
    :username="$displayName"
    :usersub="$user->barangay ?? ''"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        <a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
        <a href="{{ route('caterer.messages') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
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
        <a href="{{ route('caterer.earnings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Earnings
        </a>
    </x-slot:sidebar>

    {{-- Approval Status Card --}}
    @if($user->approval_status !== 'approved')
        <div class="mb-5 drop-shadow-md">
            @if($user->approval_status === 'pending')
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-2xl p-5 border border-yellow-200">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <svg class="size-6 text-yellow-600 mt-0.5 animate-pulse shrink-0" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <h4 class="font-black text-yellow-900 mb-1">Profile Pending Review</h4>
                                <p class="text-sm text-yellow-700">Your profile is under review. We'll notify you once approved.</p>
                            </div>
                        </div>
                        <a href="{{ route('caterer.profile') }}" class="inline-flex justify-center px-4 py-2 rounded-lg bg-yellow-600 text-white text-sm font-bold hover:bg-yellow-700 transition-colors whitespace-nowrap">
                            Edit Profile
                        </a>
                    </div>
                </div>
            @elseif($user->approval_status === 'rejected')
                <div class="bg-gradient-to-r from-red-50 to-rose-50 rounded-2xl p-5 border border-red-200">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <svg class="size-6 text-red-600 mt-0.5 shrink-0" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <h4 class="font-black text-red-900 mb-1">Profile Rejected</h4>
                                <p class="text-sm text-red-700">{{ $user->rejection_reason ?? 'Please update your profile and resubmit.' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('caterer.profile') }}" class="inline-flex justify-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors whitespace-nowrap">
                            Update Profile
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Priority Actions --}}
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
        <a href="{{ route('caterer.bookings', ['status' => 'pending']) }}" class="group relative overflow-hidden rounded-2xl border bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md {{ $pendingBookings > 0 ? 'border-[#F6B78E]' : 'border-[#EDE4D8]' }}">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#E8642A]"></div>
            <div class="flex items-start justify-between gap-3 mb-4 pt-1">
                <div class="w-11 h-11 rounded-xl bg-[#FEF3EC] flex items-center justify-center">
                    <svg class="w-[22px] h-[22px] stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $pendingBookings > 0 ? 'bg-[#E8642A] text-white' : 'bg-[#F6EFE7] text-[#8A6D3F]' }}">
                    {{ $pendingBookings > 0 ? 'Needs review' : 'Clear' }}
                </span>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $pendingBookings }}</div>
            <div class="text-sm font-black text-[#1C1A17] mb-1">Pending Requests</div>
            <div class="min-h-8 text-xs text-[#8A7F72]">{{ $pendingBookings > 0 ? 'Review new client requests first.' : 'No new requests waiting.' }}</div>
            <div class="mt-4 flex items-center justify-between border-t border-[#F4E7D8] pt-3 text-xs font-black text-[#E8642A]">
                <span>Review requests</span>
                <svg class="size-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <a href="{{ route('caterer.bookings', ['status' => 'confirmed']) }}" class="group relative overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#2E7D32]"></div>
            <div class="flex items-start justify-between gap-3 mb-4 pt-1">
                <div class="w-11 h-11 rounded-xl bg-[#EAF5E9] flex items-center justify-center">
                    <svg class="w-[22px] h-[22px] stroke-[#2E7D32]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#EAF5E9] text-[#2E7D32]">Schedule</span>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $upcomingBookings->count() }}</div>
            <div class="text-sm font-black text-[#1C1A17] mb-1">Upcoming Events</div>
            <div class="min-h-8 text-xs text-[#8A7F72]">See the next bookings to prepare.</div>
            <div class="mt-4 flex items-center justify-between border-t border-[#E6F1E6] pt-3 text-xs font-black text-[#2E7D32]">
                <span>Open schedule</span>
                <svg class="size-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <a href="{{ route('caterer.messages') }}" class="group relative overflow-hidden rounded-2xl border bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md {{ $unreadMessages > 0 ? 'border-[#F6B78E]' : 'border-[#EDE4D8]' }}">
            <div class="absolute inset-x-0 top-0 h-1 bg-red-500"></div>
            <div class="flex items-start justify-between gap-3 mb-4 pt-1">
                <div class="w-11 h-11 rounded-xl bg-[#FEF3EC] flex items-center justify-center">
                    <svg class="w-[22px] h-[22px] stroke-red-500" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                </div>
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $unreadMessages > 0 ? 'bg-red-500 text-white' : 'bg-[#F6EFE7] text-[#8A6D3F]' }}">
                    {{ $unreadMessages > 0 ? 'Unread' : 'Clear' }}
                </span>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $unreadMessages }}</div>
            <div class="text-sm font-black text-[#1C1A17] mb-1">Client Messages</div>
            <div class="min-h-8 text-xs text-[#8A7F72]">{{ $unreadMessages > 0 ? 'Reply before clients wait too long.' : 'Inbox is clear right now.' }}</div>
            <div class="mt-4 flex items-center justify-between border-t border-[#F4E7D8] pt-3 text-xs font-black text-red-500">
                <span>Open inbox</span>
                <svg class="size-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <a href="{{ route('caterer.reviews') }}" class="group relative overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#8A6D3F]"></div>
            <div class="flex items-start justify-between gap-3 mb-4 pt-1">
                <div class="w-11 h-11 rounded-xl bg-[#F6EFE7] flex items-center justify-center">
                    <svg class="w-[22px] h-[22px] stroke-[#8A6D3F]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#F6EFE7] text-[#8A6D3F]">Reviews</span>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ number_format($avgRating, 1) }}</div>
            <div class="text-sm font-black text-[#1C1A17] mb-1">Average Rating</div>
            <div class="min-h-8 text-xs text-[#8A7F72]">Track feedback after events.</div>
            <div class="mt-4 flex items-center justify-between border-t border-[#F4E7D8] pt-3 text-xs font-black text-[#8A6D3F]">
                <span>View feedback</span>
                <svg class="size-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    {{-- Daily Work --}}
    <div class="grid xl:grid-cols-[minmax(0,1fr)_380px] gap-5 mb-5 drop-shadow-md">
        {{-- Upcoming Bookings --}}
        <div class="relative overflow-hidden bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-base font-black text-[#1C1A17]">Upcoming Bookings</h3>
                    <p class="text-xs text-[#8A7F72]">Next events and requests to prepare.</p>
                </div>
                <a href="{{ route('caterer.bookings') }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors whitespace-nowrap">View All</a>
            </div>
            @if($upcomingBookings->isEmpty())
                <div class="rounded-xl border border-dashed border-[#EDE4D8] p-6 text-center">
                    <div class="size-12 rounded-xl bg-[#FDF6EE] flex items-center justify-center mx-auto mb-3">
                        <svg class="size-6 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-[#1C1A17]">No upcoming bookings.</p>
                    <p class="text-xs text-[#8A7F72] mt-1">Accepted bookings and new requests will appear here.</p>
                </div>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach($upcomingBookings as $booking)
                        @php
                            $status = strtolower($booking->status ?? 'pending');
                            $bookingStatusClass = $status === 'confirmed' ? 'bg-[#EAF5E9] text-[#2E7D32]' : 'bg-[#FFF8E1] text-[#F57F17]';
                            $bookingFilter = $status === 'confirmed' ? 'confirmed' : 'pending';
                            $bookingAction = $status === 'confirmed' ? 'Open Event' : 'Review Request';
                        @endphp
                        <div class="px-3.5 py-3 border border-[#EDE4D8] rounded-xl hover:shadow-sm transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                <div class="min-w-0">
                                    <div class="text-[13.5px] font-bold text-[#1C1A17] truncate">{{ $booking->event_title }}</div>
                                    <div class="text-xs text-[#8A7F72] mt-0.5">{{ $booking->user->name }}</div>
                                </div>
                                <span class="self-start text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $bookingStatusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex flex-wrap gap-3.5 text-[11.5px] text-[#8A7F72]">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="size-3.5 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $booking->event_date->format('M d, Y') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="size-3.5 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-4a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                        {{ number_format($booking->guests) }} guests
                                    </span>
                                </div>
                                <a href="{{ route('caterer.bookings', ['status' => $bookingFilter]) }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors whitespace-nowrap">{{ $bookingAction }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Messages --}}
        <div class="relative overflow-hidden bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#E8642A]"></div>
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-base font-black text-[#1C1A17]">Recent Messages</h3>
                    <p class="text-xs text-[#8A7F72]">Latest client conversations.</p>
                </div>
                <a href="{{ route('caterer.messages') }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors whitespace-nowrap">View All</a>
            </div>
            @if($recentMessages->isEmpty())
                <div class="rounded-xl border border-dashed border-[#EDE4D8] p-6 text-center">
                    <div class="size-12 rounded-xl bg-[#FDF6EE] flex items-center justify-center mx-auto mb-3">
                        <svg class="size-6 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-[#1C1A17]">No messages yet.</p>
                    <p class="text-xs text-[#8A7F72] mt-1">Client conversations will show up here.</p>
                </div>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach($recentMessages as $message)
                        <a href="{{ route('messages.show', $message->user) }}" class="block px-3.5 py-3 border border-[#EDE4D8] rounded-xl hover:shadow-sm hover:bg-[#FDF6EE] transition-colors">
                            <div class="flex items-center justify-between gap-3 mb-1">
                                <span class="text-[13.5px] font-bold text-[#1C1A17] truncate">{{ $message->user->name }}</span>
                                @if(!$message->is_read && $message->sender === 'client')
                                    <span class="text-[11.5px] text-[#E8642A] font-semibold flex items-center gap-1 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-[11.5px] text-[#8A7F72] font-medium whitespace-nowrap">{{ $message->created_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            <div class="text-[12.5px] text-[#8A7F72]">{{ Str::limit($message->previewText(), 58) }}</div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Performance Summary --}}
    <div class="grid xl:grid-cols-2 gap-5 mb-5 drop-shadow-md">
        {{-- Business Trends --}}
        <div class="relative overflow-hidden bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#E8642A]"></div>
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                <div>
                    <h3 class="text-base font-black text-[#1C1A17]">Business Trends</h3>
                    <p class="text-xs text-[#8A7F72] mt-1">Previous month vs current month.</p>
                </div>
                <span class="{{ $growth >= 0 ? 'bg-[#EAF5E9] text-[#2E7D32]' : 'bg-[#FFF8E1] text-[#F57F17]' }} text-xs font-bold px-2 py-1 rounded-lg self-start">
                    {{ $growth >= 0 ? '+' : '-' }}{{ abs($growth) }}%
                </span>
            </div>
            <div class="h-36 mb-3">
                <canvas id="trendsChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3.5 rounded-xl border border-[#EDE4D8]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-5 border-t-2 border-dashed border-[#B0B0B0]"></div>
                        <span class="text-xs text-[#8A7F72]">Previous Month</span>
                    </div>
                    <div class="text-[26px] font-black text-[#1C1A17] leading-none mb-1">{{ $previousMonthTotal }}</div>
                    <div class="text-xs text-[#8A7F72]">Bookings</div>
                </div>
                <div class="p-3.5 rounded-xl bg-[#FEF3EC] border border-[#FDDDC8]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-5 border-t-2 border-[#E8642A]"></div>
                        <span class="text-xs text-[#E8642A] font-bold">Current Month</span>
                    </div>
                    <div class="text-[26px] font-black text-[#E8642A] leading-none mb-1">{{ $currentMonthTotal }}</div>
                    <div class="text-xs text-[#E8642A]">Bookings</div>
                </div>
            </div>
        </div>

        {{-- Top Performing Packages --}}
        <div class="relative overflow-hidden bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            <div class="absolute inset-x-0 top-0 h-1 bg-[#E8642A]"></div>
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-base font-black text-[#1C1A17]">Top Packages</h3>
                    <p class="text-xs text-[#8A7F72] mt-1">Popular offers this month.</p>
                </div>
                <span class="bg-[#FDF6EE] text-[#8A6D3F] px-3 py-1.5 rounded-lg text-xs font-bold self-start">{{ $totalBookings }} total bookings</span>
            </div>
            @if($topPackages->isEmpty())
                <div class="rounded-xl border border-dashed border-[#EDE4D8] p-6 text-center">
                    <div class="size-12 rounded-xl bg-[#FDF6EE] flex items-center justify-center mx-auto mb-3">
                        <svg class="size-6 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-sm font-bold text-[#1C1A17]">No packages yet.</p>
                    <p class="text-xs text-[#8A7F72] mt-1 mb-4">Create a package so clients can book faster.</p>
                    <a href="{{ route('caterer.menu-pricing') }}" class="inline-flex justify-center px-4 py-2 rounded-lg bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                        Create Package
                    </a>
                </div>
            @else
                <div class="grid gap-2.5">
                    @foreach($topPackages as $pkg)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3.5 rounded-xl border border-[#EDE4D8]">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="bg-orange-50 text-[#E8642A] text-[11px] px-2.5 py-1 rounded-full font-bold">{{ $pkg['rank'] }}</span>
                                    <span class="text-xs text-[#8A7F72]">Catering Package</span>
                                </div>
                                <h4 class="text-[#1C1A17] font-black truncate">{{ $pkg['name'] }}</h4>
                            </div>
                            <div class="grid grid-cols-3 sm:flex sm:items-center gap-2 text-center sm:text-right">
                                <div class="rounded-lg bg-gray-50 px-3 py-2">
                                    <div class="text-[11px] text-[#8A7F72] font-bold">Bookings</div>
                                    <div class="text-sm font-black text-[#1C1A17]">{{ $pkg['bookings'] }}</div>
                                </div>
                                <div class="rounded-lg bg-orange-50 px-3 py-2">
                                    <div class="text-[11px] text-[#E8642A] font-bold">Revenue</div>
                                    <div class="text-sm font-black text-[#E8642A]">{{ $pkg['revenue'] }}</div>
                                </div>
                                <div class="rounded-lg bg-gray-50 px-3 py-2">
                                    <div class="text-[11px] text-[#8A7F72] font-bold">Rating</div>
                                    <div class="text-sm font-black text-green-600">{{ $pkg['satisfaction'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Business Profile Snapshot --}}
    <div class="relative overflow-hidden bg-white rounded-2xl p-[22px] border border-[#EDE4D8] mb-5 drop-shadow-md">
       
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h3 class="text-base font-black text-[#1C1A17]">Business Profile</h3>
                <p class="text-xs text-[#8A7F72] mt-1">Quick check of what clients see.</p>
            </div>
            <a href="{{ route('caterer.profile') }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">Edit Profile</a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="p-3.5 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold mb-1">Business Name</div>
                <div class="text-sm font-bold text-[#1C1A17] break-words">{{ $user->business_name ?? 'Not set' }}</div>
            </div>
            <div class="p-3.5 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold mb-1">Location</div>
                <div class="text-sm font-bold text-[#1C1A17] break-words">{{ $user->barangay ?? 'Not set' }}</div>
            </div>
            <div class="p-3.5 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold mb-1">Phone</div>
                <div class="text-sm font-bold text-[#1C1A17] break-words">{{ $user->phone ?? 'Not set' }}</div>
            </div>
            <div class="p-3.5 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold mb-1">Email</div>
                <div class="text-sm font-bold text-[#1C1A17] break-words">{{ $user->email }}</div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
    <script>
    const ctx = document.getElementById('trendsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [
                {
                    label: 'Previous Month',
                    data: {{ json_encode($previousWeekly) }},
                    borderColor: '#B0B0B0',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    pointRadius: 0,
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Current Month',
                    data: {{ json_encode($currentWeekly) }},
                    borderColor: '#E8642A',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#E8642A',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#8A7F72' } },
                y: { min: 0, ticks: { stepSize: 1, font: { size: 11 }, color: '#8A7F72' }, grid: { color: '#EDE4D8' } }
            }
        }
    });
    </script>
    </x-slot:scripts>

</x-dashboard-layout>
