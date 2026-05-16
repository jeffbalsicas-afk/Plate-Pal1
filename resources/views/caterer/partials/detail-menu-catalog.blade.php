@php
    $modalPrefix = $modalPrefix ?? 'detail';
@endphp

@if($menuItems->isNotEmpty() || $addOns->isNotEmpty())
    <div class="mb-8" x-data="{ selectedItem: null, selectedAddon: null }">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-black text-[#1C1A17]">Menu & Add-ons</h2>
                <p class="text-sm text-[#8A7F72]">Review available menu choices and optional extras before sending a booking request.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs font-bold">
                @if($menuItems->isNotEmpty())
                    <span class="rounded-full bg-[#FFF7E8] px-3 py-1 text-[#9A5B12]">{{ $menuItems->count() }} menu item{{ $menuItems->count() === 1 ? '' : 's' }}</span>
                @endif
                @if($addOns->isNotEmpty())
                    <span class="rounded-full bg-[#EAF8F4] px-3 py-1 text-[#0F766E]">{{ $addOns->count() }} add-on{{ $addOns->count() === 1 ? '' : 's' }}</span>
                @endif
            </div>
        </div>

        @if($menuItems->isNotEmpty())
            <section class="mb-7" aria-labelledby="{{ $modalPrefix }}-menu-items-heading">
                <h3 id="{{ $modalPrefix }}-menu-items-heading" class="mb-3 text-sm font-black uppercase tracking-[0.18em] text-[#8A7F72]">Menu Items</h3>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($menuItems as $item)
                        @php
                            $itemUnit = $item->unit ?: 'item';
                            $itemCategory = $item->category ? ucfirst($item->category) : 'Menu';
                        @endphp
                        <button type="button" @click="selectedItem = {{ $item->id }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            @if($item->image_path)
                                <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="h-40 w-full object-cover">
                            @else
                                <div class="flex h-40 w-full items-center justify-center bg-[#FDF6EE]">
                                    <svg class="size-12 text-[#D3CCBE]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </div>
                            @endif
                            <span class="flex flex-1 flex-col p-4">
                                <span class="mb-2 flex items-start justify-between gap-3">
                                    <span class="min-w-0">
                                        <span class="block truncate font-black text-[#1C1A17]">{{ $item->name }}</span>
                                        <span class="mt-1 inline-flex rounded-full bg-[#FFF7E8] px-2.5 py-1 text-[11px] font-bold text-[#9A5B12]">{{ $itemCategory }}</span>
                                    </span>
                                    <span class="shrink-0 text-right text-sm font-black text-[#E8642A]">&#8369;{{ number_format($item->price, 0) }}/{{ $itemUnit }}</span>
                                </span>
                                @if($item->description)
                                    <span class="line-clamp-2 text-xs leading-5 text-[#8A7F72]">{{ $item->description }}</span>
                                @else
                                    <span class="text-xs leading-5 text-[#8A7F72]">Tap to view details or request this item.</span>
                                @endif
                            </span>
                        </button>
                    @endforeach
                </div>
            </section>
        @endif

        @if($addOns->isNotEmpty())
            <section aria-labelledby="{{ $modalPrefix }}-addons-heading">
                <h3 id="{{ $modalPrefix }}-addons-heading" class="mb-3 text-sm font-black uppercase tracking-[0.18em] text-[#8A7F72]">Add-ons</h3>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($addOns as $addon)
                        @php $addonUnit = $addon->unit ?: 'item'; @endphp
                        <button type="button" @click="selectedAddon = {{ $addon->id }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-[#EDE4D8] bg-white text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            @if($addon->image_path)
                                <img src="{{ $addon->image_path }}" alt="{{ $addon->name }}" class="h-32 w-full object-cover">
                            @else
                                <div class="flex h-32 w-full items-center justify-center bg-[#FDF6EE]">
                                    <svg class="size-10 text-[#D3CCBE]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </div>
                            @endif
                            <span class="flex flex-1 flex-col p-4">
                                <span class="block truncate font-black text-[#1C1A17]">{{ $addon->name }}</span>
                                @if($addon->description)
                                    <span class="mt-2 line-clamp-2 text-xs leading-5 text-[#8A7F72]">{{ $addon->description }}</span>
                                @endif
                                <span class="mt-auto pt-3 text-sm font-black text-[#E8642A]">&#8369;{{ number_format($addon->price, 0) }}/{{ $addonUnit }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </section>
        @endif

        @foreach($menuItems as $item)
            @php
                $itemUnit = $item->unit ?: 'item';
                $itemCategory = $item->category ? ucfirst($item->category) : 'Menu';
            @endphp
            <div x-show="selectedItem === {{ $item->id }}" x-cloak x-effect="window.PlatePalModals?.toggle('{{ $modalPrefix }}-menu-item-preview-{{ $item->id }}', selectedItem === {{ $item->id }})" @keydown.escape.window="selectedItem = null" @click.self="selectedItem = null" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[80] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" style="display: none;">
                <div @click.stop x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                    <div class="p-6">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-black text-[#1C1A17]">{{ $item->name }}</h3>
                                <span class="mt-2 inline-flex rounded-full bg-[#FFF7E8] px-3 py-1 text-xs font-bold text-[#9A5B12]">{{ $itemCategory }}</span>
                            </div>
                            <button type="button" @click="selectedItem = null" class="rounded-lg p-2 transition-colors hover:bg-[#FDF6EE]" aria-label="Close menu item preview">
                                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @if($item->image_path)
                            <img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="mb-4 h-64 w-full rounded-xl object-cover">
                        @endif
                        <div class="mb-4 text-3xl font-black text-[#E8642A]">&#8369;{{ number_format($item->price, 0) }}/{{ $itemUnit }}</div>
                        @if($item->description)
                            <p class="mb-6 text-sm leading-relaxed text-[#1C1A17]">{{ $item->description }}</p>
                        @endif
                        <button type="button" onclick="requestItem('menu', {{ $item->id }})" @click="selectedItem = null" class="block w-full rounded-xl bg-[#E8642A] px-6 py-3 text-center text-sm font-bold text-white transition-colors hover:bg-[#F07C42]">
                            Request This Item
                        </button>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach($addOns as $addon)
            @php $addonUnit = $addon->unit ?: 'item'; @endphp
            <div x-show="selectedAddon === {{ $addon->id }}" x-cloak x-effect="window.PlatePalModals?.toggle('{{ $modalPrefix }}-addon-preview-{{ $addon->id }}', selectedAddon === {{ $addon->id }})" @keydown.escape.window="selectedAddon = null" @click.self="selectedAddon = null" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[80] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" style="display: none;">
                <div @click.stop x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
                    <div class="p-6">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <h3 class="text-2xl font-black text-[#1C1A17]">{{ $addon->name }}</h3>
                            <button type="button" @click="selectedAddon = null" class="rounded-lg p-2 transition-colors hover:bg-[#FDF6EE]" aria-label="Close add-on preview">
                                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @if($addon->image_path)
                            <img src="{{ $addon->image_path }}" alt="{{ $addon->name }}" class="mb-4 h-64 w-full rounded-xl object-cover">
                        @endif
                        <div class="mb-4 text-3xl font-black text-[#E8642A]">&#8369;{{ number_format($addon->price, 0) }}/{{ $addonUnit }}</div>
                        @if($addon->description)
                            <p class="mb-6 text-sm leading-relaxed text-[#1C1A17]">{{ $addon->description }}</p>
                        @endif
                        <button type="button" onclick="requestItem('addon', {{ $addon->id }})" @click="selectedAddon = null" class="block w-full rounded-xl bg-[#E8642A] px-6 py-3 text-center text-sm font-bold text-white transition-colors hover:bg-[#F07C42]">
                            Request This Add-on
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
