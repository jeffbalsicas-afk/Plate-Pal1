<x-dashboard-layout
    title="{{ $booking->event_title }} - PlatePal"
    heading="Client Bookings"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        @include('client.partials.sidebar')
    </x-slot:sidebar>

    @php
        $status = strtolower($booking->status ?? 'pending');
        $statusStyles = [
            'confirmed' => 'bg-emerald-100 text-emerald-800',
            'pending' => 'bg-amber-100 text-amber-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
        $catererName = $booking->caterer->business_name ?? $booking->caterer->name ?? 'Selected caterer';
        $packageName = $booking->selected_package_name;
    @endphp

    <div class="max-w-5xl mx-auto">
        <a href="{{ route('client.bookings') }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors mb-6">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to bookings
        </a>

        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-lg overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-[#EDE4D8]">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17] mb-1">{{ $booking->event_title }}</h1>
                        <p class="text-base sm:text-lg text-[#8A6D3F]">Catered by <span class="font-bold">{{ $catererName }}</span></p>
                    </div>
                    <span class="w-fit px-4 py-2 rounded-xl text-sm font-bold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 sm:p-8 grid md:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-[#FDF6EE] border border-[#EDE4D8] p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8A7F72] mb-2">Event date</p>
                    <p class="text-xl font-black text-[#1C1A17]">{{ $booking->event_date->format('M d, Y') }}</p>
                </div>
                <div class="rounded-2xl bg-[#FDF6EE] border border-[#EDE4D8] p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8A7F72] mb-2">Guest count</p>
                    <p class="text-xl font-black text-[#1C1A17]">{{ number_format($booking->guests) }} guests</p>
                </div>
                <div class="rounded-2xl bg-[#FDF6EE] border border-[#EDE4D8] p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8A7F72] mb-2">Booking ID</p>
                    <p class="text-xl font-black text-[#1C1A17]">#{{ $booking->id }}</p>
                </div>
            </div>

            <div class="px-6 sm:px-8 pb-6 sm:pb-8">
                @if($packageName || $booking->bookingItems->isNotEmpty() || $booking->special_requests)
                    <div class="grid md:grid-cols-2 gap-4 mb-5">
                        @if($packageName)
                            <div class="rounded-2xl border border-[#EDE4D8] p-5">
                                <h2 class="text-lg font-black text-[#1C1A17] mb-3">Selected Package</h2>
                                <p class="text-base font-bold text-[#1C1A17]">{{ $packageName }}</p>
                                @if($booking->package_price)
                                    <p class="text-sm text-[#8A6D3F] mt-1">
                                        Bundle price &#8369;{{ number_format($booking->package_price, 0) }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        @if($booking->bookingItems->isNotEmpty())
                            <div class="rounded-2xl border border-[#EDE4D8] p-5">
                                <h2 class="text-lg font-black text-[#1C1A17] mb-3">Requested Items</h2>
                                <div class="space-y-2">
                                    @foreach($booking->bookingItems as $item)
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-[#1C1A17]">{{ $item->item_name }}</span>
                                            <span class="text-[#E8642A] font-bold">₱{{ number_format($item->item_price, 0) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($booking->special_requests)
                            <div class="rounded-2xl border border-[#EDE4D8] p-5">
                                <h2 class="text-lg font-black text-[#1C1A17] mb-3">Event Notes</h2>
                                <p class="text-sm text-[#8A6D3F] whitespace-pre-line">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if($booking->decline_reason)
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-5 mb-5">
                        <h2 class="text-lg font-black text-red-800 mb-2">Decline Reason</h2>
                        <p class="text-sm text-red-700">{{ $booking->decline_reason }}</p>
                    </div>
                @endif

                <div class="rounded-2xl border border-[#EDE4D8] p-5 mb-5">
                    <h2 class="text-lg font-black text-[#1C1A17] mb-4">Caterer Details</h2>
                    <div class="grid sm:grid-cols-2 gap-4 text-sm text-[#8A6D3F]">
                        <p><span class="font-bold text-[#1C1A17]">Location:</span> {{ $booking->caterer->barangay ?? $booking->caterer->location ?? 'Tagum City' }}</p>
                        <p><span class="font-bold text-[#1C1A17]">Cuisine:</span> {{ $booking->caterer->cuisine ?? 'Catering Services' }}</p>
                        <p><span class="font-bold text-[#1C1A17]">Phone:</span> {{ $booking->caterer->phone ?? 'Not provided' }}</p>
                    </div>
                </div>

                @if(in_array($status, ['pending', 'confirmed']))
                    <div class="flex gap-3">
                        <a href="{{ route('client.bookings.edit', $booking) }}" class="px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                            Edit Booking
                        </a>
                        <form method="POST" action="{{ route('client.bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-3 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors">
                                Cancel Booking
                            </button>
                        </form>
                    </div>
                @endif

                @if($status === 'completed')
                    <div class="rounded-2xl border border-[#EDE4D8] p-5 mt-5">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-4">
                            <div>
                                <h2 class="text-lg font-black text-[#1C1A17]">{{ $existingReview ? 'Update Your Review' : 'Leave a Review' }}</h2>
                                <p class="text-sm text-[#8A7F72]">Your public feedback helps other clients choose confidently.</p>
                            </div>
                            @if($existingReview)
                                <span class="w-fit rounded-full bg-[#FFF2DF] px-3 py-1 text-xs font-bold text-[#A94A18]">Already reviewed</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('client.bookings.review', $booking) }}" class="space-y-4">
                            @csrf
                            <div class="grid sm:grid-cols-[160px_1fr] gap-3">
                                <div>
                                    <label for="rating" class="block text-xs font-bold text-[#1C1A17] mb-1.5">Rating</label>
                                    <select id="rating" name="rating" required class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                                        @for($rating = 5; $rating >= 1; $rating--)
                                            <option value="{{ $rating }}" @selected((int) old('rating', $existingReview->rating ?? 5) === $rating)>{{ $rating }} stars</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label for="package_name" class="block text-xs font-bold text-[#1C1A17] mb-1.5">Package</label>
                                    <input id="package_name" name="package_name" value="{{ old('package_name', $existingReview->package_name ?? $packageName ?? '') }}" type="text" placeholder="Classic Filipino"
                                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                                </div>
                            </div>
                            <div>
                                <label for="body" class="block text-xs font-bold text-[#1C1A17] mb-1.5">Review</label>
                                <textarea id="body" name="body" rows="4" required class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" placeholder="Share what went well...">{{ old('body', $existingReview->body ?? '') }}</textarea>
                            </div>
                            <button type="submit" class="px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                                {{ $existingReview ? 'Update Review' : 'Submit Review' }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout>
