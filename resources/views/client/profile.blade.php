@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
@endphp

<x-dashboard-layout
    title="My Profile - PlatePal"
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
        <a href="{{ route('client.bookings') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <div class="flex items-center gap-2.5">
                <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                My Bookings
            </div>
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
        </a>
        <a href="{{ route('client.reviews') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#8A7F72]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            My Reviews
        </a>
    </x-slot:sidebar>

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('client.dashboard') }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors mb-6">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to dashboard
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
                <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17] mb-2">My Profile</h1>
                <p class="text-base sm:text-lg text-[#8A6D3F]">Manage your account information</p>
            </div>

            <form method="POST" action="{{ route('client.profile.update') }}" class="p-6 sm:p-8 space-y-6">
                @csrf

                <div class="flex items-center gap-4 pb-6 border-b border-[#EDE4D8]">
                    <div class="w-16 h-16 rounded-full bg-[#E8642A] text-white text-2xl font-bold flex items-center justify-center">
                        {{ $initials }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#1C1A17]">{{ $user->name }}</p>
                        <p class="text-xs text-[#8A7F72]">Member since {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-bold text-[#1C1A17] mb-2">Full Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-[#1C1A17] mb-2">Email Address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-bold text-[#1C1A17] mb-2">Phone Number</label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone', $user->phone) }}"
                        placeholder="+63 9XX XXX XXXX"
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('phone')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-2xl bg-[#FDF6EE] border border-[#EDE4D8] p-5">
                    <p class="text-sm font-bold text-[#1C1A17] mb-2">Account Status</p>
                    <div class="space-y-2 text-sm text-[#8A6D3F]">
                        <p><span class="font-bold text-[#1C1A17]">Role:</span> Client</p>
                        <p><span class="font-bold text-[#1C1A17]">Member Since:</span> {{ $user->created_at->format('F d, Y') }}</p>
                        <p><span class="font-bold text-[#1C1A17]">Last Updated:</span> {{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button
                        type="submit"
                        class="flex-1 px-5 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors"
                    >
                        Save Changes
                    </button>
                    <a
                        href="{{ route('client.dashboard') }}"
                        class="flex-1 inline-flex justify-center px-5 py-3 rounded-xl border border-[#E8642A] text-[#E8642A] text-sm font-bold hover:bg-[#FDF6EE] transition-colors"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
