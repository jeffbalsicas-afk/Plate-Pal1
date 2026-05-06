@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
    $displayName = $user->business_name ?? $user->name;
    $barangays = [
        'Magugpo Poblacion',
        'Apokon',
        'Visayan Village',
        'Mankilam',
        'New Balamban',
        'Pagsabangan',
        'Magugpo East',
        'Magugpo West',
        'San Isidro',
        'San Miguel',
        'San Agustin',
        'Nueva Fuerza',
        'Bincungan',
        'Busaon',
        'Canocotan',
        'La Filipina',
        'Liboganon',
        'Madaum',
        'Magugpo North',
        'Magugpo South',
        'Pandapan',
        'Cuambogan',
        'Magdum',
    ];
@endphp

<x-dashboard-layout
    title="Edit Profile – PlatePal"
    heading="Caterer Profile"
    :username="$displayName"
    :usersub="$user->barangay ?? ''"
    :initials="$initials"
>
    <x-slot:sidebar>
        <a href="{{ route('caterer.profile') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[#E8642A] hover:bg-[#FDF6EE] transition-colors text-sm font-medium">
            <svg class="size-4 stroke-[#E8642A]" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Edit Profile
        </a>
    </x-slot:sidebar>
    <x-slot:sidebarFooter></x-slot:sidebarFooter>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-0">

        {{-- Approval Status Banner --}}
        @if($user->approval_status === 'pending')
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-[#FFF8E1] border border-yellow-300 flex items-start gap-2 sm:gap-3">
                <svg class="size-4 sm:size-5 stroke-yellow-600 shrink-0 mt-0.5" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-xs sm:text-sm font-bold text-yellow-700">Pending Admin Approval</p>
                    <p class="text-[0.7rem] sm:text-xs text-yellow-600">Your profile is under review. You'll be notified once approved.</p>
                </div>
            </div>
        @elseif($user->approval_status === 'approved')
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-[#EAF5E9] border border-green-300 flex items-start gap-2 sm:gap-3">
                <svg class="size-4 sm:size-5 stroke-green-600 shrink-0 mt-0.5" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-xs sm:text-sm font-bold text-green-700">Profile Approved ✓</p>
                    <p class="text-[0.7rem] sm:text-xs text-green-600">Your catering business is live and visible to clients.</p>
                </div>
            </div>
        @elseif($user->approval_status === 'rejected')
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-red-50 border border-red-300 flex items-start gap-2 sm:gap-3">
                <svg class="size-4 sm:size-5 stroke-red-500 shrink-0 mt-0.5" fill="none" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-xs sm:text-sm font-bold text-red-600">Profile Rejected</p>
                    <p class="text-[0.7rem] sm:text-xs text-red-500">Please update your details and resubmit for approval.</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-[#EAF5E9] border border-green-300 text-green-700 text-xs sm:text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Profile Form --}}
        <div class="bg-white rounded-xl sm:rounded-2xl px-4 sm:px-7 py-5 sm:py-6 border border-[#EDE4D8]">
            <h2 class="text-lg sm:text-xl font-black text-[#1C1A17] mb-4 sm:mb-6 text-center">Catering Business Details</h2>

            <form method="POST" action="{{ route('caterer.profile.update') }}" enctype="multipart/form-data" class="space-y-4 sm:space-y-5">
                @csrf

                {{-- Profile Image --}}
                <div class="flex flex-col">
                    <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-2">Profile / Business Photo</label>
                    @if($user->profile_image)
                        <img src="{{ $user->profile_image }}" alt="Profile" class="w-20 sm:w-24 h-20 sm:h-24 rounded-lg sm:rounded-xl object-cover mb-2 sm:mb-3 border border-[#EDE4D8]">
                    @endif
                    <input type="file" name="profile_image" accept="image/*"
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    @error('profile_image')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Business Name --}}
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $user->business_name) }}" required
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('business_name') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    @error('business_name')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Barangay Dropdown --}}
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Barangay / Location</label>
                    <select name="barangay" required
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('barangay') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                        @foreach($barangays as $barangay)
                            <option value="{{ $barangay }}" {{ old('barangay', $user->barangay) === $barangay ? 'selected' : '' }}>
                                {{ $barangay }}
                            </option>
                        @endforeach
                    </select>
                    @error('barangay')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    @error('phone')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Cuisine --}}
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Cuisine / Specialty</label>
                    <input type="text" name="cuisine" value="{{ old('cuisine', $user->cuisine) }}" required placeholder="e.g. Authentic Filipino, Seafood, BBQ"
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('cuisine') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    @error('cuisine')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Price Range --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Minimum Price per Head</label>
                        <input type="text" name="price_min" value="{{ old('price_min', $user->price_min) }}" required placeholder="₱"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('price_min') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                        @error('price_min')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Maximum Price per Head</label>
                        <input type="text" name="price_max" value="{{ old('price_max', $user->price_max) }}" required placeholder="₱"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('price_max') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                        @error('price_max')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Business Description</label>
                    <textarea name="description" rows="4" placeholder="Tell clients about your catering service..."
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('description') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">{{ old('description', $user->description) }}</textarea>
                    @error('description')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Guest Range --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Minimum Guests</label>
                        <input type="text" name="min_guest" value="{{ old('min_guest', $user->min_guest) }}" required placeholder="e.g. 10"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('min_guest') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                        @error('min_guest')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1C1A17] mb-1.5 sm:mb-2">Maximum Guests</label>
                        <input type="text" name="max_guest" value="{{ old('max_guest', $user->max_guest) }}" required placeholder="e.g. 10"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl bg-gray-50 border {{ $errors->has('max_guest') ? 'border-red-400' : 'border-gray-200' }} text-[#1C1A17] text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                        @error('max_guest')<p class="text-red-500 text-[0.7rem] sm:text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl bg-[#E8642A] text-white text-sm sm:text-base font-bold hover:bg-[#F07C42] transition-colors shadow-md shadow-orange-200">
                    Save & Submit for Approval
                </button>
            </form>
        </div>
    </div>

</x-dashboard-layout>
