<div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-sm p-2 mb-5 overflow-x-auto">
    <div class="grid grid-cols-3 min-w-[520px] gap-2">
        @foreach($menuTypes as $tabKey => $menuType)
            <a
                href="{{ route('caterer.menu-pricing', ['tab' => $tabKey]) }}"
                class="text-center rounded-xl px-3 py-3 text-sm font-bold transition-colors focus:outline-none {{ $activeTab === $tabKey ? 'bg-[#E8642A] text-white shadow-sm' : 'text-[#8A6D3F] hover:bg-[#FDF6EE]' }}"
            >
                {{ $menuType['label'] }}
                <span class="text-xs font-bold ml-1 {{ $activeTab === $tabKey ? 'text-white/80' : 'text-[#8A7F72]' }}">({{ $menuType['entries']->total() }})</span>
            </a>
        @endforeach
    </div>
</div>
