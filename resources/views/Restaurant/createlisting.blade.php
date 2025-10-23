<x-layout>
    <div class="min-h-screen  py-10">
        <div
            class="max-w-3xl mx-auto bg-white/90 backdrop-blur-md shadow-2xl rounded-2xl p-10 border border-purple-100">

            <!-- Heading -->
            <h2 class="text-3xl font-extrabold text-center text-purple-800 mb-8">
                {{ isset($listing) ? 'Edit Your Listing' : 'Add a New Listing' }}
            </h2>

            <!-- Form -->
            <form method="POST"
                action="{{ isset($listing) ? route('listing.update', $listing->id) : route('Restaurant.createlisting') }}"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($listing))
                    @method('PUT')
                @endif

                {{-- <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $listing->name ?? '') }}"
                        class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition">
                    @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $listing->name ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Manufacture Date</label>
                        <input type="date" name="manufacturedate"
                            value="{{ old('manufacturedate', isset($listing) ? $listing->manufacturedate->format('Y-m-d') : '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition">
                        @error('manufacturedate') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>



                <!-- Price + Discounted Price (Side by Side) -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Price</label>
                        <input type="number" name="price" value="{{ old('price', $listing->price ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition">
                        @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Discounted Price</label>
                        <input type="number" name="discountedprice"
                            value="{{ old('discountedprice', $listing->discountedprice ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition">
                        @error('discountedprice') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Remaining Items + Manufacture Date -->
                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                        <input type="text" name="category" value="{{ old('category', $listing->category ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition">
                        @error('category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Remaining Items</label>
                        <input type="number" name="remainingitem"
                            value="{{ old('remainingitem', $listing->remainingitem ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition">
                        @error('remainingitem') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>



                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 p-3 text-gray-800 shadow-sm transition resize-none">{{ old('description', $listing->description ?? '') }}</textarea>
                    @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Listing Image</label>
                    <div class="flex items-center justify-center w-full">
                        <label
                            class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer hover:bg-purple-50 border-gray-300 hover:border-purple-400 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-purple-600" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold text-purple-700">Click to upload</span> or drag & drop
                                </p>
                            </div>
                            <input type="file" name="image" class="hidden" />
                        </label>
                    </div>
                    @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-6 text-center">
                    <button type="submit"
                        class="px-8 py-3 bg-purple-600 text-white font-semibold rounded-full shadow-lg hover:bg-purple-700 hover:shadow-xl transition-all duration-300">
                        {{ isset($listing) ? 'Update Listing' : 'Add Listing' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>