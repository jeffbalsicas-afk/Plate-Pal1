@if($menuItems->isNotEmpty() || $addOns->isNotEmpty())
    <div class="grid gap-4 lg:grid-cols-2">
        @if($menuItems->isNotEmpty())
            <div>
                <div class="mb-2 flex items-center justify-between gap-3">
                    <label class="text-xs font-bold text-[#1C1A17]">Menu Items <span class="font-medium text-[#8A7F72]">(Optional)</span></label>
                    <span class="text-[11px] font-bold text-[#8A7F72]">{{ $menuItems->count() }} available</span>
                </div>
                <div class="max-h-64 space-y-2 overflow-y-auto rounded-xl border border-[#EDE4D8] bg-[#FDF6EE] p-2">
                    @foreach($menuItems as $item)
                        @php
                            $itemUnit = $item->unit ?: 'item';
                            $itemCategory = $item->category ? ucfirst($item->category) : 'Menu';
                        @endphp
                        <label class="group flex cursor-pointer items-start gap-3 rounded-lg border border-transparent bg-white/70 p-3 transition hover:border-[#F3C5A9] hover:bg-white">
                            <input type="checkbox" name="menu_items[]" value="{{ $item->id }}" @checked(in_array($item->id, old('menu_items', []))) class="mt-1 rounded border-[#EDE4D8] text-[#E8642A] focus:ring-[#E8642A]">
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-bold text-[#1C1A17]">{{ $item->name }}</span>
                                <span class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-[#8A7F72]">
                                    <span class="rounded-full bg-[#FFF7E8] px-2 py-0.5 font-bold text-[#9A5B12]">{{ $itemCategory }}</span>
                                    @if($item->description)
                                        <span class="line-clamp-1">{{ $item->description }}</span>
                                    @endif
                                </span>
                            </span>
                            <span class="shrink-0 text-right text-xs font-black text-[#E8642A]">&#8369;{{ number_format($item->price, 0) }}/{{ $itemUnit }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        @if($addOns->isNotEmpty())
            <div>
                <div class="mb-2 flex items-center justify-between gap-3">
                    <label class="text-xs font-bold text-[#1C1A17]">Add-ons <span class="font-medium text-[#8A7F72]">(Optional)</span></label>
                    <span class="text-[11px] font-bold text-[#8A7F72]">{{ $addOns->count() }} available</span>
                </div>
                <div class="max-h-64 space-y-2 overflow-y-auto rounded-xl border border-[#EDE4D8] bg-[#FDF6EE] p-2">
                    @foreach($addOns as $addon)
                        @php $addonUnit = $addon->unit ?: 'item'; @endphp
                        <label class="group flex cursor-pointer items-start gap-3 rounded-lg border border-transparent bg-white/70 p-3 transition hover:border-[#F3C5A9] hover:bg-white">
                            <input type="checkbox" name="addons[]" value="{{ $addon->id }}" @checked(in_array($addon->id, old('addons', []))) class="mt-1 rounded border-[#EDE4D8] text-[#E8642A] focus:ring-[#E8642A]">
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-bold text-[#1C1A17]">{{ $addon->name }}</span>
                                @if($addon->description)
                                    <span class="mt-1 block line-clamp-1 text-[11px] text-[#8A7F72]">{{ $addon->description }}</span>
                                @endif
                            </span>
                            <span class="shrink-0 text-right text-xs font-black text-[#E8642A]">&#8369;{{ number_format($addon->price, 0) }}/{{ $addonUnit }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif
