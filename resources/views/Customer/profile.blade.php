<x-customer-layout>
    <div class="min-h-screen py-10 px-6">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                <h2 class="text-2xl font-bold">My Profile</h2>
                <p class="text-sm opacity-80">Manage your personal information</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <!-- Left Column: Profile Info -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Profile Information</h3>

                    <div class="space-y-3">
                        <p><span class="font-semibold text-gray-600">Name:</span> {{ $customer->name }}</p>
                        <p><span class="font-semibold text-gray-600">Email:</span> {{ $customer->email }}</p>
                        <p><span class="font-semibold text-gray-600">Phone:</span> {{ $customer->phone }}</p>

                    </div>
                </div>

                <!-- Right Column: Edit Form -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Edit Profile</h3>

                    @if(session('success'))
                        <div class="mb-4 p-3 text-sm text-purple-700 bg-purple-100 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('Customer.updateProfile') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Password (leave blank if not
                                changing)</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>

                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>

                        <button type="submit"
                            class="w-full bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-600 transition">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-customer-layout>