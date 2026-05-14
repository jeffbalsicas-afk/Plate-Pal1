@php
    $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
    $displayName = $user->business_name ?? $user->name;
    $barangays = [
        'Magugpo Poblacion', 'Apokon', 'Visayan Village', 'Mankilam', 'New Balamban',
        'Pagsabangan', 'Magugpo East', 'Magugpo West', 'San Isidro', 'San Miguel',
        'San Agustin', 'Nueva Fuerza', 'Bincungan', 'Busaon', 'Canocotan',
        'La Filipina', 'Liboganon', 'Madaum', 'Magugpo North', 'Magugpo South',
        'Pandapan', 'Cuambogan', 'Magdum',
    ];
    $servicesOptions = [
        'Weddings & Receptions', 'Birthday Parties', 'Corporate Events',
        'Baptisms & Communions', 'Graduations', 'Family Gatherings',
        'Anniversaries', 'Fiestas', 'Holiday Celebrations'
    ];
    $selectedServices = is_array($user->services_offered) ? $user->services_offered : json_decode($user->services_offered ?? '[]', true);
    $selectedServices = is_array($selectedServices) ? $selectedServices : [];
    $galleryImages = is_array($user->gallery_images) ? $user->gallery_images : json_decode($user->gallery_images ?? '[]', true);
    $galleryImages = is_array($galleryImages) ? $galleryImages : [];
@endphp

<x-dashboard-layout
    title="Manage Profile – PlatePal"
    heading="Caterer Profile"
    :username="$displayName"
    :usersub="$user->barangay ?? ''"
    :initials="$initials"
>
    <div
        class="max-w-4xl mx-auto"
        x-data="{ tab: ['basic', 'about', 'gallery'].includes(window.location.hash.slice(1)) ? window.location.hash.slice(1) : 'basic' }"
        x-init="window.addEventListener('hashchange', () => { const value = window.location.hash.slice(1); if (['basic', 'about', 'gallery'].includes(value)) tab = value })"
    >
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-300 text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-300 text-red-700 text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        @if($user->approval_status === 'pending')
            <div class="mb-6 p-4 rounded-xl bg-yellow-50 border border-yellow-300">
                <p class="text-sm font-black text-yellow-800">Pending admin approval</p>
                <p class="text-sm text-yellow-700 mt-1">Your latest profile changes are waiting for admin review before your public profile is shown to clients again.</p>
            </div>
        @elseif($user->approval_status === 'rejected')
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-300">
                <p class="text-sm font-black text-red-800">Profile needs changes</p>
                <p class="text-sm text-red-700 mt-1">{{ $user->rejection_reason ?: 'Please update your profile and submit it again for admin approval.' }}</p>
            </div>
        @endif

        <!-- Tabs -->
        <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1 border-b border-[#EDE4D8]">
            <button type="button" @click="tab = 'basic'; window.location.hash = 'basic'" :class="tab === 'basic' ? 'bg-[#E8642A] text-white' : 'text-[#1C1A17] hover:bg-[#FDF6EE]'" class="px-4 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">
                Basic Info
            </button>
            <button type="button" @click="tab = 'about'; window.location.hash = 'about'" :class="tab === 'about' ? 'bg-[#E8642A] text-white' : 'text-[#1C1A17] hover:bg-[#FDF6EE]'" class="px-4 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">
                About Us
            </button>
            <button type="button" @click="tab = 'gallery'; window.location.hash = 'gallery'" :class="tab === 'gallery' ? 'bg-[#E8642A] text-white' : 'text-[#1C1A17] hover:bg-[#FDF6EE]'" class="px-4 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">
                Gallery
            </button>
        </div>

        <!-- Basic Info Tab -->
        <div x-show="tab === 'basic'" x-transition class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <h2 class="text-xl font-black text-[#1C1A17] mb-6">Basic Information</h2>
            <form method="POST" action="{{ route('caterer.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="basic">

                <!-- Profile Image -->
                <div>
                    <label class="block text-sm font-bold text-[#1C1A17] mb-2">Profile Photo</label>
                    <div class="flex items-center gap-4">
                        <div class="size-20 rounded-full overflow-hidden bg-[#FDF6EE] border-2 border-[#EDE4D8]">
                            @if($user->profile_image)
                                @if(str_starts_with($user->profile_image, 'http'))
                                    <img src="{{ $user->profile_image }}" alt="Profile" class="w-full h-full object-cover">
                                @elseif(str_starts_with($user->profile_image, '/storage/'))
                                    <img src="{{ $user->profile_image }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset($user->profile_image) }}" alt="Profile" class="w-full h-full object-cover">
                                @endif
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl font-black text-[#E8642A]">{{ $initials }}</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="profile_image" accept="image/*" class="text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#E8642A] file:text-white hover:file:bg-[#F07C42] file:cursor-pointer">
                            <p class="text-xs text-[#8A7F72] mt-1">JPG, PNG up to 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Business Name *</label>
                        <input type="text" name="business_name" value="{{ old('business_name', $user->business_name) }}" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Barangay *</label>
                        <select name="barangay" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                            @foreach($barangays as $barangay)
                                <option value="{{ $barangay }}" {{ old('barangay', $user->barangay) === $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Phone *</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Cuisine *</label>
                        <input type="text" name="cuisine" value="{{ old('cuisine', $user->cuisine) }}" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Min Price/Head *</label>
                        <input type="number" name="price_min" value="{{ old('price_min', $user->price_min) }}" required min="0" step="0.01" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Max Price/Head *</label>
                        <input type="number" name="price_max" value="{{ old('price_max', $user->price_max) }}" required min="0" step="0.01" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Min Guests *</label>
                        <input type="number" name="min_guest" value="{{ old('min_guest', $user->min_guest) }}" required min="1" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#1C1A17] mb-2">Max Guests *</label>
                        <input type="number" name="max_guest" value="{{ old('max_guest', $user->max_guest) }}" required min="1" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#1C1A17] mb-2">Short Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">{{ old('description', $user->description) }}</textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#E8642A] text-white font-bold hover:bg-[#F07C42] transition-colors">
                    Save Basic Info
                </button>
            </form>
        </div>

        <!-- About Us Tab -->
        <div x-show="tab === 'about'" x-cloak x-transition class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <h2 class="text-xl font-black text-[#1C1A17] mb-6">About Your Business</h2>
            <form method="POST" action="{{ route('caterer.profile.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="about">

                <div>
                    <label class="block text-sm font-bold text-[#1C1A17] mb-2">Our Story</label>
                    <textarea name="our_story" rows="5" placeholder="Tell clients about your journey, heritage, and passion..." class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">{{ old('our_story', $user->our_story) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#1C1A17] mb-2">What Makes Us Special</label>
                    <textarea name="what_makes_special" rows="5" placeholder="List your unique selling points (one per line)..." class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-[#1C1A17] focus:outline-none focus:ring-2 focus:ring-[#E8642A]">{{ old('what_makes_special', $user->what_makes_special) }}</textarea>
                    <p class="text-xs text-[#8A7F72] mt-1">Enter each point on a new line</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#1C1A17] mb-3">Services Offered</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($servicesOptions as $service)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="services_offered[]" value="{{ $service }}" {{ in_array($service, $selectedServices) ? 'checked' : '' }} class="w-4 h-4 rounded text-[#E8642A] focus:ring-[#E8642A]">
                                <span class="text-sm text-[#1C1A17]">{{ $service }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#E8642A] text-white font-bold hover:bg-[#F07C42] transition-colors">
                    Save About Info
                </button>
            </form>
        </div>

        <!-- Gallery Tab -->
        <div x-show="tab === 'gallery'" x-cloak x-transition class="bg-white rounded-2xl p-6 border border-[#EDE4D8]">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-xl font-black text-[#1C1A17]">Photo Gallery</h2>
                    <p class="text-sm text-[#8A7F72] mt-1">Add portfolio photos that clients will see on your public profile.</p>
                </div>
                <span class="text-xs font-bold text-[#8A7F72] bg-[#FDF6EE] border border-[#EDE4D8] rounded-full px-3 py-1">
                    {{ count($galleryImages) }} uploaded
                </span>
            </div>

            <form
                method="POST"
                action="{{ route('caterer.profile.update') }}"
                enctype="multipart/form-data"
                class="space-y-5"
                x-data="{
                    files: [],
                    errors: [],
                    dragging: false,
                    maxSize: 2 * 1024 * 1024,
                    choose() {
                        this.$refs.galleryInput.click();
                    },
                    addFiles(fileList) {
                        this.errors = [];
                        this.setFiles([...this.files.map((item) => item.file), ...Array.from(fileList)]);
                    },
                    setFiles(fileList) {
                        this.files.forEach((item) => URL.revokeObjectURL(item.preview));
                        const accepted = [];

                        Array.from(fileList).forEach((file) => {
                            if (!file.type.startsWith('image/')) {
                                this.errors.push(`${file.name} is not an image.`);
                                return;
                            }

                            if (file.size > this.maxSize) {
                                this.errors.push(`${file.name} is larger than 2MB.`);
                                return;
                            }

                            accepted.push({
                                file,
                                name: file.name,
                                size: `${(file.size / 1024 / 1024).toFixed(2)} MB`,
                                preview: URL.createObjectURL(file),
                            });
                        });

                        this.files = accepted;
                        this.syncInput();
                    },
                    remove(index) {
                        const nextFiles = this.files.map((item) => item.file);
                        nextFiles.splice(index, 1);
                        this.setFiles(nextFiles);
                    },
                    syncInput() {
                        const transfer = new DataTransfer();
                        this.files.forEach((item) => transfer.items.add(item.file));
                        this.$refs.galleryInput.files = transfer.files;
                    },
                }"
            >
                @csrf
                <input type="hidden" name="form_type" value="gallery">

                <div
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
                    :class="dragging ? 'border-[#E8642A] bg-[#FFF4ED]' : 'border-[#EDE4D8] bg-[#FDF6EE]'"
                    class="rounded-2xl border-2 border-dashed p-8 text-center transition-colors"
                >
                    <input
                        x-ref="galleryInput"
                        type="file"
                        name="gallery_images[]"
                        multiple
                        accept="image/*"
                        class="sr-only"
                        @change="addFiles($event.target.files)"
                    >

                    <div class="mx-auto mb-4 size-14 rounded-full bg-white border border-[#EDE4D8] flex items-center justify-center">
                        <svg class="size-7 text-[#E8642A]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v1.25A2.25 2.25 0 005.25 20h13.5A2.25 2.25 0 0021 17.75V16.5M16.5 7.5L12 3m0 0L7.5 7.5M12 3v13.5"/></svg>
                    </div>
                    <p class="text-sm font-black text-[#1C1A17]">Drop photos here or choose files</p>
                    <p class="text-xs text-[#8A7F72] mt-1">JPG, PNG, WEBP up to 2MB each</p>
                    <button type="button" @click="choose" class="mt-5 inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors">
                        Choose Photos
                    </button>
                </div>

                <template x-if="errors.length">
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                        <template x-for="message in errors" :key="message">
                            <p class="text-sm font-medium text-red-700" x-text="message"></p>
                        </template>
                    </div>
                </template>

                <template x-if="files.length">
                    <div class="rounded-2xl border border-[#EDE4D8] p-4">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h3 class="text-sm font-black text-[#1C1A17]">Ready to upload</h3>
                            <span class="text-xs font-bold text-[#8A7F72]" x-text="`${files.length} selected`"></span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <template x-for="(item, index) in files" :key="`${item.name}-${index}`">
                                <div class="relative overflow-hidden rounded-xl border border-[#EDE4D8] bg-white">
                                    <img :src="item.preview" :alt="item.name" class="h-36 w-full object-cover">
                                    <div class="p-3">
                                        <p class="truncate text-xs font-bold text-[#1C1A17]" x-text="item.name"></p>
                                        <p class="text-[11px] text-[#8A7F72]" x-text="item.size"></p>
                                    </div>
                                    <button type="button" @click="remove(index)" class="absolute right-2 top-2 rounded-lg bg-white/95 p-1.5 text-red-600 shadow-sm hover:bg-red-50 transition-colors" aria-label="Remove photo">
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <button
                    type="submit"
                    :disabled="files.length === 0"
                    :class="files.length === 0 ? 'bg-[#D3CCBE] cursor-not-allowed' : 'bg-[#E8642A] hover:bg-[#F07C42]'"
                    class="w-full py-3 rounded-xl text-white font-bold transition-colors"
                >
                    Upload Selected Photos
                </button>
            </form>

            @if(!empty($galleryImages))
                <div class="mt-6">
                    <h3 class="text-lg font-bold text-[#1C1A17] mb-4">Current Gallery</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($galleryImages as $index => $image)
                            <div class="relative group">
                                @if(str_starts_with($image, 'http'))
                                    <img src="{{ $image }}" alt="Gallery" class="w-full h-40 object-cover rounded-xl">
                                @elseif(str_starts_with($image, '/storage/'))
                                    <img src="{{ $image }}" alt="Gallery" class="w-full h-40 object-cover rounded-xl">
                                @else
                                    <img src="{{ asset($image) }}" alt="Gallery" class="w-full h-40 object-cover rounded-xl">
                                @endif
                                <form method="POST" action="{{ route('caterer.profile.gallery.delete', $index) }}" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors" onclick="return confirm('Delete this photo?')">
                                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-6 p-8 rounded-xl bg-[#FDF6EE] border border-[#EDE4D8] text-center">
                    <svg class="size-12 text-[#D3CCBE] mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    <p class="text-sm font-bold text-[#1C1A17]">No gallery photos yet</p>
                    <p class="text-xs text-[#8A7F72] mt-1">Upload your best event setups, food spreads, and team photos.</p>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
