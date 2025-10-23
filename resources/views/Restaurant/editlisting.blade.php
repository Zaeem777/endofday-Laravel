<x-layout>
    <div
        class="max-w-3xl mx-auto mt-12 mb-12 px-6 py-10 bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100">

        <!-- Title -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">✏️ Edit Your Listing</h2>
            <p class="text-gray-500 text-sm mt-2">Update your product details and make it shine!</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg">
                <ul class="list-disc ml-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Listing Form -->
        <form action="{{ route('listing.update', $listing->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')
            <!-- Name & Discounted Price -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $listing->name) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Manufacture Date</label>
                    <input type="date" name="manufacturedate"
                        value="{{ old('manufacturedate', $listing->manufacturedate) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                </div>
            </div>

            {{-- Price and Discounted Price --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (PKR)</label>
                    <input type="number" name="price" value="{{ old('price', $listing->price) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discounted Price (PKR)</label>
                    <input type="number" name="discountedprice"
                        value="{{ old('discountedprice', $listing->discountedprice) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                </div>
            </div>
            {{--
    </div> --}}

    <!-- Category & Remaining Items -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <input type="text" name="category" value="{{ old('category', $listing->category) }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Remaining Items</label>
            <input type="number" name="remainingitem" value="{{ old('remainingitem', $listing->remainingitem) }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
        </div>
    </div>

    <!-- Manufacture Date -->
    {{-- <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Manufacture Date</label>
        <input type="date" name="manufacturedate" value="{{ old('manufacturedate', $listing->manufacturedate) }}"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
    </div> --}}

    <!-- Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
        <textarea name="description" rows="4"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">{{ old('description', $listing->description) }}</textarea>
    </div>

    <!-- Current Image Preview -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-3">Current Image</label>
        @if($listing->image)
            <div class="flex items-center space-x-4">
                <img src="{{ asset('storage/' . $listing->image) }}" alt="Listing Image"
                    class="w-32 h-32 object-cover rounded-xl shadow-sm border border-gray-200">
                <div class="text-sm text-gray-500">You can upload a new one below.</div>
            </div>
        @else
            <p class="text-gray-500">No image uploaded</p>
        @endif
    </div>

    <!-- Upload New Image -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Image</label>
        <input type="file" name="image"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent transition bg-gray-50">
    </div>

    <!-- Submit Buttons -->
    <div class="flex justify-end space-x-3 pt-4">
        <a href="{{ route('Restaurant.showlistings') }}"
            class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
            Cancel
        </a>
        <button type="submit"
            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition font-medium">
            💾 Update Listing
        </button>
    </div>
    </form>
    </div>
</x-layout>