<x-customer-layout>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $restaurants->name }} - Menu</h1>

    @if($listings->isEmpty())
        <p class="text-gray-600">This restaurant has no listings yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($listings as $listing)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                    @if($listing->image)
                        <img src="{{ asset('storage/' . $listing->image) }}" 
                             alt="{{ $listing->name }}" 
                             class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
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

                        <!-- ✅ Add to Cart button -->
                       <button type="button"
                          onclick="addToCart({{ $listing->id }})"
                         
                         class="w-full bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition">
                        Add to Cart
                        </button>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-customer-layout>
