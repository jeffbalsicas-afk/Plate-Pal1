<div class="rounded-2xl border border-[#EDE4D8] bg-white p-4 shadow-sm">
    <div class="mb-3 flex items-start justify-between gap-3">
        <div>
            <div class="text-sm font-black text-[#1C1A17]">Menu Overview</div>
            <div class="mt-1 text-xs text-[#8A7F72]">Total count of your menu offerings.</div>
        </div>
        <span class="rounded-full bg-[#F6EFE7] px-2.5 py-1 text-[11px] font-bold text-[#8A6D3F]">All Active</span>
    </div>
    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-xl border border-[#CDE8CC] bg-[#F6FBF5] p-3">
            <div class="mb-3 flex items-center justify-center">
                <div class="flex size-9 items-center justify-center rounded-lg bg-[#EAF5E9]">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'check', 'class' => 'size-4 stroke-[#2E7D32]'])
                </div>
            </div>
            <div class="text-2xl font-black leading-none text-center text-[#1C1A17]">{{ $menuSummary['packages'] }}</div>
            <div class="mt-1 text-xs font-bold text-center text-[#2E7D32]">Packages</div>
        </div>
        <div class="rounded-xl border border-[#D4E4F7] bg-[#F5F9FF] p-3">
            <div class="mb-3 flex items-center justify-center">
                <div class="flex size-9 items-center justify-center rounded-lg bg-[#E3F2FD]">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'check', 'class' => 'size-4 stroke-[#1976D2]'])
                </div>
            </div>
            <div class="text-2xl font-black leading-none text-center text-[#1C1A17]">{{ $menuSummary['items'] }}</div>
            <div class="mt-1 text-xs font-bold text-center text-[#1976D2]">Menu Items</div>
        </div>
        <div class="rounded-xl border border-[#F3D68B] bg-[#FFFCF0] p-3">
            <div class="mb-3 flex items-center justify-center">
                <div class="flex size-9 items-center justify-center rounded-lg bg-[#FFF8E1]">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'check', 'class' => 'size-4 stroke-[#B26A00]'])
                </div>
            </div>
            <div class="text-2xl font-black leading-none text-center text-[#1C1A17]">{{ $menuSummary['addons'] }}</div>
            <div class="mt-1 text-xs font-bold text-center text-[#B26A00]">Add-ons</div>
        </div>
    </div>
</div>
