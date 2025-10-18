
<x-layout>
    
<div class="max-w-7xl mx-auto mt-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">My Listings</h2>

    @if($listings->isEmpty())
        <p class="text-gray-600">You haven't created any listings yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($listings as $listing)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                 
                    @if($listing->image)
                        <img src="{{ asset('storage/'. $listing->image) }}" 
                             alt="{{ $listing->name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $listing->name }}</h3>
                        <p class="text-gray-600 text-sm">Category: {{ $listing->category }}</p>
                        <p class="text-gray-600 text-sm">Price: {{ $listing->price }} PKR</p>
                        @if($listing->discountedprice)
                            <p class="text-green-600 text-sm">Discounted: {{ $listing->discountedprice }} PKR</p>
                        @endif
                        <p class="text-gray-600 text-sm">Remaining: {{ $listing->remainingitem }}</p>
                        <p class="text-gray-500 text-sm mt-2">{{ Str::limit($listing->description, 50) }}</p>
                        
                        <div class="flex justify-end space-x-3 mt-4">
                            <!-- Edit Button -->
                            <a href="{{ route('listing.edit', $listing->id) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                ✏️
                            </a>

                            <!-- Delete Button (opens modal) -->
                            <button type="button" 
                                    onclick="openModal({{ $listing->id }})" 
                                    class="text-red-600 hover:text-red-800">
                                🗑️
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class=" hidden fixed inset-0 bg-gray-900 flex bg-opacity-50  items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 transform transition-all scale-95 opacity-0" id="modalContent">
        <h2 class="text-lg font-semibold text-gray-900">Confirm Delete</h2>
        <p class="text-gray-600 mt-2">Are you sure you want to delete this listing?</p>

        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="closeModal()" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                Cancel
            </button>

            <!-- Delete form -->
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(listingId) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        const form = document.getElementById('deleteForm');

        // Set form action dynamically
        form.action = `/Restaurant/listings/${listingId}`;

        modal.classList.remove('hidden');

        // animate modal
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');

        // reverse animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
</x-layout>
