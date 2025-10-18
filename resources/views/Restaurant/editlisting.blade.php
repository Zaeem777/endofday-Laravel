<x-layout>
<div class="max-w-3xl mx-auto mt-10 px-6 py-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Listing</h2>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Listing Form -->
    <form action="{{ route('listing.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $listing->name) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Price -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Price (PKR)</label>
            <input type="number" name="price" value="{{ old('price', $listing->price) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Discounted Price -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Discounted Price (PKR)</label>
            <input type="number" name="discountedprice" value="{{ old('discountedprice', $listing->discountedprice) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Category</label>
            <input type="text" name="category" value="{{ old('category', $listing->category) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Remaining Items -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Remaining Items</label>
            <input type="number" name="remainingitem" value="{{ old('remainingitem', $listing->remainingitem) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Manufacture Date -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Manufacture Date</label>
            <input type="date" name="manufacturedate" value="{{ old('manufacturedate', $listing->manufacturedate) }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Description</label>
            <textarea name="description" rows="4" 
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">{{ old('description', $listing->description) }}</textarea>
        </div>

        <!-- Current Image Preview -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Current Image</label>
            @if($listing->image)
                <img src="{{ asset('storage/' . $listing->image) }}" 
                     alt="Listing Image" 
                     class="w-32 h-32 object-cover rounded-lg mb-3 border">
            @else
                <p class="text-gray-500">No image uploaded</p>
            @endif
        </div>

        <!-- Upload New Image -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-1">Upload New Image</label>
            <input type="file" name="image" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('Restaurant.showlistings') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update Listing
            </button>
        </div>
    </form>
</div>
</x-layout>
