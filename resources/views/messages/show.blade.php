@php
    $initials = strtoupper(substr(auth()->user()->name, 0, 1) . (str_contains(auth()->user()->name, ' ') ? substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Messages – PlatePal"
    heading="Messages"
    :username="auth()->user()->name"
    :initials="$initials"
>
    <x-slot:sidebar>
        @if(auth()->user()->role === 'client')
            <a href="{{ route('client.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Dashboard
            </a>
            <a href="{{ route('client.browse') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse Caterers
            </a>
            <a href="{{ route('client.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <div class="flex items-center gap-2.5">
                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    My Bookings
                </div>
                @if(($activeBookings ?? 0) > 0)
                    <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $activeBookings }}</span>
                @endif
            </a>
            <a href="{{ route('client.saved-caterers') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Saved Caterers
            </a>
            <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
                <div class="flex items-center gap-2.5">
                    <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    Messages
                </div>
                @if(($unreadMessages ?? 0) > 0)
                    <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
                @endif
            </a>
            <a href="{{ route('client.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                My Reviews
            </a>
        @else
            <a href="{{ route('caterer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Dashboard
            </a>
            <a href="{{ route('caterer.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <div class="flex items-center gap-2.5">
                    <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Bookings
                </div>
                @if(($pendingBookings ?? 0) > 0)
                    <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $pendingBookings }}</span>
                @endif
            </a>
            <a href="{{ route('caterer.menu-pricing') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Menu & Pricing
            </a>
            <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg bg-[#FDF6EE] text-[#E8642A] font-bold text-sm">
                <div class="flex items-center gap-2.5">
                    <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    Messages
                </div>
                @if(($unreadMessages ?? 0) > 0)
                    <span class="bg-red-500 text-white text-xs font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
                @endif
            </a>

            <a href="{{ route('caterer.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Reviews
            </a>
            <a href="{{ route('caterer.earnings') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Earnings
            </a>
        @endif
    </x-slot:sidebar>

    <div x-data="messageDeleteHandler()" @open-delete-modal.window="openDeleteModal($event.detail.messageId)">

    <div class="bg-white rounded-2xl border border-[#EDE4D8] overflow-hidden flex flex-col h-120">
        <div class="p-6 border-b border-[#EDE4D8] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#E8642A] text-white text-xs font-bold flex items-center justify-center">
                    {{ strtoupper(substr($recipient->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-bold text-[#1C1A17]">{{ $recipient->business_name ?? $recipient->name }}</h2>
                    <p class="text-xs text-[#8A7F72]">{{ $recipient->email }}</p>
                </div>
            </div>
            <a href="{{ route('messages.index') }}" class="text-[#8A7F72] hover:text-[#1C1A17]">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <div id="messages-thread" data-current-role="{{ auth()->user()->role }}" class="flex-1 overflow-y-auto p-6 space-y-4">
            @forelse($messages as $message)
                @php
                    $isMine = $message->sender === auth()->user()->role;
                    $hasBody = filled($message->body);
                @endphp
                <div data-message-id="{{ $message->id }}" class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} group">
                    <div class="max-w-xs sm:max-w-sm {{ $isMine ? 'bg-[#E8642A] text-white' : 'bg-[#FDF6EE] text-[#1C1A17]' }} rounded-lg px-4 py-2 relative">
                        @if($isMine)
                            <button type="button" @click="$dispatch('open-delete-modal', { messageId: {{ $message->id }} })" class="absolute -left-8 top-1 opacity-0 group-hover:opacity-100 transition-opacity w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center" title="Delete message">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                        @if($hasBody)
                            <p class="text-sm break-words">{{ $message->body }}</p>
                        @endif

                        @if($message->hasAttachment())
                            @php
                                $attachmentUrl = route('messages.attachment', $message);
                                $attachmentName = $message->attachmentName();
                                $attachmentSize = $message->formattedAttachmentSize();
                            @endphp

                            @if($message->isAttachmentImage())
                                <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener" class="{{ $hasBody ? 'mt-2' : '' }} block overflow-hidden rounded-lg border bg-white {{ $isMine ? 'border-white/30' : 'border-[#EDE4D8]' }}">
                                    <img src="{{ $attachmentUrl }}" alt="{{ $attachmentName ?? 'Attached image' }}" class="block max-h-48 w-full object-contain">
                                </a>
                            @else
                                <a href="{{ $attachmentUrl }}" class="{{ $hasBody ? 'mt-2' : '' }} flex items-center gap-2 rounded-lg border px-3 py-2 text-xs font-bold transition-colors {{ $isMine ? 'border-white/30 bg-white/10 text-white hover:bg-white/20' : 'border-[#EDE4D8] bg-white text-[#1C1A17] hover:bg-[#F7EFE5]' }}">
                                    <svg class="size-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.657-5.657l-6.586 6.586a6 6 0 108.485 8.485l6.586-6.586"/></svg>
                                    <span class="min-w-0">
                                        <span class="block truncate">{{ $attachmentName }}</span>
                                        @if($attachmentSize)
                                            <span class="block font-medium {{ $isMine ? 'text-orange-100' : 'text-[#8A7F72]' }}">{{ $attachmentSize }}</span>
                                        @endif
                                    </span>
                                </a>
                            @endif
                        @endif

                        <p class="text-xs {{ $isMine ? 'text-orange-100' : 'text-[#8A7F72]' }} mt-1">
                            {{ $message->created_at->format('g:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <div id="messages-empty-state" class="text-center py-12">
                    <p class="text-[#8A7F72]">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-[#EDE4D8] p-6">
            <form id="message-form" action="{{ route('messages.store', $recipient) }}" method="POST" enctype="multipart/form-data" data-channel="messages.{{ $clientId }}.{{ $catererId }}" class="space-y-3" autocomplete="off">
                @csrf
                <div id="attachment-selection" class="hidden items-center justify-between gap-3 rounded-lg border border-[#EDE4D8] bg-[#FDF6EE] px-3 py-2 text-sm text-[#1C1A17]">
                    <div class="flex min-w-0 items-center gap-2">
                        <svg class="size-4 flex-shrink-0 text-[#E8642A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.657-5.657l-6.586 6.586a6 6 0 108.485 8.485l6.586-6.586"/></svg>
                        <span id="attachment-selection-name" class="truncate font-medium"></span>
                    </div>
                    <button type="button" id="attachment-clear" class="rounded-md p-1 text-[#8A7F72] hover:bg-white hover:text-[#1C1A17]" aria-label="Remove attachment">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                @error('body')
                    <p class="text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
                @error('attachment')
                    <p class="text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex gap-3">
                    <input id="message-attachment" type="file" name="attachment" accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv" class="sr-only">
                    <label for="message-attachment" class="inline-flex cursor-pointer items-center justify-center rounded-lg border border-[#EDE4D8] bg-white px-3 py-2 text-[#8A7F72] transition-colors hover:border-[#E8642A] hover:text-[#E8642A]" title="Attach file" aria-label="Attach file">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.657-5.657l-6.586 6.586a6 6 0 108.485 8.485l6.586-6.586"/></svg>
                    </label>
                    <input type="text" name="body" placeholder="Type a message..." autocomplete="off"
                        class="flex-1 px-4 py-2 rounded-lg bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] placeholder:text-[#8A7F72] focus:outline-none focus:border-[#E8642A]">
                    <button type="submit" class="px-6 py-2 rounded-lg bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors disabled:cursor-not-allowed disabled:opacity-70">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Message Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeDeleteModal()">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="size-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1C1A17]">Delete Message</h3>
            </div>
            <p class="text-sm text-[#8A7F72] mb-6">Are you sure you want to delete this message? This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="closeDeleteModal()" class="flex-1 px-4 py-2 rounded-lg border border-[#EDE4D8] text-[#1C1A17] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">
                    Cancel
                </button>
                <button @click="deleteMessage()" class="flex-1 px-4 py-2 rounded-lg bg-red-500 text-white text-sm font-bold hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
    </div>

    <x-slot:scripts>
        <script>
            function messageDeleteHandler() {
                return {
                    showDeleteModal: false,
                    deleteMessageId: null,
                    openDeleteModal(messageId) {
                        this.deleteMessageId = messageId;
                        this.showDeleteModal = true;
                    },
                    closeDeleteModal() {
                        this.showDeleteModal = false;
                        this.deleteMessageId = null;
                    },
                    deleteMessage() {
                        if (this.deleteMessageId) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '/messages/' + this.deleteMessageId;
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                };
            }

            document.addEventListener('DOMContentLoaded', () => {
                const thread = document.getElementById('messages-thread');
                const form = document.getElementById('message-form');

                if (!thread || !form) {
                    return;
                }

                const input = form.querySelector('input[name="body"]');
                const attachmentInput = form.querySelector('input[name="attachment"]');
                const attachmentSelection = document.getElementById('attachment-selection');
                const attachmentSelectionName = document.getElementById('attachment-selection-name');
                const attachmentClear = document.getElementById('attachment-clear');
                const submitButton = form.querySelector('button[type="submit"]');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const currentRole = thread.dataset.currentRole;
                const seenMessages = new Set(
                    Array.from(thread.querySelectorAll('[data-message-id]')).map((node) => node.dataset.messageId)
                );

                const scrollToBottom = () => {
                    thread.scrollTop = thread.scrollHeight;
                };

                const updateAttachmentSelection = () => {
                    const file = attachmentInput.files?.[0];

                    if (!file) {
                        attachmentSelection.classList.add('hidden');
                        attachmentSelection.classList.remove('flex');
                        attachmentSelectionName.textContent = '';
                        return;
                    }

                    attachmentSelection.classList.remove('hidden');
                    attachmentSelection.classList.add('flex');
                    attachmentSelectionName.textContent = file.name;
                };

                const createAttachmentNode = (attachment, isMine, hasBody) => {
                    const link = document.createElement('a');
                    link.href = attachment.url;
                    link.target = '_blank';
                    link.rel = 'noopener';
                    link.className = hasBody ? 'mt-2 block' : 'block';

                    if (attachment.is_image) {
                        const image = document.createElement('img');
                        image.src = attachment.url;
                        image.alt = attachment.name || 'Attached image';
                        image.loading = 'lazy';
                        image.className = 'block max-h-48 w-full object-contain';
                        link.className = `${hasBody ? 'mt-2 ' : ''}block overflow-hidden rounded-lg border bg-white ${isMine ? 'border-white/30' : 'border-[#EDE4D8]'}`;
                        link.appendChild(image);
                        return link;
                    }

                    link.className = `${hasBody ? 'mt-2 ' : ''}flex items-center gap-2 rounded-lg border px-3 py-2 text-xs font-bold transition-colors ${isMine ? 'border-white/30 bg-white/10 text-white hover:bg-white/20' : 'border-[#EDE4D8] bg-white text-[#1C1A17] hover:bg-[#F7EFE5]'}`;

                    const icon = document.createElement('span');
                    icon.className = 'flex-shrink-0';
                    icon.innerHTML = '<svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.657-5.657l-6.586 6.586a6 6 0 108.485 8.485l6.586-6.586"/></svg>';

                    const label = document.createElement('span');
                    label.className = 'min-w-0';

                    const name = document.createElement('span');
                    name.className = 'block truncate';
                    name.textContent = attachment.name || 'Attachment';
                    label.appendChild(name);

                    if (attachment.size_label) {
                        const size = document.createElement('span');
                        size.className = `block font-medium ${isMine ? 'text-orange-100' : 'text-[#8A7F72]'}`;
                        size.textContent = attachment.size_label;
                        label.appendChild(size);
                    }

                    link.append(icon, label);
                    return link;
                };

                const appendMessage = (message) => {
                    const id = String(message.id);

                    if (seenMessages.has(id)) {
                        return;
                    }

                    seenMessages.add(id);

                    document.getElementById('messages-empty-state')?.remove();

                    const isMine = message.sender === currentRole;
                    const text = (message.body ?? '').trim();
                    const hasBody = text.length > 0;

                    const row = document.createElement('div');
                    row.dataset.messageId = id;
                    row.className = `flex ${isMine ? 'justify-end' : 'justify-start'} group`;

                    const bubble = document.createElement('div');
                    bubble.className = `max-w-xs sm:max-w-sm rounded-lg px-4 py-2 relative ${isMine ? 'bg-[#E8642A] text-white' : 'bg-[#FDF6EE] text-[#1C1A17]'}`;

                    if (isMine) {
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.className = 'absolute -left-8 top-1 opacity-0 group-hover:opacity-100 transition-opacity w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center';
                        deleteBtn.title = 'Delete message';
                        deleteBtn.innerHTML = '<svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                        deleteBtn.addEventListener('click', () => {
                            window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { messageId: id } }));
                        });
                        bubble.appendChild(deleteBtn);
                    }

                    if (hasBody) {
                        const body = document.createElement('p');
                        body.className = 'text-sm break-words';
                        body.textContent = text;
                        bubble.appendChild(body);
                    }

                    if (message.attachment?.url) {
                        bubble.appendChild(createAttachmentNode(message.attachment, isMine, hasBody));
                    }

                    const time = document.createElement('p');
                    time.className = `text-xs mt-1 ${isMine ? 'text-orange-100' : 'text-[#8A7F72]'}`;
                    time.textContent = message.created_at_label ?? 'Just now';

                    bubble.appendChild(time);
                    row.appendChild(bubble);
                    thread.appendChild(row);
                    scrollToBottom();
                };

                // Polling for new messages
                const pollMessages = async () => {
                    const lastMessageId = Array.from(seenMessages).pop();
                    const url = `{{ route('messages.latest', $recipient) }}${lastMessageId ? '?after=' + lastMessageId : ''}`;

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        });

                        if (response.ok) {
                            const data = await response.json();
                            data.messages.forEach(appendMessage);
                        }
                    } catch (error) {
                        console.error('Polling error:', error);
                    }
                };

                // Poll every 3 seconds
                const pollInterval = setInterval(pollMessages, 1000);

                // Cleanup on page unload
                window.addEventListener('beforeunload', () => {
                    clearInterval(pollInterval);
                });

                attachmentInput.addEventListener('change', updateAttachmentSelection);
                attachmentClear.addEventListener('click', () => {
                    attachmentInput.value = '';
                    updateAttachmentSelection();
                });

                if (window.Echo) {
                    window.Echo.private(form.dataset.channel)
                        .listen('.message.sent', appendMessage);
                }

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const body = input.value.trim();
                    const file = attachmentInput.files?.[0] ?? null;

                    if (!body && !file) {
                        return;
                    }

                    submitButton.disabled = true;

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Socket-ID': window.Echo?.socketId() ?? '',
                            },
                            body: (() => {
                                const formData = new FormData(form);
                                formData.set('body', body);

                                if (!file) {
                                    formData.delete('attachment');
                                }

                                return formData;
                            })(),
                        });

                        if (!response.ok) {
                            throw new Error('Unable to send message.');
                        }

                        const payload = await response.json();
                        appendMessage(payload.message);
                        form.reset();
                        updateAttachmentSelection();
                    } catch (error) {
                        HTMLFormElement.prototype.submit.call(form);
                    } finally {
                        submitButton.disabled = false;
                    }
                });

                scrollToBottom();
            });
        </script>
    </x-slot:scripts>
</x-dashboard-layout>
