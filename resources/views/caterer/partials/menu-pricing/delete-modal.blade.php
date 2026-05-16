<template x-teleport="body">
    <div
        x-show="showDeleteModal"
        x-cloak
        x-transition.opacity
        x-effect="window.PlatePalModals?.toggle('menu-pricing-delete', showDeleteModal)"
        @keydown.escape.window="closeDeleteModal()"
        @click.self="closeDeleteModal()"
        class="fixed inset-0 z-[90] flex items-center justify-center bg-black/50 p-4"
        role="dialog"
        aria-modal="true"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-5 flex items-start gap-4">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-red-50">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'trash', 'class' => 'size-5 stroke-red-600'])
                </div>
                <div class="min-w-0">
                    <h2 class="text-xl font-black text-[#1C1A17]">Delete <span x-text="deleteTarget.type"></span>?</h2>
                    <p class="mt-2 text-sm leading-6 text-[#8A7F72]">
                        This will permanently remove
                        <span class="font-bold text-[#1C1A17]" x-text="deleteTarget.name"></span>
                        from your menu.
                    </p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-2">
                <button
                    type="button"
                    @click="closeDeleteModal()"
                    class="w-full px-4 py-3 rounded-xl border border-[#EDE4D8] text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :form="deleteTarget.formId"
                    :disabled="!deleteTarget.formId"
                    class="w-full px-4 py-3 rounded-xl bg-red-600 text-sm font-bold text-white hover:bg-red-700 disabled:cursor-not-allowed disabled:bg-red-300 transition-colors"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>
