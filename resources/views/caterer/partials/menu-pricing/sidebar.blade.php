<a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors font-medium text-sm">
    @include('caterer.partials.menu-pricing.icon', ['name' => 'dashboard', 'class' => 'size-4 stroke-[#8A7F72]'])
    Dashboard
</a>
<a href="{{ route('caterer.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
    <div class="flex items-center gap-2.5">
        @include('caterer.partials.menu-pricing.icon', ['name' => 'bookings', 'class' => 'size-4 stroke-[#8A7F72]'])
        Bookings
    </div>
    @if($pendingBookings > 0)
        <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $pendingBookings }}</span>
    @endif
</a>
<a href="{{ route('caterer.menu-pricing') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
    @include('caterer.partials.menu-pricing.icon', ['name' => 'sliders', 'class' => 'size-4 stroke-[#E8642A]'])
    Menu & Pricing
</a>
<a href="{{ route('caterer.messages') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
    <div class="flex items-center gap-2.5">
        @include('caterer.partials.menu-pricing.icon', ['name' => 'messages', 'class' => 'size-4 stroke-[#8A7F72]'])
        Messages
    </div>
    @if($unreadMessages > 0)
        <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
    @endif
</a>
<a href="{{ route('caterer.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
    @include('caterer.partials.menu-pricing.icon', ['name' => 'reviews', 'class' => 'size-4 stroke-[#8A7F72]'])
    Reviews
</a>
<a href="{{ route('caterer.earnings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
    @include('caterer.partials.menu-pricing.icon', ['name' => 'earnings', 'class' => 'size-4 stroke-[#8A7F72]'])
    Earnings
</a>
