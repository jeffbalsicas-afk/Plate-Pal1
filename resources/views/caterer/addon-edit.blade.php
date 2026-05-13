@php
    $initials = strtoupper(substr(auth()->user()->name, 0, 1) . (str_contains(auth()->user()->name, ' ') ? substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) : ''));
    $displayName = auth()->user()->business_name ?? auth()->user()->name;
@endphp

<x-dashboard-layout
    title="Edit Add-on - PlatePal"
    heading="Caterer Dashboard"
    :username="$displayName"
    :usersub="auth()->user()->barangay ?? ''"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors font-medium text-sm">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            Dashboard
        </a>
        <a href="{{ route('caterer.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Bookings
            </div>
        </a>
        <a href="{{ route('caterer.menu-pricing') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Menu & Pricing
        </a>
        <a href="{{ route('caterer.messages') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Messages
            </div>
        </a>
        <a href="{{ route('caterer.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            Reviews
        </a>
        <a href="{{ route('caterer.earnings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Earnings
        </a>
    </x-slot:sidebar>

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('caterer.menu-pricing') }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors mb-6">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Menu & Pricing
        </a>

        @if($errors->any())
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-lg overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-[#EDE4D8]">
                <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17] mb-2">Edit Add-on</h1>
                <p class="text-base sm:text-lg text-[#8A6D3F]">Update your add-on details</p>
            </div>

            <form method="POST" action="{{ route('menu.addons.update', $addon) }}" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-bold text-[#1C1A17] mb-2">Add-on Name *</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $addon->name) }}"
                        required
                        placeholder="e.g., Extra Dessert Sampler"
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-bold text-[#1C1A17] mb-2">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Describe your add-on..."
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A] resize-none"
                    >{{ old('description', $addon->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-bold text-[#1C1A17] mb-2">Price *</label>
                        <input
                            id="price"
                            name="price"
                            type="number"
                            step="0.01"
                            value="{{ old('price', $addon->price) }}"
                            required
                            placeholder="0.00"
                            class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                        >
                        @error('price')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit" class="block text-sm font-bold text-[#1C1A17] mb-2">Unit *</label>
                        <select
                            id="unit"
                            name="unit"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                        >
                            <option value="">Select unit</option>
                            <option value="head" @selected(old('unit', $addon->unit) === 'head')>Per Head</option>
                            <option value="tray" @selected(old('unit', $addon->unit) === 'tray')>Per Tray</option>
                            <option value="bottle" @selected(old('unit', $addon->unit) === 'bottle')>Per Bottle</option>
                            <option value="box" @selected(old('unit', $addon->unit) === 'box')>Per Box</option>
                        </select>
                        @error('unit')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @error('status')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

                <div class="rounded-xl bg-sky-50 border border-sky-200 px-4 py-3 text-sm text-sky-800">
                    Approved changes become live after admin review.
                </div>

                <div class="grid sm:grid-cols-3 gap-3 pt-4">
                    <button
                        type="submit"
                        name="status"
                        value="draft"
                        class="px-5 py-3 rounded-xl border border-[#EDE4D8] text-[#1C1A17] text-sm font-bold hover:bg-[#FDF6EE] transition-colors"
                    >
                        Save Draft
                    </button>
                    <button
                        type="submit"
                        name="status"
                        value="pending"
                        class="px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors"
                    >
                        Submit for Approval
                    </button>
                    <a
                        href="{{ route('caterer.menu-pricing') }}"
                        class="inline-flex justify-center px-5 py-3 rounded-xl border border-[#E8642A] text-[#E8642A] text-sm font-bold hover:bg-[#FDF6EE] transition-colors"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
