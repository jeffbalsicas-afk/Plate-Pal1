<x-dashboard-layout
    title="{{ $booking->event_title }} - PlatePal"
    heading="Client Dashboard"
    :username="$user->name"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('client.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Dashboard
            </div>
        </a>
        <a href="{{ route('client.browse') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse Caterers
            </div>
        </a>
        <a href="{{ route('client.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#E8642A] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                My Bookings
            </div>
            @if(($statusCounts['all'] ?? 0) > 0)
                <span class="bg-[#E8642A] text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $statusCounts['all'] }}</span>
            @endif
        </a>
        <a href="{{ route('client.saved-caterers') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            Saved Caterers
        </a>
        <a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                Messages
            </div>
            @if($unreadMessages > 0)
                <span class="bg-[#E8642A] text-white text-xs font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">{{ $unreadMessages }}</span>
            @endif
        </a>
        <a href="{{ route('client.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            My Reviews
        </a>
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
                <div class="rounded-2xl border border-[#EDE4D8] p-5">
                    <h2 class="text-lg font-black text-[#1C1A17] mb-4">Caterer Details</h2>
                    <div class="grid sm:grid-cols-2 gap-4 text-sm text-[#8A6D3F]">
                        <p><span class="font-bold text-[#1C1A17]">Location:</span> {{ $booking->caterer->barangay ?? $booking->caterer->location ?? 'Tagum City' }}</p>
                        <p><span class="font-bold text-[#1C1A17]">Cuisine:</span> {{ $booking->caterer->cuisine ?? 'Catering Services' }}</p>
                        <p><span class="font-bold text-[#1C1A17]">Phone:</span> {{ $booking->caterer->phone ?? 'Not provided' }}</p>
                    </div>
                </div>

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
                                    <input id="package_name" name="package_name" value="{{ old('package_name', $existingReview->package_name ?? '') }}" type="text" placeholder="Classic Filipino"
                                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                                </div>
                            </div>
                            <div>
                                <label for="title" class="block text-xs font-bold text-[#1C1A17] mb-1.5">Title</label>
                                <input id="title" name="title" value="{{ old('title', $existingReview->title ?? '') }}" type="text" required placeholder="Perfect wedding feast"
                                    class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
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

                <div class="flex flex-col sm:flex-row gap-3 mt-5">
                    @if($booking->caterer)
                        <a href="{{ route('caterer.detail', $booking->caterer->id) }}" class="inline-flex justify-center px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                            View Caterer
                        </a>
                    @endif
                    @if(in_array($status, ['pending', 'confirmed']))
                        <a href="{{ route('client.bookings.edit', $booking) }}" class="inline-flex justify-center px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                            Edit Booking
                        </a>
                    @endif
                    <a href="{{ route('client.bookings') }}" class="inline-flex justify-center px-5 py-3 rounded-xl border border-[#E8642A] text-[#E8642A] text-sm font-bold hover:bg-[#FDF6EE] transition-colors">
                        All Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
