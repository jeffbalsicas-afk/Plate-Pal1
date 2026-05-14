<x-dashboard-layout
    title="Feedback - PlatePal"
    heading="Feedback"
    :username="$displayName"
    :usersub="ucfirst($user->role)"
    :initials="$initials"
>
    <div class="max-w-3xl mx-auto">
        <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#E8642A] hover:text-[#F07C42] transition-colors mb-6">
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

        <section class="rounded-2xl border border-[#EDE4D8] bg-white shadow-sm overflow-hidden">
            <div class="border-b border-[#EDE4D8] p-6 sm:p-8">
                <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17]">Feedback</h1>
                <p class="mt-2 text-sm sm:text-base text-[#8A6D3F]">Tell us what feels confusing, broken, or helpful in PlatePal.</p>
            </div>

            <form method="POST" action="{{ route('feedback.store') }}" class="p-6 sm:p-8 space-y-5">
                @csrf
                <input type="hidden" name="page_url" value="{{ old('page_url', url()->previous()) }}">

                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="block">
                        <span class="block text-sm font-bold text-[#1C1A17] mb-2">Feedback Type</span>
                        <select name="type" class="w-full rounded-xl border border-[#EDE4D8] bg-[#FDF6EE] px-4 py-3 text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]" required>
                            <option value="experience" @selected(old('type') === 'experience')>Experience</option>
                            <option value="bug" @selected(old('type') === 'bug')>Bug or issue</option>
                            <option value="suggestion" @selected(old('type') === 'suggestion')>Suggestion</option>
                            <option value="other" @selected(old('type') === 'other')>Other</option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="block text-sm font-bold text-[#1C1A17] mb-2">Rating</span>
                        <select name="rating" class="w-full rounded-xl border border-[#EDE4D8] bg-[#FDF6EE] px-4 py-3 text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A]">
                            <option value="">No rating</option>
                            @for($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}" @selected((string) old('rating') === (string) $rating)>{{ $rating }} {{ $rating === 1 ? 'star' : 'stars' }}</option>
                            @endfor
                        </select>
                    </label>
                </div>

                <label class="block">
                    <span class="block text-sm font-bold text-[#1C1A17] mb-2">Message</span>
                    <textarea name="message" rows="6" class="w-full rounded-xl border border-[#EDE4D8] bg-[#FDF6EE] px-4 py-3 text-sm text-[#1C1A17] focus:outline-none focus:border-[#E8642A] resize-none" placeholder="Example: The booking status was hard to understand..." required>{{ old('message') }}</textarea>
                </label>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-2">
                    <a href="{{ $dashboardRoute }}" class="inline-flex justify-center rounded-xl border border-[#EDE4D8] px-5 py-3 text-sm font-bold text-[#8A6D3F] transition-colors hover:bg-[#FDF6EE]">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center rounded-xl bg-[#E8642A] px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-[#F07C42]">
                        Send Feedback
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-dashboard-layout>
