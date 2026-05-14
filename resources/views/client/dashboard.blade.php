@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

    $stats = [
        ['path' => 'M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'value' => $activeBookings, 'label' => 'Active Bookings', 'tone' => 'bg-[#EAF8F4] text-[#E05C2A] border-[#E3BC8C]'],
        ['path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'value' => $savedCaterersCount, 'label' => 'Saved Caterers', 'tone' => 'bg-[#EAF8F4] text-[#BE3455] border-[#E3BC8C]'],
        ['path' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z', 'value' => $unreadMessages, 'label' => 'Unread Messages', 'tone' => 'bg-[#EAF8F4] text-[#2563A6] border-[#E3BC8C]'],
        ['path' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z', 'value' => $completedEvents, 'label' => 'Completed Events', 'tone' => 'bg-[#EAF8F4] text-[#9A5B12] border-[#E3BC8C]'],
    ];
@endphp

<x-dashboard-layout
    title="Client Dashboard - PlatePal"
    heading="Client Dashboard"
    :username="$user->name"
    :initials="$initials"
>
    <x-slot:sidebar>
        @include('client.partials.sidebar')
    </x-slot:sidebar>

    <div class="space-y-5">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        
        <section class="overflow-hidden rounded-[28px] border shadow-lg border-[#d7ece4] bg-[#F4FAF7]">
            <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_330px]">
                <div class="p-6 sm:p-8">
                    <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-orange bg-white px-3 py-1.5 text-xs font-black text-brand-orange">
                        <span class="size-2 rounded-full bg-brand-orange"></span>
                        Event planning hub
                    </div>
                    <h1 class="max-w-2xl text-3xl font-black leading-tight text-[#0f0e0d] sm:text-4xl">Find food that fits the moment.</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-[#343635]">Search local caterers, save your favorites, and keep bookings organized from one calm workspace.</p>
                    <form action="{{ route('client.browse') }}" method="GET" class="mt-6 flex flex-col gap-3 rounded-2xl border border-[#fffefc] bg-[#f0ede7] p-2 sm:flex-row">
                        <label class="relative flex-1">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2">
                                <svg class="size-4 stroke-brand-cream-dark" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/></svg>
                            </span>
                            <input
                                type="text"
                                name="search"
                                placeholder="Search cuisine, caterer, or barangay"
                                class="h-12 w-full rounded-xl border border-transparent bg-[#F8FBF9] pl-10 pr-4 text-sm text-[#181a19] placeholder:text-[#7A8983] focus:border-[#ff7b00] focus:outline-none"
                            >
                        </label>
                        <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-brand-orange px-5 text-sm font-black text-white transition-colors hover:bg-[#F07C42]">
                            Discover
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
                <div class="border-t border-[#DDE8E2] bg-white p-6 lg:border-l lg:border-t-0">
                    <div class="mb-4 text-xs font-black uppercase text-[#66756F]">Today</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-[#DDE8E2] bg-[#F8FBF9] p-4">
                            <div class="text-3xl font-black text-brand-orange">{{ $activeBookings }}</div>
                            <div class="mt-1 text-xs font-bold text-[#66756F]">Active plans</div>
                        </div>
                        <div class="rounded-2xl border border-[#DDE8E2] bg-[#F8FBF9] p-4">
                            <div class="text-3xl font-black text-brand-cream-dark">{{ $savedCaterersCount }}</div>
                            <div class="mt-1 text-xs font-bold text-[#66756F]">Saved picks</div>
                        </div>
                    </div>
                    <a href="{{ route('client.browse') }}" class="mt-4 flex items-center justify-between rounded-2xl border border-[#DDE8E2] bg-[#17201D] px-4 py-3 text-sm font-black text-white transition-colors hover:bg-[#25312D]">
                        Browse caterers
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-2 gap-3.5 lg:grid-cols-4">
            @foreach($stats as $stat)
                <div class="rounded-3xl border border-[#DDE8E2] bg-white p-4 shadow-sm">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div class="flex size-11 items-center justify-center rounded-2xl border {{ $stat['tone'] }}">
                            <svg class="size-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['path'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-[30px] font-black leading-none text-[#17201D]">{{ $stat['value'] }}</div>
                    <div class="mt-1 text-xs font-bold text-[#66756F]">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <section class="rounded-3xl border border-[#DDE8E2] bg-gradient-to-br from-white to-[#F8FBF9] p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-orange to-brand-orange-light shadow-sm">
                            <svg class="size-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-[#17201D]">Upcoming Events</h2>
                            <p class="mt-0.5 text-xs text-[#66756F]">Requests and confirmed bookings to track.</p>
                        </div>
                    </div>
                    <a href="{{ route('client.bookings') }}" class="rounded-full bg-[#EAF8F4] px-3 py-1.5 text-xs font-black text-brand-orange transition-colors hover:bg-[#f3ecd7]">View All</a>
                </div>
                @if($upcomingBookings->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[#DDE8E2] bg-[#F8FBF9] p-6 text-center">
                        <p class="text-sm font-black text-[#17201D]">No upcoming bookings yet.</p>
                        <p class="mt-1 text-xs text-[#66756F]">Discover caterers when you are ready to plan.</p>
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach($upcomingBookings as $booking)
                            <div class="rounded-2xl border border-[#DDE8E2] bg-[#F8FBF9] p-3.5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-black text-[#17201D]">{{ $booking->event_title }}</div>
                                        <div class="mt-1 text-xs text-[#66756F]">{{ $booking->caterer->business_name ?? $booking->caterer->name }}</div>
                                    </div>
                                    <span class="rounded-full px-2.5 py-1 text-[11px] font-black {{ $booking->status === 'confirmed' ? 'bg-[#EAF8F4] text-[#0F766E]' : 'bg-[#FFF7E8] text-[#9A5B12]' }}">{{ ucfirst($booking->status) }}</span>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-3 text-[11.5px] font-bold text-[#66756F]">
                                    <span>{{ $booking->event_date->format('M d, Y') }}</span>
                                    <span>{{ number_format($booking->guests) }} guests</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="rounded-3xl border border-[#DDE8E2] bg-gradient-to-br from-white to-[#EFF6FF] p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#2563A6] to-[#3B82F6] shadow-sm">
                            <svg class="size-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-[#17201D]">Recent Messages</h2>
                            <p class="mt-0.5 text-xs text-[#66756F]">Latest caterer conversations.</p>
                        </div>
                    </div>
                    <a href="{{ route('messages.index') }}" class="rounded-full bg-[#EFF6FF] px-3 py-1.5 text-xs font-black text-brand-orange transition-colors hover:bg-[#f3ecd7]">View All</a>
                </div>
                @if($recentMessages->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[#DDE8E2] bg-[#F8FBF9] p-6 text-center">
                        <p class="text-sm font-black text-[#17201D]">No messages yet.</p>
                        <p class="mt-1 text-xs text-[#66756F]">Messages from caterers will appear here.</p>
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach($recentMessages as $message)
                            <a href="{{ route('messages.show', $message->caterer) }}" class="block rounded-2xl border border-[#DDE8E2] bg-[#F8FBF9] p-3.5 transition-colors hover:bg-[#EAF8F4]">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="truncate text-sm font-black text-[#17201D]">{{ $message->caterer->business_name ?? $message->caterer->name }}</span>
                                    @if(!$message->is_read && $message->sender === 'caterer')
                                        <span class="flex items-center gap-1 whitespace-nowrap text-[11px] font-black text-[#0F766E]">
                                            <span class="size-1.5 rounded-full bg-[#0F766E]"></span>
                                            {{ $message->created_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="whitespace-nowrap text-[11px] font-bold text-[#66756F]">{{ $message->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <div class="mt-1 text-xs text-[#66756F]">{{ Str::limit($message->previewText(), 54) }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        <section class="rounded-3xl border border-[#DDE8E2] bg-gradient-to-br from-white via-[#FFF7E8]/30 to-white p-6 shadow-sm" x-data="{
            showRemoveSavedModal: false,
            removeTarget: { formId: '', name: '', action: 'remove' },
            openSavedCatererModal(formId, name, action = 'remove') {
                this.removeTarget = { formId, name, action };
                this.showRemoveSavedModal = true;
            },
            openRemoveSavedModal(formId, name) {
                this.openSavedCatererModal(formId, name, 'remove');
            },
            closeRemoveSavedModal() {
                this.showRemoveSavedModal = false;
                this.removeTarget = { formId: '', name: '', action: 'remove' };
            }
        }" @open-remove-modal.window="openRemoveSavedModal($event.detail.formId, $event.detail.name)">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#D99A20] to-[#F59E0B] shadow-sm">
                        <svg class="size-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-[#17201D]">Featured Caterers Near You</h2>
                        <p class="mt-0.5 text-xs text-[#66756F]">A few highly rated options to start with.</p>
                    </div>
                </div>
                <a href="{{ route('client.browse') }}" class="rounded-full bg-[#EAF8F4] px-3 py-1.5 text-xs font-black text-brand-orange transition-colors hover:bg-[#f3ecd7]">View All</a>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse($featuredCaterers as $caterer)
                    @php
                        $detailUrl = route('caterer.detail', $caterer['id']);
                        $toggleFormId = 'toggle-saved-' . $caterer['id'];
                    @endphp
                    <article class="group overflow-hidden rounded-3xl border border-[#DDE8E2] bg-gradient-to-br from-white to-[#F8FBF9] shadow-sm transition-all hover:shadow-xl hover:-translate-y-1">
                        <div class="relative h-40 overflow-hidden bg-gradient-to-br from-[#EAF8F4] to-[#D7F3EC]">
                            <img src="{{ $caterer['image'] }}" alt="{{ $caterer['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <form id="{{ $toggleFormId }}" method="POST" action="{{ route('client.saved-caterers.toggle', $caterer['id']) }}" class="absolute right-3 top-3">
                                @csrf
                                <button 
                                    type="button"
                                    @click.prevent="openSavedCatererModal(@js($toggleFormId), @js($caterer['name']), @js($caterer['is_saved'] ? 'remove' : 'save'))"
                                    class="flex size-9 items-center justify-center rounded-full bg-white/95 shadow-lg backdrop-blur-sm transition-all hover:scale-110 {{ $caterer['is_saved'] ? 'text-[#BE3455]' : 'text-gray-400' }} hover:text-[#BE3455] hover:bg-[#FCECEF]" 
                                    aria-label="{{ $caterer['is_saved'] ? 'Unsave' : 'Save' }} {{ $caterer['name'] }}">
                                    @if($caterer['is_saved'])
                                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @else
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                        <div class="p-4">
                            <div class="mb-2 flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ $detailUrl }}" class="block truncate text-sm font-black text-[#17201D] hover:text-[#0F766E]">{{ $caterer['name'] }}</a>
                                    <p class="mt-1 text-xs text-[#66756F]">{{ $caterer['location'] }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full bg-[#FFF7E8] px-2 py-1 text-xs font-black text-[#9A5B12]">
                                    <svg class="size-3 fill-[#D99A20]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ $caterer['rating'] }}
                                </span>
                            </div>
                            <p class="mb-2 text-xs font-extrabold text-brand-orange">{{ $caterer['cuisine'] }}</p>
                            <p class="mb-3 text-xs text-[#66756F]">{{ $caterer['reviews'] }} reviews</p>
                            <div class="flex items-center justify-between border-t border-[#DDE8E2] pt-3">
                                <span class="text-sm font-black text-[#17201D]">{{ $caterer['price'] }}</span>
                                <a href="{{ $detailUrl }}#booking-request" class="rounded-xl bg-brand-orange px-3 py-2 text-xs font-black text-white">
                                    Book
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-[#DDE8E2] p-8 text-center">
                        <p class="text-sm text-[#66756F]">No featured caterers at the moment.</p>
                    </div>
                @endforelse
            </div>
            
            @include('client.partials.remove-saved-modal')
        </section>
    </div>
</x-dashboard-layout>
