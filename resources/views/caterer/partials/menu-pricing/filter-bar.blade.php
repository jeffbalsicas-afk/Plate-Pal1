<div class="bg-white rounded-2xl border border-[#EDE4D8] p-4 mb-5">
    <form method="GET" class="grid lg:grid-cols-[minmax(0,1fr)_220px_220px_auto] gap-3">
        <input type="hidden" name="tab" value="{{ $activeTab }}">
        <label class="relative block">
            <span class="absolute left-4 top-1/2 -translate-y-1/2">
                @include('caterer.partials.menu-pricing.icon', ['name' => 'search', 'class' => 'size-4 stroke-[#8A7F72]'])
            </span>
            <input type="text" name="search" placeholder="Search {{ $activeTab === 'packages' ? 'packages' : ($activeTab === 'items' ? 'menu items' : 'add-ons') }}..." value="{{ $search }}" class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
        </label>
        <select name="sort" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
            <option value="newest" @selected($sort === 'newest')>Newest first</option>
            <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
            <option value="price_asc" @selected($sort === 'price_asc')>Lowest price</option>
            <option value="price_desc" @selected($sort === 'price_desc')>Highest price</option>
        </select>
        <select name="category" @disabled($activeTab !== 'items') class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A] disabled:cursor-not-allowed disabled:opacity-50">
            <option value="">All categories</option>
            <option value="main" @selected($category === 'main')>Main Dish</option>
            <option value="side" @selected($category === 'side')>Side Dish</option>
            <option value="dessert" @selected($category === 'dessert')>Dessert</option>
            <option value="beverage" @selected($category === 'beverage')>Beverage</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-3 rounded-xl bg-[#1C1A17] text-white text-sm font-bold hover:bg-black transition-colors">Apply</button>
            @if($search !== '' || $sort !== 'newest' || $category)
                <a href="{{ route('caterer.menu-pricing', ['tab' => $activeTab]) }}" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border border-[#EDE4D8] text-sm font-bold text-[#8A6D3F] hover:bg-[#FDF6EE] transition-colors">Clear</a>
            @endif
        </div>
    </form>
</div>
