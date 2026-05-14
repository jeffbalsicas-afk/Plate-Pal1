<x-layout title="Forgot Password - PlatePal">
<div class="min-h-screen bg-white flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <div class="flex items-center justify-between mb-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="/assets/PlatePal_logo.jpg" alt="PlatePal" class="w-10 h-10 rounded-xl object-cover">
                <span class="text-xl font-bold tracking-tight">
                    <span class="text-gray-900 font-display">PLATE</span><span class="text-[#f44e08] font-display">PAL</span>
                </span>
            </a>
            <a href="{{ route($loginRoute ?? 'login') }}" class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to sign in
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset your password</h1>
            <p class="text-sm text-gray-500 mb-6">Enter your account email and PlatePal will send a password reset link.</p>

            @if(session('status'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com"
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} text-gray-900 placeholder:text-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#f44e08] transition-all">
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#f44e08] text-white text-sm font-bold hover:bg-[#d94406] transition-all shadow-md shadow-orange-200">
                    Send Reset Link
                </button>
            </form>
        </div>
    </div>
</div>
</x-layout>
