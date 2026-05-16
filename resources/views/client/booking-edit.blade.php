@php
    $initials = strtoupper(substr(auth()->user()->name, 0, 1) . (str_contains(auth()->user()->name, ' ') ? substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="Edit Booking - PlatePal"
    heading="Client Bookings"
    :username="auth()->user()->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        @include('client.partials.sidebar')
    </x-slot:sidebar>

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('client.bookings.show', $booking) }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors mb-6">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to booking
        </a>

        @if($errors->any())
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-[#EDE4D8] shadow-lg overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-[#EDE4D8]">
                <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17] mb-2">Edit Booking</h1>
                <p class="text-base sm:text-lg text-[#8A6D3F]">Update your event details</p>
            </div>

            <form method="POST" action="{{ route('client.bookings.update', $booking) }}" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="event_title" class="block text-sm font-bold text-[#1C1A17] mb-2">Event Title</label>
                    <input
                        id="event_title"
                        name="event_title"
                        type="text"
                        value="{{ old('event_title', $booking->event_title) }}"
                        required
                        placeholder="e.g., Birthday Party, Wedding Reception"
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('event_title')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="event_date" class="block text-sm font-bold text-[#1C1A17] mb-2">Event Date</label>
                    <input
                        id="event_date"
                        name="event_date"
                        type="date"
                        value="{{ old('event_date', $booking->event_date->format('Y-m-d')) }}"
                        min="{{ now()->toDateString() }}"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('event_date')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="guests" class="block text-sm font-bold text-[#1C1A17] mb-2">Number of Guests</label>
                    <input
                        id="guests"
                        name="guests"
                        type="number"
                        value="{{ old('guests', $booking->guests) }}"
                        required
                        min="1"
                        max="10000"
                        placeholder="Caterer typically serves {{ $booking->caterer->min_guest ?? 20 }}-{{ $booking->caterer->max_guest ?? 100 }} guests"
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    <p class="text-xs text-[#8A7F72] mt-1">Typical range: {{ $booking->caterer->min_guest ?? 20 }}-{{ $booking->caterer->max_guest ?? 100 }} guests (flexible)</p>
                    @error('guests')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if(($packages ?? collect())->isNotEmpty())
                    <div>
                        <label for="package_id" class="block text-sm font-bold text-[#1C1A17] mb-2">Package</label>
                        <select
                            id="package_id"
                            name="package_id"
                            class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                        >
                            <option value="">Custom request</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" @selected((int) old('package_id', $booking->package_id) === $package->id)>
                                    {{ $package->name }} - &#8369;{{ number_format($package->price, 0) }} bundle, min {{ $package->min_guests }} guests
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div>
                    <label for="special_requests" class="block text-sm font-bold text-[#1C1A17] mb-2">Event Notes</label>
                    <textarea
                        id="special_requests"
                        name="special_requests"
                        rows="4"
                        placeholder="Menu preferences, setup needs, allergies, venue details..."
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >{{ old('special_requests', $booking->special_requests) }}</textarea>
                    @error('special_requests')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button
                        type="submit"
                        class="flex-1 px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors"
                    >
                        Save Changes
                    </button>
                    <a
                        href="{{ route('client.bookings.show', $booking) }}"
                        class="flex-1 inline-flex justify-center px-5 py-3 rounded-xl border border-[#E8642A] text-[#E8642A] text-sm font-bold hover:bg-[#FDF6EE] transition-colors"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
