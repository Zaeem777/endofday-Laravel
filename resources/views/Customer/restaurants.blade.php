<x-customer-layout>
    <div class=" mx-auto px-6 py-10">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">
                🍽️ Discover Delicious Restaurants
            </h1>
            <p class="text-gray-600 text-lg">Find your next favorite meal from our handpicked local restaurants.</p>
        </div>

        @if($restaurants->isEmpty())
            <div class="bg-white shadow-sm rounded-xl p-8 text-center border">
                <p class="text-gray-500 text-lg">No restaurants available right now. Please check back later!</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($restaurants as $restaurant)
                    <div
                        class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <!-- Restaurant Image -->
                        <div class="relative">
                            @if($restaurant->image)
                                <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                            @else
                                <div>
                                    <img src="{{ asset('storage/no-image.jpg') }}" alt="No image available"
                                        class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                                </div>
                            @endif

                            <!-- Overlay Gradient -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition">
                            </div>

                            <!-- View Menu Button on Hover -->
                            <div
                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('Customer.restaurant.menu', $restaurant->id) }}"
                                    class="bg-blue-600 text-white font-medium px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                                    🍴 View Menu
                                </a>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $restaurant->name }}</h3>
                            <div class="flex flex-col gap-1 text-sm text-gray-600">
                                <p class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 12.414A4 4 0 1118 8a4 4 0 01-4.586 4.586L16.657 17.657a2 2 0 11-2.828 2.828L6 12.828a8 8 0 1111.314-11.314l.343.343" />
                                    </svg>
                                    {{ $restaurant->address }}
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a2 2 0 011.789 1.106l1.387 2.772A2 2 0 0111 8.618V11a1 1 0 001 1h2.382a2 2 0 011.742.97l2.772 4.957A2 2 0 0120 19h1a1 1 0 001 1v2a1 1 0 01-1 1H19a9 9 0 01-9-9V9a2 2 0 01.225-.9L8.03 5.121A2 2 0 006.618 5H5a2 2 0 01-2-2z" />
                                    </svg>
                                    {{ $restaurant->phone }}
                                </p>
                            </div>

                            <!-- CTA Button (Visible on Mobile / Non-hover Devices) -->
                            <div class="mt-4 sm:hidden">
                                <a href="{{ route('Customer.restaurant.menu', $restaurant->id) }}"
                                    class="w-full inline-block text-center bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                                    🍴 View Menu
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-customer-layout>