<x-customer-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2 tracking-tight">
                🍽️ {{ $restaurants->name }} Menu
            </h1>
            <p class="text-gray-600 text-lg">Explore delicious items freshly made for you.</p>
        </div>

        @if($listings->isEmpty())
            <div class="bg-white shadow-sm rounded-xl p-8 text-center border">
                <p class="text-gray-500 text-lg">This restaurant has no listings yet.</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($listings as $listing)
                    <div class="group bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">

                        <!-- Image Section -->
                        <div class="relative">
                            @if($listing->image)
                                <img src="{{ asset('storage/' . $listing->image) }}"
                                    alt="{{ $listing->name }}"
                                    class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                 <div>
                                    <img src="{{ asset('storage/no-image.jpg') }}" alt="No image available"
                                        class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                                </div>
                            @endif

                            @if($listing->discountedprice)
                                <div class="absolute top-3 left-3 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow">
                                    {{ round((1 - ($listing->discountedprice / $listing->price)) * 100) }}% OFF
                                </div>
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $listing->name }}</h3>
                            <p class="text-sm text-gray-500 mb-3 capitalize">{{ $listing->category }}</p>

                            <!-- Price -->
                            <div class="flex items-center gap-2 mb-3">
                                @if($listing->discountedprice)
                                    <p class="text-gray-400 line-through text-sm">{{ number_format($listing->price) }} PKR</p>
                                    <p class="text-green-600 font-semibold text-lg">{{ number_format($listing->discountedprice) }} PKR</p>
                                @else
                                    <p class="text-blue-600 font-semibold text-lg">{{ number_format($listing->price) }} PKR</p>
                                @endif
                            </div>

                            <!-- Remaining stock -->
                            <p class="text-sm text-gray-600 mb-3">
                                🧺 <span class="font-medium">{{ $listing->remainingitem }}</span> items left
                            </p>

                            <!-- Description -->
                            <p class="text-gray-500 text-sm mb-5 leading-relaxed">
                                {{ Str::limit($listing->description, 60) }}
                            </p>

                            <!-- Add to Cart Button -->
                            <button type="button"
                                onclick="addToCart({{ $listing->id }})"
                                class="w-full bg-indigo-600 text-white py-2 rounded-lg font-medium hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all">
                                🛒 Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-customer-layout>
