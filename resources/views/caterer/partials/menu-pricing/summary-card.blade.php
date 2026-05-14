<button
    type="button"
    @click="tab = @js($tabKey)"
    class="group relative overflow-hidden rounded-2xl border bg-white p-4 text-left shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
    :class="tab === @js($tabKey) ? 'border-[#E8642A] bg-[#FFF8F3]' : 'border-[#EDE4D8] hover:bg-[#FDF6EE]'"
>
    <div class="absolute inset-x-0 top-0 h-1 {{ $menuType['accent'] }}"></div>
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex size-10 items-center justify-center rounded-xl {{ $menuType['iconWrap'] }}">
            @include('caterer.partials.menu-pricing.icon', ['name' => $menuType['icon'], 'class' => $menuType['iconClass']])
        </div>
        <span class="rounded-full px-2 py-1 text-[11px] font-bold" :class="tab === @js($tabKey) ? 'bg-[#E8642A] text-white' : 'bg-[#F6EFE7] text-[#8A6D3F]'">Open</span>
    </div>
    <div class="text-[32px] font-black leading-none text-[#1C1A17]">{{ $menuType['count'] }}</div>
    <div class="mt-1 text-sm font-black text-[#1C1A17]">{{ $menuType['label'] }}</div>
    <div class="mt-2 text-xs text-[#8A7F72]">{{ $menuType['subtitle'] }}</div>
</button>
