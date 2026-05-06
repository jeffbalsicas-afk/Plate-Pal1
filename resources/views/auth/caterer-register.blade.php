<x-layout title="Join as Caterer – PlatePal">
<div class="flex min-h-screen font-sans">
    {{-- Left side --}}
    <div class="w-full lg:w-1/2 flex flex-col px-8 lg:px-20 py-10 bg-white overflow-y-auto">
        <div class="flex items-center justify-between mb-12">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="/assets/PlatePal_logo.jpg" alt="PlatePal" class="w-10 h-10 rounded-xl object-cover">
                <span class="text-xl font-bold font-display tracking-tight text-gray-900">PLATE<span class="text-[#f44e08] font-display">PAL</span></span>
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to Home
            </a>
        </div>

        <div class="flex-1 flex flex-col justify-center max-w-md mx-auto w-full pb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-2 text-center">Join as a Caterer</h1>
            <p class="text-gray-500 text-lg mb-8 text-center">Start growing your catering business with PlatePal</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('caterer.register') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name') }}" required placeholder="Lola Maria's Kitchen"
                        class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border {{ $errors->has('business_name') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-[#111827] transition-all">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Owner's Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Maria Santos"
                        class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-[#111827] transition-all">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com"
                        class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-[#111827] transition-all">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="0912 345 6789"
                        class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-[#111827] transition-all">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Barangay</label>
                    <select name="barangay" required
                        class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border {{ $errors->has('barangay') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-[#111827] transition-all">
                        <option value="">Select your barangay</option>
                        @foreach(['Magugpo Poblacion', 'Apokon', 'Visayan Village', 'Mankilam', 'New Balamban', 'Pagsabangan', 'Magugpo East', 'Magugpo West', 'San Isidro', 'San Miguel', 'San Agustin', 'Nueva Fuerza', 'Bincungan', 'Busaon', 'Canocotan', 'La Filipina', 'Liboganon', 'Madaum', 'Magugpo North', 'Magugpo South', 'Pandapan', 'Cuambogan', 'Magdum'] as $barangay)
                        <option value="{{ $barangay }}" {{ old('barangay') === $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required placeholder="Create a strong password"
                            class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} focus:outline-none focus:ring-2 focus:ring-[#111827] transition-all pr-12">
                        <button type="button" onclick="togglePassword('password', 'eye-pass')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-pass" class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Re-enter your password"
                            class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#111827] transition-all pr-12">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-confirm" class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 rounded-xl bg-[#111827] text-white text-lg font-bold hover:bg-gray-800 transition-all shadow-md mt-4">
                    Create Caterer Account
                </button>
            </form>

            <div class="mt-8 space-y-3 text-center">
                <p class="text-gray-600 font-medium">Already have an account? <a href="{{ route('caterer.login') }}" class="text-[#f44e08] font-bold hover:underline ml-1">Sign in</a></p>
                <p class="text-gray-600 font-medium">Are you a client? <a href="{{ route('register') }}" class="text-[#f44e08] font-bold hover:underline ml-1">Sign up here</a></p>
            </div>
        </div>
    </div>

    {{-- Right side --}}
    <div class="hidden lg:flex lg:w-1/2 bg-[#111827] flex-col justify-center px-20 text-white">
        <div class="max-w-lg">
            <h2 class="text-4xl font-bold leading-tight mb-6">Build Your Catering Empire</h2>
            <p class="text-xl text-gray-400 leading-relaxed mb-12">Join PlatePal's growing network of successful caterers in Tagum City and take your business to the next level.</p>
            <ul class="space-y-6">
                @foreach(['No setup fees - start for free', 'Easy-to-use dashboard and tools', 'Connect with local clients daily'] as $benefit)
                <li class="flex items-center gap-4">
                    <div class="bg-white/10 rounded-full p-1"><svg class="size-5 text-gray-300" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                    <span class="text-xl font-medium text-gray-200">{{ $benefit }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    icon.innerHTML = isPassword
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}
</script>
</x-layout>
