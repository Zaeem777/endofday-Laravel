<x-layout>
<div 
    class="max-w-2xl mx-4 sm:mx-auto mt-5 bg-white/90 backdrop-blur-md shadow-2xl rounded-2xl border border-gray-100 overflow-hidden text-gray-900 transition-all duration-300"
    x-data="{ editMode: {{ $errors->any() ? 'true' : 'false' }} }">

    <!-- Cover Image -->
    <div class="relative h-40 sm:h-48">
        <img src="https://images.unsplash.com/photo-1549880338-65ddcdfd017b?auto=format&fit=crop&w=1200&q=80" 
             class="absolute inset-0 object-cover w-full h-full" alt="Restaurant Banner">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 to-transparent"></div>
    </div>

    <!-- Profile Image -->
    <div class="relative flex justify-center -mt-16 sm:-mt-20">
        <div class="w-32 h-32 border-4 border-white rounded-full overflow-hidden shadow-lg">
            <img class="object-cover w-full h-full"
                 src="{{ $restaurant->image ? asset('storage/' . $restaurant->image) : 'https://via.placeholder.com/150' }}"
                 alt="{{ $restaurant->name }}">
        </div>
    </div>

    <!-- Profile Content -->
    <div class="p-8">
        <!-- View Mode -->
        <div x-show="!editMode" x-transition>
            <div class="space-y-6">
                <!-- Info fields -->
                @foreach ([
                    'Name' => $restaurant->name,
                    'Email' => $restaurant->email,
                    'Phone' => $restaurant->phone,
                    'Address' => $restaurant->address
                ] as $label => $value)
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wide">{{ $label }}</label>
                        <p class="mt-1 text-base font-medium text-gray-800 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-100 shadow-sm">
                            {{ $value }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Edit Button -->
            <div class="flex justify-center pt-8 mt-8 border-t">
                <button @click="editMode = true"
                    class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full shadow-md hover:shadow-lg transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5h2m-1-1v2m6.364 3.636a9 9 0 11-12.728 0 9 9 0 0112.728 0z" />
                    </svg>
                    Edit Profile
                </button>
            </div>
        </div>

        <!-- Edit Mode -->
        <div x-show="editMode" x-transition>
            <form method="POST" action="{{ route('Restaurant.updateProfile') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Form Fields -->
                @foreach ([
                    'name' => 'Name',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'address' => 'Address'
                ] as $field => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
                        <input type="{{ $field === 'email' ? 'email' : 'text' }}" name="{{ $field }}" 
                               value="{{ old($field, $restaurant->$field) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                        @error($field) 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                @endforeach

                <!-- Password Fields -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">New Password</label>
                        <input type="password" name="password" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                        @error('password') 
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Profile Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Profile Image</label>
                    <input type="file" name="image"
                           class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full 
                                  file:border-0 file:text-sm file:font-semibold file:bg-blue-50 
                                  file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl px-4 py-2 transition">
                    @error('image') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-center space-x-4 pt-6 border-t mt-8">
                    <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-full shadow-md hover:shadow-lg transition-all duration-200">
                        💾 Save Changes
                    </button>
                    <button type="button" @click="editMode = false"
                        class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-full shadow-md hover:shadow-lg transition-all duration-200">
                        ✖ Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layout>
