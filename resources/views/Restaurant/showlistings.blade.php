<x-layout>
    <div class="max-w-7xl mx-auto mt-10 px-4">
        <h2 class="text-3xl font-bold mb-8 text-gray-800 text-center">🍰 My Listings</h2>

        @if($listings->isEmpty())
            <div class="text-center text-gray-500 text-lg mt-8">
                You haven't created any listings yet.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($listings as $listing)
                    <div
                        class="group bg-white shadow-md rounded-2xl overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300">

                        <!-- Image -->
                        @if($listing->image)
                            <div class="relative overflow-hidden">
                                <img src="{{ asset('storage/' . $listing->image) }}" alt="{{ $listing->name }}"
                                    class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

                                <div
                                    class="absolute bottom-3 left-3 text-white text-sm px-3 py-1 bg-black/50 rounded-full backdrop-blur-sm">
                                    {{ $listing->category }}
                                </div>
                            </div>
                        @else
                            <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400 text-sm">
                                No Image
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                {{ $listing->name }}
                            </h3>

                            <p class="text-gray-500 text-sm mt-2 line-clamp-2">
                                {{ Str::limit($listing->description, 60) }}
                            </p>

                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="px-3 py-1 text-xs bg-blue-100 text-blue-700 font-medium rounded-full">
                                    💸 Price: {{ $listing->price }} PKR
                                </span>

                                @if($listing->discountedprice)
                                    <span class="px-3 py-1 text-xs bg-green-100 text-green-700 font-medium rounded-full">
                                        🔖 {{ $listing->discountedprice }} PKR
                                    </span>
                                @endif

                                <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 font-medium rounded-full">
                                    🧁 Remaining: {{ $listing->remainingitem }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-4 mt-6 border-t border-gray-100 pt-4">
                                <a href="{{ route('listing.edit', $listing->id) }}"
                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                    ✏️ Edit
                                </a>

                                <button type="button" onclick="openModal({{ $listing->id }})"
                                    class="text-red-600 hover:text-red-800 transition-colors">
                                    🗑️ Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 transition-opacity">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 transform transition-all scale-95 opacity-0"
            id="modalContent">
            <h2 class="text-xl font-semibold text-gray-900">Confirm Delete</h2>
            <p class="text-gray-600 mt-2">Are you sure you want to delete this listing?</p>

            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
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

            form.action = `/Restaurant/listings/${listingId}`;
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
</x-layout>