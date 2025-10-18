<x-layout>
<div class="max-w-2xl mx-4 sm:mx-auto mt-16 bg-white shadow-xl rounded-lg text-gray-900"
     x-data="{ editMode: {{ $errors->any() ? 'true' : 'false' }} }">

    <!-- Top cover image -->
    <div class="rounded-t-lg h-32 overflow-hidden">
        <img class="object-cover object-top w-full" 
             src="https://images.unsplash.com/photo-1549880338-65ddcdfd017b?auto=format&fit=crop&w=1200&q=80" 
             alt="Restaurant Banner">
    </div>

    <!-- Profile Image -->
    <div class="mx-auto w-32 h-32 relative -mt-16 border-4 border-white rounded-full overflow-hidden">
        <img class="object-cover object-center h-32 w-32"
             src="{{ $restaurant->image ? asset('storage/' . $restaurant->image) : 'https://via.placeholder.com/150' }}"
             alt="{{ $restaurant->name }}">
    </div>

    <!-- Profile View Mode -->
    <div class="p-6" x-show="!editMode">
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-600">Name</label>
                <p class="mt-2 block w-full rounded-lg border p-2.5">{{ $restaurant->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Email</label>
                <p class="mt-2 block w-full rounded-lg border p-2.5">{{ $restaurant->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Phone</label>
                <p class="mt-2 block w-full rounded-lg border p-2.5">{{ $restaurant->phone }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Address</label>
                <p class="mt-2 block w-full rounded-lg border p-2.5">{{ $restaurant->address }}</p>
            </div>
        </div>

        <div class="flex justify-center pt-6 border-t mt-6">
            <button @click="editMode = true"
                class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 font-semibold shadow-md">
                Edit Profile
            </button>
        </div>
    </div>

    <!-- Profile Edit Mode -->
    <div class="p-6" x-show="editMode">
        <form method="POST" action="{{ route('Restaurant.updateProfile') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Name</label>
                <input type="text" name="name" value="{{ old('name', $restaurant->name) }}"
                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2.5">
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" name="email" value="{{ old('email', $restaurant->email) }}"
                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2.5">
                @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $restaurant->phone) }}"
                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2.5">
                @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Address</label>
                <input type="text" name="address" value="{{ old('address', $restaurant->address) }}"
                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2.5">
                @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-600">New Password</label>
                <input type="password" name="password"
                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2.5">
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2.5">
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Profile Image</label>
                <input type="file" name="image"
                       class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                              file:rounded-full file:border-0 file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4 justify-center pt-4 border-t">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 font-semibold shadow-md">
                    Save Changes
                </button>
                <button type="button" @click="editMode = false"
                    class="bg-gray-500 text-white px-6 py-2 rounded-full hover:bg-gray-600 font-semibold shadow-md">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout>
