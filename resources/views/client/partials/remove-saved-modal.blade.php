<div
    x-show="showRemoveSavedModal"
    x-cloak
    x-transition
    @keydown.escape.window="closeRemoveSavedModal()"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
>
    <div @click.outside="closeRemoveSavedModal()" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <div class="mb-5 flex items-start gap-4">
            <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-[#FDF6EE]">
                @include('client.partials.sidebar-icon', ['name' => 'heart', 'class' => 'size-5 stroke-[#E8642A]'])
            </div>
            <div class="min-w-0">
                <h2 class="text-xl font-black text-[#1C1A17]">Remove saved caterer?</h2>
                <p class="mt-2 text-sm leading-6 text-[#8A7F72]">
                    This will remove
                    <span class="font-bold text-[#1C1A17]" x-text="removeTarget.name"></span>
                    from your saved list.
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
            >
                Remove
            </button>
        </div>
    </div>
</div>
