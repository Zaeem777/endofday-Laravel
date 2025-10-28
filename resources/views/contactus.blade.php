<x-dynamic-component :component="$layout">
    <div class="min-h-screen flex justify-center items-start pt-10">

        <div class="bg-white shadow-xl rounded-2xl p-8 max-w-2xl w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold text-blue-700 mb-2">Contact Us</h1>
                <p class="text-gray-500">We’d love to hear from you! Fill out the form below to get in touch.</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('ContactUs.submit') }}" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                        <input type="text" id="phone" name="phone" maxlength="11"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition outline-none"
                            placeholder="03XXXXXXXXX" value="{{ old('phone') }}">
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition outline-none"
                            placeholder="you@example.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Topic -->
                <div>
                    <label for="topic" class="block text-sm font-semibold text-gray-700 mb-2">Topic</label>
                    <input type="text" id="topic" name="topic"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition outline-none"
                        placeholder="Write your topic here..." value="{{ old('topic') }}">
                    @error('topic')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body -->
                <div>
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                    <textarea id="body" name="body" rows="5"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition outline-none"
                        placeholder="Type your message...">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 text-center">
                    <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-8 py-3 rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dynamic-component>