@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
    $profileImageUrl = $user->profile_image_url;
@endphp

<x-dashboard-layout
    title="My Profile - PlatePal"
    heading="Client Dashboard"
    :username="$user->name"
    :initials="$initials"
>
    {{-- Sidebar --}}
    <x-slot:sidebar>
        @include('client.partials.sidebar')
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

            <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf
                <input type="hidden" name="form_type" value="profile">

                <div class="flex items-center gap-4 pb-6 border-b border-[#EDE4D8]">
                    <div class="w-16 h-16 overflow-hidden rounded-full bg-[#E8642A] text-white text-2xl font-bold flex items-center justify-center shrink-0">
                        @if($profileImageUrl)
                            <img src="{{ $profileImageUrl }}" alt="{{ $user->name }} profile photo" class="h-full w-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span class="hidden h-full w-full items-center justify-center">{{ $initials }}</span>
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-[#1C1A17]">{{ $user->name }}</p>
                        <p class="text-xs text-[#8A7F72]">Member since {{ $user->created_at->format('M d, Y') }}</p>
                        <input
                            id="profile_image"
                            name="profile_image"
                            type="file"
                            accept="image/*"
                            class="mt-3 w-full text-xs text-[#8A7F72] file:mr-3 file:rounded-lg file:border-0 file:bg-[#E8642A] file:px-3 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-[#F07C42]"
                        >
                        @error('profile_image')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
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

        <div class="mt-6 bg-white rounded-2xl border border-[#EDE4D8] shadow-lg overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-[#EDE4D8]">
                <h2 class="text-2xl font-black text-[#1C1A17] mb-2">Change Password</h2>
                <p class="text-sm sm:text-base text-[#8A6D3F]">Use your current password to set a new one.</p>
            </div>

            <form method="POST" action="{{ route('client.profile.update') }}" class="p-6 sm:p-8 space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="password">

                <div>
                    <label for="current_password" class="block text-sm font-bold text-[#1C1A17] mb-2">Current Password</label>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('current_password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-[#1C1A17] mb-2">New Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                    @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-[#1C1A17] mb-2">Confirm New Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full px-5 py-3 rounded-xl bg-[#1C1A17] text-white text-sm font-bold hover:bg-[#3A332B] transition-colors"
                >
                    Update Password
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
