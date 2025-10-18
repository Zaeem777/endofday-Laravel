<x-customer-layout>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Available Restaurants</h1>

    @if($restaurants->isEmpty())
        <p class="text-gray-600">No restaurants available right now.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($restaurants as $restaurant)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition">
                    @if($restaurant->image)
                        <img src="{{ asset('storage/' . $restaurant->image) }}" 
                             alt="{{ $restaurant->name }}" 
                             class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $restaurant->name }}</h3>
                        <p class="text-gray-600 text-sm">📍 {{ $restaurant->address }}</p>
                        <p class="text-gray-600 text-sm">📞 {{ $restaurant->phone }}</p>

                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('Customer.restaurant.menu', $restaurant->id) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                View Menu
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-customer-layout>
