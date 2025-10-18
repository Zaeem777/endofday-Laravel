<x-layout>
<div class="max-w-2xl mx-auto mt-10 bg-white shadow-xl rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">{{ isset($listing) ? 'Edit Listing' : 'Add Listing' }}</h2>

    <form method="POST" action="{{ isset($listing) ? route('listing.update', $listing->id) : route('Restaurant.createlisting') }}" 
          enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if(isset($listing))
            @method('PUT')
        @endif

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Name</label>
            <input type="text" name="name" value="{{ old('name', $listing->name ?? '') }}"
                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm p-2.5 focus:ring focus:ring-blue-200 focus:border-blue-500">
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Price -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Price</label>
            <input type="number" name="price" value="{{ old('price', $listing->price ?? '') }}"
                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm p-2.5 focus:ring focus:ring-blue-200 focus:border-blue-500">
            @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Discounted Price -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Discounted Price</label>
            <input type="number" name="discountedprice" value="{{ old('discountedprice', $listing->discountedprice ?? '') }}"
                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm p-2.5 focus:ring focus:ring-blue-200 focus:border-blue-500">
            @error('discountedprice') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Category -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Category</label>
            <input type="text" name="category" value="{{ old('category', $listing->category ?? '') }}"
                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm p-2.5 focus:ring focus:ring-blue-200 focus:border-blue-500">
            @error('category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Remaining Items -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Remaining Items</label>
            <input type="number" name="remainingitem" value="{{ old('remainingitem', $listing->remainingitem ?? '') }}"
                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm p-2.5 focus:ring focus:ring-blue-200 focus:border-blue-500">
            @error('remainingitem') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Manufacture Date -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Manufacture Date</label>
            <input type="date" name="manufacturedate" value="{{ old('manufacturedate', isset($listing) ? $listing->manufacturedate->format('Y-m-d') : '') }}"
                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm p-2.5 focus:ring focus:ring-blue-200 focus:border-blue-500">
            @error('manufacturedate') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Description</label>
            <textarea name="description" rows="3" 
                      class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm p-2.5 focus:ring focus:ring-blue-200 focus:border-blue-500">{{ old('description', $listing->description ?? '') }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Image -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Listing Image</label>
            <input type="file" name="image"
                   class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Submit -->
        <div class="flex justify-center pt-4 border-t">
            <button type="submit" 
                    class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 font-semibold shadow-md">
                {{ isset($listing) ? 'Update Listing' : 'Add Listing' }}
            </button>
        </div>
    </form>
</div>
</x-layout>
