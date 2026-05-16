@php
    $activeBookingsCount = $activeBookings ?? 0;
    $unreadCount = $unreadMessages ?? 0;

    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'client.dashboard',
            'icon' => 'dashboard',
            'active' => request()->routeIs('client.dashboard'),
        ],
        [
            'label' => 'Browse Caterers',
            'route' => 'client.browse',
            'icon' => 'search',
            'active' => request()->routeIs('client.browse'),
        ],
        [
            'label' => 'My Bookings',
            'route' => 'client.bookings',
            'icon' => 'bookings',
            'active' => request()->routeIs('client.bookings*'),
            'count' => $activeBookingsCount,
        ],
        [
            'label' => 'Saved Caterers',
            'route' => 'client.saved-caterers',
            'icon' => 'heart',
            'active' => request()->routeIs('client.saved-caterers'),
        ],
        [
            'label' => 'Messages',
            'route' => 'messages.index',
            'icon' => 'messages',
            'active' => request()->routeIs('messages.*'),
            'count' => $unreadCount,
        ],
        [
            'label' => 'My Reviews',
            'route' => 'client.reviews',
            'icon' => 'reviews',
            'active' => request()->routeIs('client.reviews'),
        ],
    ];
@endphp

@foreach($navItems as $item)
    <a
        href="{{ route($item['route']) }}"
        class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors text-sm {{ $item['active'] ? 'bg-[#FDF6EE] text-[#E8642A] font-large' : 'text-[#1C1A17] hover:bg-[#FDF6EE] font-medium' }}"
    >
        <span class="flex items-center gap-2.5">
            @include('client.partials.sidebar-icon', [
                'name' => $item['icon'],
                'class' => $item['active'] ? 'size-4 stroke-[#E8642A]' : 'size-4 stroke-[#8A7F72]',
            ])
            {{ $item['label'] }}
        </span>
        @if(($item['count'] ?? 0) > 0)
            <span class="bg-red-500 text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $item['count'] }}</span>
        @endif
    </a>
@endforeach
