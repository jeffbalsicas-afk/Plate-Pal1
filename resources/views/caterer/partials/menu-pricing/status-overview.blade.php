<div class="rounded-2xl border border-[#EDE4D8] bg-white p-4 shadow-sm">
    <div class="mb-3 flex items-start justify-between gap-3">
        <div>
            <div class="text-sm font-black text-[#1C1A17]">Publishing Status</div>
            <div class="mt-1 text-xs text-[#8A7F72]">Approval overview for all menu entries.</div>
        </div>
        <span class="rounded-full bg-[#F6EFE7] px-2.5 py-1 text-[11px] font-bold text-[#8A6D3F]">Admin review</span>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div class="rounded-xl border border-[#CDE8CC] bg-[#F6FBF5] p-3">
            <div class="mb-3 flex items-center justify-between gap-2">
                <div class="flex size-9 items-center justify-center rounded-lg bg-[#EAF5E9]">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'check', 'class' => 'size-4 stroke-[#2E7D32]'])
                </div>
                <span class="rounded-full bg-[#EAF5E9] px-2 py-1 text-[11px] font-bold text-[#2E7D32]">Live</span>
            </div>
            <div class="text-2xl font-black leading-none text-[#1C1A17]">{{ $menuSummary['live'] }}</div>
            <div class="mt-1 text-xs font-bold text-[#2E7D32]">Published</div>
        </div>
        <div class="rounded-xl border border-[#F3D68B] bg-[#FFFCF0] p-3">
            <div class="mb-3 flex items-center justify-between gap-2">
                <div class="flex size-9 items-center justify-center rounded-lg bg-[#FFF8E1]">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'clock', 'class' => 'size-4 stroke-[#B26A00]'])
                </div>
                <span class="rounded-full bg-[#FFF8E1] px-2 py-1 text-[11px] font-bold text-[#B26A00]">Pending</span>
            </div>
            <div class="text-2xl font-black leading-none text-[#1C1A17]">{{ $menuSummary['pending'] }}</div>
            <div class="mt-1 text-xs font-bold text-[#B26A00]">Under Review</div>
        </div>
    </div>
</div>
