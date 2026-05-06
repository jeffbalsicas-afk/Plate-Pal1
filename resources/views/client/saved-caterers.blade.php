@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Saved Caterers – PlatePal"
    heading="Saved Caterers"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
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
        <a href="{{ route('client.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                My Bookings
            </div>
        </a>
        <a href="{{ route('client.saved-caterers') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#E8642A] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            Saved Caterers
        </a>
        <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                Messages
            </div>
        </a>
        <a href="{{ route('client.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            My Reviews
        </a>
    </x-slot:sidebar>

    {{-- Content --}}
    <div class="bg-white rounded-2xl px-7 py-6 border border-[#EDE4D8] mb-5 drop-shadow-md">
        <h2 class="text-xl font-black text-[#1C1A17] mb-6">Your Saved Caterers</h2>

        @if($savedCaterers->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-[#EDE4D8] mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <p class="text-[#8A7F72] text-lg font-medium mb-4">No saved caterers yet</p>
                <a href="{{ route('client.browse') }}" class="inline-block px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                    Browse Caterers
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($savedCaterers as $saved)
                <div class="bg-white rounded-2xl overflow-hidden border border-[#EDE4D8] hover:shadow-lg transition-shadow">
                    <div class="relative h-40 overflow-hidden bg-gradient-to-br from-[#FDF6EE] to-[#F5EFEA]">
                        <img src="{{ $saved->caterer->profile_image ?? '/assets/Pusit.png' }}" alt="{{ $saved->caterer->business_name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        <form action="{{ route('client.saved-caterers.toggle', $saved->caterer) }}" method="POST" class="absolute top-3 right-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-[#FDF6EE] transition-colors shadow-md">
                                <svg class="w-4 h-4 fill-[#E8642A]" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </button>
                        </form>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <h4 class="text-sm font-bold text-[#1C1A17]">{{ $saved->caterer->business_name ?? $saved->caterer->name }}</h4>
                                <p class="text-xs text-[#8A7F72]">📍 {{ $saved->caterer->barangay ?? 'Tagum City' }}</p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <svg class="size-4 fill-[#FBBF24]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-bold text-[#1C1A17]">{{ $saved->caterer->rating ?? 4.5 }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-[#E8642A] font-medium mb-2">{{ $saved->caterer->cuisine ?? 'Filipino Cuisine' }}</p>
                        <p class="text-xs text-[#8A7F72] mb-3">{{ $saved->caterer->reviews_count ?? 0 }} reviews</p>
                        <div class="flex items-center justify-between pt-3 border-t border-[#EDE4D8]">
                            <span class="text-sm font-bold text-[#E8642A]">PHP {{ $saved->caterer->price_min ?? 300 }}-{{ $saved->caterer->price_max ?? 500 }}/head</span>
                            <a href="{{ route('caterer.detail', $saved->caterer) }}" class="px-3 py-1.5 rounded-lg bg-[#E8642A] text-white text-xs font-bold hover:bg-[#F07C42] transition-colors">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $savedCaterers->links() }}
            </div>
        @endif
    </div>

</x-dashboard-layout>
