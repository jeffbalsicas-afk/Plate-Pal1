@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Manage Featured Caterers – PlatePal"
    heading="Featured Caterers"
    :username="$user->name"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Overview
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Users
        </a>
        <a href="{{ route('admin.featured-caterers.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Featured Caterers
        </a>
        <a href="{{ route('admin.bookings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Bookings
        </a>
        <a href="{{ route('admin.reports') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Reports
        </a>
    </x-slot:sidebar>

    <x-slot:sidebarFooter>
        <div class="border-t border-[#EDE4D8] pt-3 mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </x-slot:sidebarFooter>

    <div>
        <div class="mb-6">
            <h2 class="text-2xl font-black text-[#1C1A17] mb-1">Manage Featured Caterers</h2>
            <p class="text-sm text-[#8A7F72] mb-4">Select which caterers appear in the "Featured Caterers Near You" section on the client dashboard</p>
            <div class="flex gap-3">
                <input type="text" id="searchInput" placeholder="Search caterers..." 
                    class="flex-1 px-[18px] py-3 rounded-xl bg-white border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A] transition-colors">
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#EDE4D8] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#EDE4D8] bg-[#FDF6EE]">
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Caterer</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Location</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Cuisine</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Rating</th>
                            <th class="text-left text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Status</th>
                            <th class="text-center text-xs font-bold text-[#8A7F72] uppercase px-6 py-4">Featured</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EDE4D8]">
                        @forelse($caterers as $caterer)
                        <tr class="hover:bg-[#FDF6EE] transition-colors caterer-row" data-name="{{ strtolower($caterer->business_name ?? $caterer->name) }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#E8642A] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($caterer->business_name ?? $caterer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#1C1A17]">{{ $caterer->business_name ?? $caterer->name }}</div>
                                        <div class="text-xs text-[#8A7F72]">{{ $caterer->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[#8A7F72]">{{ $caterer->barangay ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-[#8A7F72]">{{ $caterer->cuisine ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <svg class="size-4 fill-[#FBBF24]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-bold text-[#1C1A17]">{{ $caterer->rating ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $caterer->approval_status === 'approved' ? 'bg-[#EAF5E9] text-[#2E7D32]' : 'bg-[#FFF8E1] text-[#F57F17]' }}">
                                    {{ ucfirst($caterer->approval_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.featured-caterers.toggle', $caterer) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $caterer->is_featured ? 'bg-[#E8642A]' : 'bg-[#D3CCBE]' }} hover:opacity-80">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $caterer->is_featured ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-[#8A7F72]">No approved caterers found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $caterers->links() }}
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.caterer-row').forEach(row => {
                const name = row.getAttribute('data-name');
                row.style.display = name.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>

</x-dashboard-layout>
