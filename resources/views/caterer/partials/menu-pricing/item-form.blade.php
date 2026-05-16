<form x-show="formType === 'items'" action="{{ route('menu.items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <label class="block">
        <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Item Name</span>
        <input type="text"
               name="name"
               placeholder="e.g., Lechon Kawali"
               class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
    </label>
    <div x-data="{ fileName: '' }">
        <label class="block text-xs font-bold text-[#1C1A17] mb-1.5">Item Image (Optional)</label>
        <div class="flex items-center gap-3">
            <label class="flex-1 cursor-pointer">
                <input type="file" name="image" accept="image/*" class="hidden" @change="fileName = $event.target.files[0]?.name || ''">
                <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] hover:border-[#E8642A] transition-colors">
                    <span class="text-sm text-[#8A7F72]" x-text="fileName || 'Choose image file'"></span>
                    <svg class="size-5 text-[#E8642A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </label>
            <button type="button" x-show="fileName" @click="fileName = ''; $el.previousElementSibling.querySelector('input').value = ''" class="px-3 py-3 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-xs text-[#8A7F72] mt-1">JPG, PNG up to 2MB</p>
    </div>
    <div class="grid sm:grid-cols-2 gap-3">
        <label class="block">
            <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Price</span>
            <input type="number"
                   name="price"
                   step="0.01"
                   min="0"
                   placeholder="eg., 100.00"
                   class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
        </label>
        <label class="block">
            <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Unit</span>
            <select name="unit" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
                <option value="">Select unit</option>
                <option value="head">Per Head</option>
                <option value="tray">Per Tray</option>
                <option value="whole">Whole</option>
                <option value="bottle">Per Bottle</option>
                <option value="box">Per Box</option>
            </select>
        </label>
    </div>
    <label class="block">
        <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Category</span>
        <select name="category" class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
            <option value="">Select category</option>
            <option value="main">Main Dish</option>
            <option value="side">Side Dish</option>
            <option value="dessert">Dessert</option>
            <option value="beverage">Beverage</option>
        </select>
    </label>
    <label class="block">
        <span class="block text-xs font-bold text-[#1C1A17] mb-1.5">Food Description</span>
        <textarea name="description"
                  rows="4"
                  placeholder="Describe your dish..."
                  class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A] resize-none"></textarea>
    </label>
    @include('caterer.partials.menu-pricing.form-actions')
</form>
