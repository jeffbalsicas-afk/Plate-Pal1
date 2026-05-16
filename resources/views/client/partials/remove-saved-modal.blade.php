<template x-teleport="body">
    <div
        x-show="showRemoveSavedModal"
        x-cloak
        x-transition.opacity
        x-effect="window.PlatePalModals?.toggle('saved-caterer-confirm', showRemoveSavedModal)"
        @keydown.escape.window="closeRemoveSavedModal()"
        @click.self="closeRemoveSavedModal()"
        class="fixed inset-0 z-[90] flex items-center justify-center bg-black/50 p-4"
        role="dialog"
        aria-modal="true"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-5 flex items-start gap-4">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-[#FDF6EE]">
                    @include('client.partials.sidebar-icon', ['name' => 'heart', 'class' => 'size-5 stroke-[#E8642A]'])
                </div>
                <div class="min-w-0">
                    <h2 class="text-xl font-black text-[#1C1A17]" x-text="removeTarget.action === 'save' ? 'Save caterer?' : 'Remove saved caterer?'">Remove saved caterer?</h2>
                    <p class="mt-2 text-sm leading-6 text-[#8A7F72]">
                        <span x-text="removeTarget.action === 'save' ? 'This will add' : 'This will remove'">This will remove</span>
                        <span class="font-bold text-[#1C1A17]" x-text="removeTarget.name"></span>
                        <span x-text="removeTarget.action === 'save' ? 'to your saved list.' : 'from your saved list.'">from your saved list.</span>
                    </p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-2">
                <button
                    type="button"
                    @click="closeRemoveSavedModal()"
                    class="w-full px-4 py-3 rounded-xl border border-[#EDE4D8] text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :form="removeTarget.formId"
                    :disabled="!removeTarget.formId"
                    class="w-full px-4 py-3 rounded-xl bg-[#E8642A] text-sm font-bold text-white hover:bg-[#F07C42] disabled:cursor-not-allowed disabled:bg-[#E9A17D] transition-colors"
                    x-text="removeTarget.action === 'save' ? 'Save' : 'Remove'"
                >
                    Remove
                </button>
            </div>
        </div>
    </div>
</template>
