@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="My Reviews – PlatePal"
    heading="My Reviews"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        @include('client.partials.sidebar')
    </x-slot:sidebar>

    {{-- Stats --}}
    <x-client-stats 
        :activeBookings="$activeBookings" 
        :savedCaterersCount="$savedCaterersCount" 
        :unreadMessages="$unreadMessages" 
        :completedEvents="$completedEvents" 
    />

    {{-- Content --}}
    <div class="bg-white rounded-2xl px-7 py-6 border border-[#EDE4D8] mb-5 drop-shadow-md">
        <h2 class="text-xl font-black text-[#1C1A17] mb-6">Your Reviews</h2>

        @if($reviews->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-[#EDE4D8] mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <p class="text-[#8A7F72] text-lg font-medium mb-4">No reviews yet</p>
                <a href="{{ route('client.bookings') }}" class="inline-block px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                    View Bookings
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($reviews as $review)
                <div class="border border-[#EDE4D8] rounded-xl p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-base font-bold text-[#1C1A17] mb-1">{{ $review->title }}</h3>
                            <p class="text-sm text-[#8A7F72]">{{ $review->caterer->business_name ?? $review->caterer->name }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="size-4 {{ $i < $review->rating ? 'fill-[#FBBF24]' : 'fill-[#EDE4D8]' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-[#1C1A17] mb-3">{{ $review->body }}</p>
                    <div class="flex items-center justify-between text-xs text-[#8A7F72]">
                        <span>{{ $review->reviewed_at->format('M d, Y') }}</span>
                        <span class="px-2.5 py-1 rounded-full {{ $review->status === 'public' ? 'bg-[#EAF5E9] text-[#2E7D32]' : 'bg-[#FFF8E1] text-[#F57F17]' }}">
                            {{ ucfirst($review->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</x-dashboard-layout>
