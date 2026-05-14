@props(['activeBookings', 'savedCaterersCount', 'unreadMessages', 'completedEvents'])

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
    @foreach([
        ['path' => 'M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'value' => $activeBookings, 'label' => 'Active Bookings', 'color' => '#E8642A'],
        ['path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'value' => $savedCaterersCount, 'label' => 'Saved Caterers', 'color' => '#BE3455'],
        ['path' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z', 'value' => $unreadMessages, 'label' => 'Unread Messages', 'color' => '#2563A6'],
        ['path' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z', 'value' => $completedEvents, 'label' => 'Completed Events', 'color' => '#9A5B12'],
    ] as $stat)
    <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8] drop-shadow-md">
        <div class="w-11 h-11 rounded-xl bg-[#FDF6EE] flex items-center justify-center mb-3.5">
            <svg class="w-[22px] h-[22px]" fill="none" stroke="{{ $stat['color'] }}" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['path'] }}"/>
            </svg>
        </div>
        <div class="text-[30px] font-black text-[#1C1A17] leading-none mb-1">{{ $stat['value'] }}</div>
        <div class="text-xs text-[#8A7F72]">{{ $stat['label'] }}</div>
    </div>
    @endforeach
</div>
