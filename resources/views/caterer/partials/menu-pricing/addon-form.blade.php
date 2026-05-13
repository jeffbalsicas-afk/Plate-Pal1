<form x-show="formType === 'addons'" action="{{ route('menu.addons.store') }}" method="POST" class="space-y-4">
    @csrf
    <label class="block">
        <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Add-on Name</span>
        <input type="text" name="name" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
    </label>
    <div class="grid sm:grid-cols-2 gap-3">
        <label class="block">
            <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Price</span>
            <input type="number" name="price" step="0.01" min="0" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
        </label>
        <label class="block">
            <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Unit</span>
            <select name="unit" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
                <option value="">Select unit</option>
                <option value="head">Per Head</option>
                <option value="tray">Per Tray</option>
                <option value="bottle">Per Bottle</option>
                <option value="box">Per Box</option>
            </select>
        </label>
    </div>
    <label class="block">
        <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Food Description</span>
        <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A] resize-none"></textarea>
    </label>
    @include('caterer.partials.menu-pricing.form-actions')
</form>
