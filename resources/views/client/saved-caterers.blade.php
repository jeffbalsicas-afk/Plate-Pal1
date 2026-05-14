@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Saved Caterers – PlatePal"
    heading="Saved Caterers"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        @include('client.partials.sidebar')
    </x-slot:sidebar>

    <div
        x-data="{
            showRemoveSavedModal: false,
            removeTarget: {
                formId: '',
                name: ''
            },
            openRemoveSavedModal(formId, name) {
                this.removeTarget = { formId, name };
                this.showRemoveSavedModal = true;
            },
            closeRemoveSavedModal() {
                this.showRemoveSavedModal = false;
                this.removeTarget = { formId: '', name: '' };
            }
        }"
    >
    {{-- Stats --}}
    <x-client-stats 
        :activeBookings="$activeBookings" 
        :savedCaterersCount="$savedCaterers->total()" 
        :unreadMessages="$unreadMessages" 
        :completedEvents="$completedEvents" 
    />

    {{-- Content --}}
    <div class="bg-white rounded-2xl px-7 py-6 border border-[#EDE4D8] mb-5 drop-shadow-md">
        <h2 class="text-xl font-black text-[#1C1A17] mb-6">Your Saved Caterers</h2>

        @if($savedCaterers->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-[#EDE4D8] mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <p class="text-[#8A7F72] text-lg font-medium mb-4">No saved caterers yet</p>
                <a href="{{ route('client.browse') }}" class="inline-block px-6 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                    Browse Caterers
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($savedCaterers as $saved)
                @php
                    $catererName = $saved->caterer->business_name ?? $saved->caterer->name;
                    $removeFormId = 'remove-saved-caterer-'.$saved->caterer->getKey();
                @endphp
                <div class="bg-white rounded-2xl overflow-hidden border border-[#EDE4D8] hover:shadow-lg transition-shadow">
                    <div class="relative h-40 overflow-hidden bg-gradient-to-br from-[#FDF6EE] to-[#F5EFEA]">
                        <img src="{{ $saved->caterer->profile_image ?? '/assets/Pusit.png' }}" alt="{{ $catererName }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        <button
                            type="button"
                            @click="openRemoveSavedModal(@js($removeFormId), @js($catererName))"
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center hover:bg-[#FDF6EE] transition-colors shadow-md"
                            aria-label="Remove {{ $catererName }} from saved caterers"
                        >
                            <svg class="w-4 h-4 fill-[#E8642A]" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                        <form id="{{ $removeFormId }}" action="{{ route('client.saved-caterers.toggle', $saved->caterer) }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <h4 class="text-sm font-bold text-[#1C1A17]">{{ $catererName }}</h4>
                                <p class="text-xs text-[#8A7F72]">📍 {{ $saved->caterer->barangay ?? 'Tagum City' }}</p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <svg class="size-4 fill-[#FBBF24]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-bold text-[#1C1A17]">{{ $saved->caterer->rating ?? 4.5 }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-[#E8642A] font-medium mb-2">{{ $saved->caterer->cuisine ?? 'Filipino Cuisine' }}</p>
                        <p class="text-xs text-[#8A7F72] mb-3">{{ $saved->caterer->reviews_count ?? 0 }} reviews</p>
                        <div class="flex items-center justify-between pt-3 border-t border-[#EDE4D8]">
                            <span class="text-sm font-bold text-[#E8642A]">PHP {{ $saved->caterer->price_min ?? 300 }}-{{ $saved->caterer->price_max ?? 500 }}/head</span>
                            <a href="{{ route('caterer.detail', $saved->caterer) }}" class="px-3 py-1.5 rounded-lg bg-[#E8642A] text-white text-xs font-bold hover:bg-[#F07C42] transition-colors">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $savedCaterers->links() }}
            </div>
        @endif
    </div>

        @include('client.partials.remove-saved-modal')
    </div>
</x-dashboard-layout>
