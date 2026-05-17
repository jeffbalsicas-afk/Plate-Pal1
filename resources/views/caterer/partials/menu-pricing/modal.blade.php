<template x-teleport="body">
    <div
        x-show="showForm"
        x-cloak
        x-transition.opacity
        x-effect="window.PlatePalModals?.toggle('menu-pricing-form', showForm)"
        @keydown.escape.window="showForm = false"
        @click.self="showForm = false"
        class="fixed inset-0 z-[80] flex items-center justify-center bg-black/50 p-4"
        role="dialog"
        aria-modal="true"
    >
        <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-4 shadow-xl sm:p-6">
            <div class="flex items-start justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-xl font-black text-[#1C1A17]">Add <span x-text="labels[formType]"></span></h2>
                    <p class="text-xs text-[#8A7F72] mt-1">Saved entries are available to clients immediately.</p>
                </div>
                <button type="button" @click="showForm = false" class="size-9 rounded-xl border border-[#EDE4D8] flex items-center justify-center hover:bg-[#FDF6EE] transition-colors">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'close', 'class' => 'size-5 stroke-[#8A7F72]'])
                </button>
            </div>

            @include('caterer.partials.menu-pricing.package-form')
            @include('caterer.partials.menu-pricing.item-form')
            @include('caterer.partials.menu-pricing.addon-form')
        </div>
    </div>
</template>
