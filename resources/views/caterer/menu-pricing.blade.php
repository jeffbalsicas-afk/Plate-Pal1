<x-dashboard-layout
    title="Menu & Pricing - PlatePal"
    heading="Caterer Dashboard"
    :username="$displayName"
    :usersub="$user->barangay ?? ''"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#1C1A17] font-medium text-sm">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
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
        <a href="{{ route('caterer.menu-pricing') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Menu & Pricing
        </a>
        <a href="#" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
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
        <a href="#" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Earnings
        </a>

    </x-slot:sidebar>

    <div class="max-w-7xl mx-auto" x-data="{ tab: 'packages', showForm: false, formType: '', editingId: null }">
        <!-- How It Works Info -->
        <div class="mb-8 p-6 rounded-2xl border border-[#EDE4D8] bg-[#FDF6EE]">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="size-6 text-[#E8642A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-[#1C1A17] mb-2">How Your Menu Works</h3>
                    <div class="space-y-2 text-sm text-[#8A7F72]">
                        <p><span class="font-bold text-[#1C1A17]"> Packages:</span> Pre-built bundles with fixed pricing. Clients book these directly.</p>
                        <p><span class="font-bold text-[#1C1A17]"> Menu Items:</span> Individual dishes (mains, sides, desserts, beverages). Shown on your profile for transparency.</p>
                        <p><span class="font-bold text-[#1C1A17]"> Add-ons:</span> Extra items clients can add to their booking (drinks, desserts, decorations).</p>
                        <p><span class="font-bold text-[#1C1A17]"> Status:</span> Set items to <span class="font-bold">Live</span> to show clients, or <span class="font-bold">Draft</span> to hide them.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-7">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#1C1A17] mb-1">Menu & Pricing</h1>
                <p class="text-sm text-[#8A7F72]">Manage your packages, dishes, and add-ons.</p>
            </div>
            <button @click="showForm = !showForm; formType = tab" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors shadow-sm">
                <svg class="size-4 stroke-white" fill="none" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/></svg>
                Add New Item
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <form method="GET" class="flex-1 flex gap-2">
                <input type="text" name="search" placeholder="Search by name..." value="{{ request('search') }}" class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors">
                <select name="sort" class="px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors">
                    <option value="">Sort by</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-[#E8642A] text-white font-bold rounded-lg hover:bg-[#F07C42] transition-colors text-xs sm:text-sm">Search</button>
            </form>
            @if(request('search') || request('sort'))
                <a href="{{ route('caterer.menu-pricing') }}" class="px-4 py-2 bg-gray-200 text-[#1C1A17] font-bold rounded-lg hover:bg-gray-300 transition-colors text-xs sm:text-sm">Clear</a>
            @endif
        </div>

        <!-- Tabs -->
        <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
            @foreach(['packages' => 'Bundled Packages', 'items' => 'Menu Items', 'addons' => 'Add-ons'] as $key => $label)
                <button @click="tab = '{{ $key }}'" :class="tab === '{{ $key }}' ? 'bg-[#FFF4ED] border-[#E8642A] text-[#C84D18]' : 'border-transparent text-[#1C1A17] hover:bg-[#FDF6EE]'" class="px-4 py-2 rounded-t-xl border-b-2 text-sm font-bold whitespace-nowrap transition-colors">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <!-- Packages Tab -->
        <section x-show="tab === 'packages'" x-transition class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-bold text-[#8A7F72]">{{ $packages->total() }} total packages</p>
            </div>
            @forelse($packages as $package)
                <div class="bg-white rounded-xl border border-[#EDE4D8] p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-bold text-[#1C1A17]">{{ $package->name }}</h3>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold {{ $package->status === 'live' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    <span class="size-1.5 rounded-full {{ $package->status === 'live' ? 'bg-green-700' : 'bg-yellow-700' }}"></span>
                                    {{ ucfirst($package->status) }}
                                </span>
                            </div>
                            @if($package->description)
                                <p class="text-sm text-[#8A7F72] mb-2">{{ $package->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm">
                                <span class="font-bold text-[#E8642A]">₱{{ number_format($package->price, 0) }}/head</span>
                                <span class="text-[#8A7F72]">Min {{ $package->min_guests }} guests</span>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <a href="{{ route('menu.packages.edit', $package) }}" class="px-3 py-2 text-sm font-bold text-[#E8642A] hover:bg-[#FDF6EE] rounded-lg transition-colors">Edit</a>
                            <form action="{{ route('menu.packages.destroy', $package) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this package?')" class="px-3 py-2 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-[#EDE4D8]">
                    <svg class="size-12 text-[#D3CCBE] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5v2.25m3-6v6m3-6v2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H2.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    <p class="text-[#8A7F72] font-medium">No packages yet. Create one to get started!</p>
                </div>
            @endforelse
            @if($packages->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $packages->links('pagination::tailwind') }}
                </div>
            @endif
        </section>

        <!-- Menu Items Tab -->
        <section x-show="tab === 'items'" x-transition class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-bold text-[#8A7F72]">{{ $menuItems->total() }} total menu items</p>
            </div>
            @forelse($menuItems as $item)
                <div class="bg-white rounded-xl border border-[#EDE4D8] p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-bold text-[#1C1A17]">{{ $item->name }}</h3>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold {{ $item->status === 'live' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    <span class="size-1.5 rounded-full {{ $item->status === 'live' ? 'bg-green-700' : 'bg-yellow-700' }}"></span>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            @if($item->description)
                                <p class="text-sm text-[#8A7F72] mb-2 line-clamp-2">{{ $item->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm">
                                <span class="font-bold text-[#E8642A]">₱{{ number_format($item->price, 0) }}/{{ $item->unit }}</span>
                                <span class="text-[#8A7F72] bg-[#FDF6EE] px-2 py-1 rounded">{{ ucfirst($item->category) }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <a href="{{ route('menu.items.edit', $item) }}" class="px-3 py-2 text-sm font-bold text-[#E8642A] hover:bg-[#FDF6EE] rounded-lg transition-colors">Edit</a>
                            <form action="{{ route('menu.items.destroy', $item) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this item?')" class="px-3 py-2 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-[#EDE4D8]">
                    <svg class="size-12 text-[#D3CCBE] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <p class="text-[#8A7F72] font-medium">No menu items yet. Add your first dish!</p>
                </div>
            @endforelse
            @if($menuItems->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $menuItems->links('pagination::tailwind') }}
                </div>
            @endif
        </section>

        <!-- Add-ons Tab -->
        <section x-show="tab === 'addons'" x-transition class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-bold text-[#8A7F72]">{{ $addOns->total() }} total add-ons</p>
            </div>
            @forelse($addOns as $addon)
                <div class="bg-white rounded-xl border border-[#EDE4D8] p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-bold text-[#1C1A17]">{{ $addon->name }}</h3>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold {{ $addon->status === 'live' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    <span class="size-1.5 rounded-full {{ $addon->status === 'live' ? 'bg-green-700' : 'bg-yellow-700' }}"></span>
                                    {{ ucfirst($addon->status) }}
                                </span>
                            </div>
                            @if($addon->description)
                                <p class="text-sm text-[#8A7F72] mb-2 line-clamp-2">{{ $addon->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm">
                                <span class="font-bold text-[#E8642A]">₱{{ number_format($addon->price, 0) }}/{{ $addon->unit }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <a href="{{ route('menu.addons.edit', $addon) }}" class="px-3 py-2 text-sm font-bold text-[#E8642A] hover:bg-[#FDF6EE] rounded-lg transition-colors">Edit</a>
                            <form action="{{ route('menu.addons.destroy', $addon) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this add-on?')" class="px-3 py-2 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-[#EDE4D8]">
                    <svg class="size-12 text-[#D3CCBE] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <p class="text-[#8A7F72] font-medium">No add-ons yet. Create one to offer extras!</p>
                </div>
            @endforelse
            @if($addOns->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $addOns->links('pagination::tailwind') }}
                </div>
            @endif
        </section>

        <!-- Add Form Modal -->
        <div x-show="showForm" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-[#1C1A17]">Add New <span x-text="formType === 'packages' ? 'Package' : formType === 'items' ? 'Menu Item' : 'Add-on'"></span></h2>
                    </div>
                    <button @click="showForm = false" class="text-[#8A7F72] hover:text-[#1C1A17] transition-colors">
                        <svg class="size-6" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Package Form -->
                <form x-show="formType === 'packages'" action="{{ route('menu.packages.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <label class="block text-xs sm:text-sm font-bold text-[#ff0000] mb-2">* Required</label>
                    <input type="text" name="name" placeholder="Package name" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                    <input type="number" name="price" placeholder="Price" step="0.01" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                    <input type="number" name="min_guests" placeholder="Min guests" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                    <textarea name="description" placeholder="Description" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors resize-none"></textarea>
                    <select name="status" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                        <option value="draft">Draft</option>
                        <option value="live">Live</option>
                    </select>
                    <div class="flex gap-2">
                    <button type="submit" class="w-full px-4 py-2 bg-[#E8642A] text-white font-bold rounded-lg hover:bg-[#F07C42] transition-colors">Create Package</button>
                    <button type="button" @click="showForm = false" class="w-full px-4 py-2 border border-[#EDE4D8] text-[#1C1A17] font-bold rounded-lg hover:bg-[#FDF6EE] transition-colors">Back</button>
                    </div>
                </form>

                <!-- Menu Item Form -->
                <form x-show="formType === 'items'" action="{{ route('menu.items.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Item name" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                    <input type="number" name="price" placeholder="Price" step="0.01" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                    <textarea name="description" placeholder="Description (optional)" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors resize-none"></textarea>
                    <select name="unit" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                        <option value="">Select unit</option>
                        <option value="head">Per Head</option>
                        <option value="tray">Per Tray</option>
                        <option value="whole">Whole</option>
                        <option value="bottle">Per Bottle</option>
                        <option value="box">Per Box</option>
                    </select>
                    <select name="category" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                        <option value="">Select category</option>
                        <option value="main">Main Dish</option>
                        <option value="side">Side Dish</option>
                        <option value="dessert">Dessert</option>
                        <option value="beverage">Beverage</option>
                    </select>
                    <select name="status" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                        <option value="draft">Draft</option>
                        <option value="live">Live</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="w-full px-4 py-2 bg-[#E8642A] text-white font-bold rounded-lg hover:bg-[#F07C42] transition-colors">Create Item</button>
                        <button type="button" @click="showForm = false" class="px-4 py-2 border border-[#EDE4D8] text-[#1C1A17] font-bold rounded-lg hover:bg-[#FDF6EE] transition-colors">Back</button>
                    </div>
                </form>

                <!-- Add-on Form -->
                <form x-show="formType === 'addons'" action="{{ route('menu.addons.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Add-on name" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                    <input type="number" name="price" placeholder="Price" step="0.01" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                    <textarea name="description" placeholder="Description (optional)" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors resize-none"></textarea>
                    <select name="unit" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                        <option value="">Select unit</option>
                        <option value="head">Per Head</option>
                        <option value="tray">Per Tray</option>
                        <option value="bottle">Per Bottle</option>
                        <option value="box">Per Box</option>
                    </select>
                    <select name="status" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-[#bebab1] text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A] hover:bg-gray-100 hover:border-[#8A7F72] transition-colors" required>
                        <option value="draft">Draft</option>
                        <option value="live">Live</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-[#E8642A] text-white font-bold rounded-lg hover:bg-[#F07C42] transition-colors">Create Add-on</button>
                        <button type="button" @click="showForm = false" class="px-4 py-2 border border-[#EDE4D8] text-[#1C1A17] font-bold rounded-lg hover:bg-[#FDF6EE] transition-colors">Back</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>
