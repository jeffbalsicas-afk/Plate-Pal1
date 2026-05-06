@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Client Dashboard – PlatePal"
    heading="Client Dashboard"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        <a href="{{ route('client.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#E8642A] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Dashboard
            </div>
        </a>
        <a href="{{ route('client.browse') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse Caterers
            </div>
        </a>
        <a href="{{ route('client.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                My Bookings
            </div>
            @if($activeBookings > 0)
                <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $activeBookings }}</span>
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
                <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
            @endif
        </a>
        <a href="{{ route('client.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            My Reviews
        </a>
    </x-slot:sidebar>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
        @foreach([
            ['path' => 'M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'value' => $activeBookings,  'label' => 'Active Bookings'],
            ['path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'value' => 0, 'label' => 'Saved Caterers'],
            ['path' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z', 'value' => $unreadMessages, 'label' => 'Unread Messages'],
            ['path' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z', 'value' => $completedEvents, 'label' => 'Completed Events'],
        ] as $stat)
        <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8] drop-shadow-md">
            <div class="w-11 h-11 rounded-xl bg-[#FDF6EE] flex items-center justify-center mb-3.5">
                <svg class="w-[22px] h-[22px]" fill="none" stroke="#E8642A" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['path'] }}"/>
                </svg>
            </div>
            <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $stat['value'] }}</div>
            <div class="text-xs text-[#8A7F72]">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl px-7 py-6 border border-[#EDE4D8] mb-5 drop-shadow-md">
        <h2 class="text-xl font-black text-[#1C1A17] mb-4">Find Your Perfect Caterer</h2>
        <div class="flex gap-3">
            <input type="text" placeholder="Search caterers or specialties..."
                class="flex-1 px-[18px] py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A] transition-colors">
            <button class="px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors flex items-center gap-1.5 whitespace-nowrap">
                Search
                <svg class="size-3.5" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    {{-- Upcoming Events + Messages --}}
    <div class="grid lg:grid-cols-2 gap-5 mb-5">

        {{-- Upcoming Bookings --}}
        <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8] drop-shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-black text-[#1C1A17]">Upcoming Events</h3>
                <a href="{{ route('client.bookings') }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">View All</a>
            </div>
            @if($upcomingBookings->isEmpty())
                <p class="text-sm text-[#8A7F72] text-center py-6">No upcoming bookings yet.</p>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach($upcomingBookings as $booking)
                    <div class="px-3.5 py-3 border border-[#EDE4D8] rounded-xl hover:shadow-sm transition-shadow">
                        <div class="flex items-start justify-between mb-1">
                            <span class="text-[13.5px] font-bold text-[#1C1A17]">{{ $booking->event_title }}</span>
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $booking->status === 'confirmed' ? 'bg-[#EAF5E9] text-[#2E7D32]' : 'bg-[#FFF8E1] text-[#F57F17]' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="text-xs text-[#8A7F72] mb-1.5">{{ $booking->caterer->business_name ?? $booking->caterer->name }}</div>
                        <div class="flex gap-3.5 text-[11.5px] text-[#8A7F72]">
                            <span>📅 {{ $booking->event_date->format('M d, Y') }}</span>
                            <span>👥 {{ $booking->guests }} guests</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Messages --}}
        <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8] drop-shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-black text-[#1C1A17]">Recent Messages</h3>
                <a href="{{ route('messages.index') }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">View All</a>
            </div>
            @if($recentMessages->isEmpty())
                <p class="text-sm text-[#8A7F72] text-center py-6">No messages yet.</p>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach($recentMessages as $message)
                    <div class="px-3.5 py-3 border border-[#EDE4D8] rounded-xl hover:shadow-sm transition-shadow">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[13.5px] font-bold text-[#1C1A17]">{{ $message->caterer->business_name ?? $message->caterer->name }}</span>
                            @if(!$message->is_read && $message->sender === 'caterer')
                                <span class="text-[11.5px] text-[#E8642A] font-semibold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                    {{ $message->created_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-[11.5px] text-[#8A7F72] font-medium">{{ $message->created_at->diffForHumans() }}</span>
                            @endif
                        </div>
                        <div class="text-[12.5px] text-[#8A7F72]">{{ Str::limit($message->body, 50) }}</div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Featured Caterers --}}
    <div class="bg-white rounded-2xl px-7 py-6 border border-[#EDE4D8] mb-5 drop-shadow-md">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-black text-[#1C1A17]">Featured Caterers Near You</h3>
            <a href="{{ route('client.browse') }}" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">View All</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 drop-shadow-md">
            @forelse($featuredCaterers as $caterer)
            <div class="bg-white rounded-2xl overflow-hidden border border-[#EDE4D8] hover:shadow-lg transition-shadow cursor-pointer">
                <div class="relative h-40 overflow-hidden bg-gradient-to-br from-[#FDF6EE] to-[#F5EFEA]">
                    <img src="{{ $caterer['image'] }}" alt="{{ $caterer['name'] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-[#FDF6EE] transition-colors shadow-md">
                        <svg class="w-4 h-4 fill-[#E8642A]" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <h4 class="text-sm font-bold text-[#1C1A17]">{{ $caterer['name'] }}</h4>
                            <p class="text-xs text-[#8A7F72]">📍 {{ $caterer['location'] }}</p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <svg class="size-4 fill-[#FBBF24]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-xs font-bold text-[#1C1A17]">{{ $caterer['rating'] }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-[#E8642A] font-medium mb-2">{{ $caterer['cuisine'] }}</p>
                    <p class="text-xs text-[#8A7F72] mb-3">{{ $caterer['reviews'] }} reviews</p>
                    <div class="flex items-center justify-between pt-3 border-t border-[#EDE4D8]">
                        <span class="text-sm font-bold text-[#E8642A]">{{ $caterer['price'] }}</span>
                        <button class="px-3 py-1.5 rounded-lg bg-[#E8642A] text-white text-xs font-bold hover:bg-[#F07C42] transition-colors">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8">
                <p class="text-sm text-[#8A7F72]">No featured caterers at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- CTA --}}
    <div class="bg-amber-100 rounded-2xl px-7 py-[22px] border border-[#f1d2a9] flex flex-col sm:flex-row items-center justify-between gap-5 drop-shadow-xl">
        <div>
            <h3 class="text-[17px] font-black text-[#1C1A17] mb-1">Planning an Event?</h3>
            <p class="text-[13px] text-[#8A7F72]">Browse authentic home-cooked meals from verified local caterers</p>
        </div>
        <a href="{{ route('client.browse') }}" class="px-[22px] py-3 rounded-xl bg-[#E8642A] text-white text-[13.5px] font-bold hover:bg-[#F07C42] transition-colors flex items-center gap-1.5 whitespace-nowrap">
            Browse Caterers
            <svg class="size-4" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

</x-dashboard-layout>
