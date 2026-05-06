<x-layout title="Create Account – PlatePal">
<div class="flex min-h-screen font-sans flex-col lg:flex-row">
    {{-- Left side: Registration form --}}
    <div class="w-full lg:w-1/2 flex flex-col px-4 sm:px-8 lg:px-20 py-6 sm:py-10 bg-white overflow-y-auto">
        {{-- Top bar: Logo + Back to Home --}}
        <div class="flex items-center justify-between mb-8 sm:mb-12">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="/assets/PlatePal_logo.jpg" alt="PlatePal" class="w-8 sm:w-10 h-8 sm:h-10 rounded-lg sm:rounded-xl object-cover">
                <span class="text-lg sm:text-xl font-bold tracking-tight">
                    <span class="text-gray-900 font-display">PLATE</span><span class="text-[#f44e08] font-display">PAL</span>
                </span>
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                <svg class="size-3 sm:size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Home
            </a>
        </div>

        {{-- Centered form content --}}
        <div class="flex-1 flex flex-col justify-center max-w-md mx-auto w-full pb-6 sm:pb-10">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-1 sm:mb-2">Create Your Account</h1>
            <p class="text-gray-500 text-sm sm:text-base md:text-lg mb-6 sm:mb-8">Join PlatePal to discover and book local caterers</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
                @csrf

                {{-- Full Name --}}
                <div>
                    <label class="block text-sm sm:text-base font-semibold text-gray-900 mb-1.5 sm:mb-2">Full Name</label>
                    <input type="text" name="name" required placeholder="Juan Dela Cruz"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder:text-gray-400 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#f44e08] transition-all">
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-sm sm:text-base font-semibold text-gray-900 mb-1.5 sm:mb-2">Email Address</label>
                    <input type="email" name="email" required placeholder="you@example.com"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder:text-gray-400 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#f44e08] transition-all">
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-sm sm:text-base font-semibold text-gray-900 mb-1.5 sm:mb-2">Phone Number</label>
                    <input type="tel" name="phone" required placeholder="0912 345 6789"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder:text-gray-400 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#f44e08] transition-all">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm sm:text-base font-semibold text-gray-900 mb-1.5 sm:mb-2">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required placeholder="Create a strong password"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder:text-gray-400 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#f44e08] transition-all pr-10 sm:pr-12">
                        <button type="button" onclick="togglePassword('password', 'eye-pass')"
                            class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-pass" class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm sm:text-base font-semibold text-gray-900 mb-1.5 sm:mb-2">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Re-enter your password"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder:text-gray-400 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-[#f44e08] transition-all pr-10 sm:pr-12">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                            class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-confirm" class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 sm:py-4 rounded-lg sm:rounded-xl bg-[#f44e08] text-white text-sm sm:text-lg font-bold hover:bg-[#d94406] transition-all shadow-md shadow-orange-200 mt-2 sm:mt-4">
                    Create Account
                </button>
            </form>

            <div class="mt-6 sm:mt-10 space-y-2 sm:space-y-3 text-center">
                <p class="text-gray-600 font-medium text-xs sm:text-base">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#f44e08] font-bold hover:underline ml-1">Sign in</a>
                </p>
                <p class="text-gray-600 font-medium text-xs sm:text-base">
                    Are you a caterer?
                    <a href="{{ route('caterer.register') }}" class="text-[#f44e08] font-bold hover:underline ml-1">Sign up here</a>
                </p>
            </div>
        </div>
    </div>

    {{-- Right side: Welcome text --}}
    <div class="hidden lg:flex lg:w-1/2 bg-[#f44e08] flex-col justify-center px-8 lg:px-20 py-10 text-white">
        <div class="max-w-lg">
            <h2 class="text-3xl lg:text-5xl font-bold leading-tight mb-4 lg:mb-6">Welcome to PlatePal</h2>
            <p class="text-base lg:text-xl text-white/90 leading-relaxed mb-8 lg:mb-12">
                Join thousands of happy clients who found their perfect caterer for special events in Tagum City.
            </p>

            <ul class="space-y-4 lg:space-y-6">
                @foreach([
                    'Quick and easy booking process',
                    'Verified and trusted local caterers',
                    'Transparent pricing with no hidden fees'
                ] as $benefit)
                <li class="flex items-center gap-3 lg:gap-4">
                    <div class="bg-white/20 rounded-full p-1 shrink-0">
                        <svg class="size-3 lg:size-5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-base lg:text-xl font-medium">{{ $benefit }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</x-layout>
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
