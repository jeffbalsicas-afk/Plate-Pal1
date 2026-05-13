<section x-show="tab === @js($tabKey)" x-transition class="space-y-3">
    @forelse($menuType['entries'] as $entry)
        @php($badge = $statusBadges[$entry->status] ?? $statusBadges['draft'])
        @php($deleteFormId = "delete-{$tabKey}-{$entry->getKey()}")
        <article class="bg-white rounded-2xl border border-[#EDE4D8] p-5 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h2 class="text-lg font-black text-[#1C1A17] truncate">{{ $entry->name }}</h2>
                        @include('caterer.partials.menu-pricing.status-badge', ['badge' => $badge])
                    </div>
                    @if($entry->description)
                        <p class="text-sm text-[#8A7F72] mb-3 line-clamp-2">{{ $entry->description }}</p>
                    @endif
                    <div class="flex flex-wrap gap-2 text-xs font-bold">
                        <span class="inline-flex items-center rounded-lg bg-[#FEF3EC] px-3 py-1.5 text-[#E8642A]">
                            PHP {{ number_format($entry->price, 0) }}
                            @if($tabKey === 'packages')
                                bundle
                            @else
                                /{{ $entry->unit }}
                            @endif
                        </span>
                        @if($tabKey === 'packages')
                            <span class="inline-flex items-center rounded-lg bg-[#F6EFE7] px-3 py-1.5 text-[#8A6D3F]">Min {{ number_format($entry->min_guests) }} guests</span>
                        @elseif($tabKey === 'items')
                            <span class="inline-flex items-center rounded-lg bg-[#F6EFE7] px-3 py-1.5 text-[#8A6D3F]">{{ ucfirst($entry->category) }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row lg:justify-end gap-2 lg:w-44">
                    <a href="{{ route($menuType['editRoute'], $entry) }}" class="inline-flex justify-center px-4 py-2.5 rounded-xl bg-[#FDF6EE] text-[#E8642A] text-sm font-bold hover:bg-[#FEF3EC] transition-colors">Edit</a>
                    <button
                        type="button"
                        @click="openDeleteModal(@js($deleteFormId), @js($entry->name), @js($menuType['singular']))"
                        class="w-full px-4 py-2.5 rounded-xl border border-red-200 text-red-600 text-sm font-bold hover:bg-red-50 transition-colors"
                    >
                        Delete
                    </button>
                    <form id="{{ $deleteFormId }}" action="{{ route($menuType['destroyRoute'], $entry) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </article>
    @empty
        <div class="bg-white rounded-2xl border border-dashed border-[#EDE4D8] p-10 text-center">
            <div class="size-14 rounded-2xl bg-[#FDF6EE] flex items-center justify-center mx-auto mb-4">
                @include('caterer.partials.menu-pricing.icon', ['name' => $menuType['icon'], 'class' => 'size-7 stroke-[#E8642A]'])
            </div>
            <h2 class="text-xl font-black text-[#1C1A17] mb-2">{{ $menuType['emptyTitle'] }}</h2>
            <p class="text-sm text-[#8A7F72] mb-4">{{ $menuType['emptyCopy'] }}</p>
            <button @click="openForm(@js($tabKey))" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                @include('caterer.partials.menu-pricing.icon', ['name' => 'addons', 'class' => 'size-4 stroke-white'])
                {{ $menuType['emptyButton'] }}
            </button>
        </div>
    @endforelse
    @if($menuType['entries']->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $menuType['entries']->appends(request()->except($menuType['pageName']))->links('pagination::tailwind') }}
        </div>
    @endif
</section>
