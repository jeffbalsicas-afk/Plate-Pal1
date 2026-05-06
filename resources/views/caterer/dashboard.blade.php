@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
    $displayName = $user->business_name ?? $user->name;
@endphp

<x-dashboard-layout
    title="Caterer Dashboard – PlatePal"
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
        <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
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
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-3">
                            <svg class="size-6 text-yellow-600 mt-0.5 animate-pulse" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <h4 class="font-black text-yellow-900 mb-1">Profile Pending Review</h4>
                                <p class="text-sm text-yellow-700">Your profile is under review. We'll notify you once approved.</p>
                            </div>
                        </div>
                        <a href="{{ route('caterer.profile') }}" class="px-4 py-2 rounded-lg bg-yellow-600 text-white text-sm font-bold hover:bg-yellow-700 transition-colors whitespace-nowrap">
                            Edit Profile
                        </a>
                    </div>
                </div>
            @elseif($user->approval_status === 'rejected')
                <div class="bg-gradient-to-r from-red-50 to-rose-50 rounded-2xl p-5 border border-red-200">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-3">
                            <svg class="size-6 text-red-600 mt-0.5" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <h4 class="font-black text-red-900 mb-1">Profile Rejected</h4>
                                <p class="text-sm text-red-700">{{ $user->rejection_reason ?? 'Please update your profile and resubmit.' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('caterer.profile') }}" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors whitespace-nowrap">
                            Update Profile
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5 drop-shadow-md">
        @foreach([
            ['path' => 'M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'value' => $totalBookings,              'label' => 'Total Bookings'],
            ['path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',                                                'value' => $pendingBookings,             'label' => 'Pending Requests'],
            ['path' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z','value' => $unreadMessages,            'label' => 'Unread Messages'],
            ['path' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z', 'value' => number_format($avgRating, 1), 'label' => 'Average Rating'],
        ] as $stat)
        <div class="bg-white rounded-2xl p-5 border border-[#EDE4D8]">
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

    {{-- Upcoming Bookings + Business Trends --}}
    <div class="grid lg:grid-cols-2 gap-5 mb-5 drop-shadow-md">

        {{-- Upcoming Bookings --}}
        <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-black text-[#1C1A17]">Upcoming Bookings</h3>
                <a href="#" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">View All</a>
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
                        <div class="text-xs text-[#8A7F72] mb-1.5">{{ $booking->user->name }}</div>
                        <div class="flex gap-3.5 text-[11.5px] text-[#8A7F72]">
                            <span>📅 {{ $booking->event_date->format('M d, Y') }}</span>
                            <span>👥 {{ $booking->guests }} guests</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Business Trends --}}
        <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8]">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-base font-black text-[#1C1A17]">Business Trends</h3>
                <span class="{{ $growth >= 0 ? 'bg-[#EAF5E9] text-[#2E7D32]' : 'bg-[#FFF8E1] text-[#F57F17]' }} text-xs font-bold px-2 py-1 rounded-lg">
                    {{ $growth >= 0 ? '↑' : '↓' }} {{ abs($growth) }}% {{ $growth >= 0 ? 'Growth' : 'Decline' }}
                </span>
            </div>
            <p class="text-xs text-[#8A7F72] mb-3">Bookings comparison: Previous vs Current Month</p>
            <div class="h-40 mb-3">
                <canvas id="trendsChart"></canvas>
            </div>
            <div class="flex justify-center gap-5 mb-3">
                <div class="flex items-center gap-1.5 text-xs text-[#8A7F72]">
                    <div class="w-5 border-t-2 border-dashed border-[#B0B0B0]"></div>
                    Previous Month
                </div>
                <div class="flex items-center gap-1.5 text-xs text-[#8A7F72]">
                    <div class="w-5 border-t-2 border-[#E8642A]"></div>
                    Current Month
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3.5 rounded-xl border border-[#EDE4D8]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-5 border-t-2 border-dashed border-[#B0B0B0]"></div>
                        <span class="text-xs text-[#8A7F72]">Previous Month</span>
                    </div>
                    <div class="text-[28px] font-black text-[#1C1A17] leading-none mb-1">{{ $previousMonthTotal }}</div>
                    <div class="text-xs text-[#8A7F72]">Total Bookings</div>
                </div>
                <div class="p-3.5 rounded-xl bg-[#FEF3EC] border border-[#FDDDC8]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-5 border-t-2 border-[#E8642A]"></div>
                        <span class="text-xs text-[#E8642A] font-bold">Current Month</span>
                    </div>
                    <div class="text-[28px] font-black text-[#E8642A] leading-none mb-1">{{ $currentMonthTotal }}</div>
                    <div class="text-xs text-[#E8642A]">Total Bookings</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Messages --}}
    <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8] mb-5 drop-shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-black text-[#1C1A17]">Recent Messages</h3>
            <a href="#" class="text-xs font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors">View All</a>
        </div>
        @if($recentMessages->isEmpty())
            <p class="text-sm text-[#8A7F72] text-center py-6">No messages yet.</p>
        @else
            <div class="grid md:grid-cols-3 gap-2.5">
                @foreach($recentMessages as $message)
                <div class="px-3.5 py-3 border border-[#EDE4D8] rounded-xl hover:shadow-sm transition-shadow">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[13.5px] font-bold text-[#1C1A17]">{{ $message->user->name }}</span>
                        @if(!$message->is_read && $message->sender === 'client')
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

    {{-- Business Info --}}
    <div class="bg-white rounded-2xl p-[22px] border border-[#EDE4D8] mb-5 drop-shadow-md">
        <h3 class="text-base font-black text-[#1C1A17] mb-4">Business Information</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold uppercase mb-1">Business Name</div>
                <div class="text-sm font-bold text-[#1C1A17]">{{ $user->business_name ?? '—' }}</div>
            </div>
            <div class="p-4 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold uppercase mb-1">Location</div>
                <div class="text-sm font-bold text-[#1C1A17]">{{ $user->barangay ?? '—' }}</div>
            </div>
            <div class="p-4 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold uppercase mb-1">Phone</div>
                <div class="text-sm font-bold text-[#1C1A17]">{{ $user->phone ?? '—' }}</div>
            </div>
            <div class="p-4 bg-[#FDF6EE] rounded-xl">
                <div class="text-xs text-[#8A7F72] font-bold uppercase mb-1">Email</div>
                <div class="text-sm font-bold text-[#1C1A17]">{{ $user->email }}</div>
            </div>
        </div>
    </div>

    {{-- Top Performing Packages --}}
    <div class="bg-[linear-gradient(135deg,#E8642A_0%,#F07C42_100%)] rounded-3xl p-8 text-white">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="text-2xl font-black">Top Performing Packages</h3>
                <p class="text-sm text-white/80">Your most popular offerings this month</p>
            </div>
            <span class="bg-white/20 px-4 py-2 rounded-xl text-sm font-bold">{{ $totalBookings }} Total Bookings</span>
        </div>
        @if($topPackages->isEmpty())
            <div class="bg-white/10 rounded-3xl p-8 text-center">
                <div class="text-4xl mb-3">📦</div>
                <h4 class="text-lg font-bold mb-2">No packages yet</h4>
                <p class="text-white/80 mb-4">Create your first package to start accepting bookings</p>
                <a href="{{ route('caterer.menu-pricing') }}" class="inline-block bg-white text-[#E8642A] px-6 py-2 rounded-xl font-bold hover:bg-gray-100 transition-colors">
                    Create Package
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($topPackages as $pkg)
                <div class="bg-white rounded-3xl p-6 text-center shadow-lg">
                    <span class="bg-orange-50 text-[#E8642A] text-xs px-3 py-1 rounded-full font-bold">{{ $pkg['rank'] }}</span>
                    <div class="w-16 h-16 bg-orange-50 rounded-2xl mx-auto my-4 flex items-center justify-center text-2xl">🍽️</div>
                    <h4 class="text-[#1C1A17] font-black mb-1">{{ $pkg['name'] }}</h4>
                    <p class="text-xs text-[#8A7F72] mb-4">Catering Package</p>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg">
                            <span class="text-xs text-[#8A7F72] font-bold uppercase">Bookings</span>
                            <span class="text-sm font-black text-[#1C1A17]">{{ $pkg['bookings'] }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-orange-50 p-2 rounded-lg">
                            <span class="text-xs text-[#E8642A] font-bold uppercase">Revenue</span>
                            <span class="text-sm font-black text-[#E8642A]">₱{{ $pkg['revenue'] }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg">
                            <span class="text-xs text-[#8A7F72] font-bold uppercase">Satisfaction</span>
                            <span class="text-sm font-black text-green-500">{{ $pkg['satisfaction'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
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
