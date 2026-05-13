<form x-show="formType === 'packages'" action="{{ route('menu.packages.store') }}" method="POST" class="space-y-4">
    @csrf
    <label class="block">
        <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Package Name</span>
        <input type="text" name="name" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
    </label>
    <div class="grid sm:grid-cols-2 gap-3">
        <label class="block">
            <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Package Price</span>
            <input type="number" name="price" step="0.01" min="0" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
        </label>
        <label class="block">
            <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Minimum Guests</span>
            <input type="number" name="min_guests" min="1" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
        </label>
    </div>
    <label class="block">
        <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Food Description</span>
        <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A] resize-none"></textarea>
    </label>
    @include('caterer.partials.menu-pricing.form-actions')
</form>
