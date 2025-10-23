<div class="relative" x-data="{ open: false }">
    <!-- Cart Button -->
    <button @click="open = !open" class="relative flex items-center text-gray-700 hover:text-blue-600 transition">
        <!-- Icon -->
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.2a1 1 0 001 1.3h12.6a1 1 0 001-1.3L17 13M7 13h10">
            </path>
        </svg>
        <!-- Badge -->
        <span data-cart-count
            class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full shadow">
            {{ $cartCount ?? 0 }}
        </span>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" @click.away="open = false" x-transition
        class="absolute right-0 mt-3 w-96 bg-white shadow-xl rounded-xl border border-gray-200 z-50 overflow-hidden">

        <!-- Header -->
        <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-green-50 border-b">
            <h3 class="text-lg font-semibold text-gray-700">🛒 Your Cart</h3>
        </div>

        <!-- ✅ Cart Items (wrapped) -->
        {{-- <div class="p-4 max-h-72 overflow-y-auto divide-y divide-gray-200" data-cart-items> --}}

            <div class="p-4 max-h-72 overflow-y-auto divide-y divide-gray-200" data-cart-items>
                @include('partials.cart-items', ['cartItems' => $cartItems ?? collect()])
            </div>

            {{--
        </div> --}}

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t flex justify-between items-center">
            <span id="cartTotal" class="font-semibold text-gray-800 text-lg">
                Total:
                {{ isset($cartItems) ? $cartItems->sum(fn($i) => $i->listing->discountedprice * $i->quantity) : 0 }} PKR
            </span>


            <span data-cart-count>

                <a href="{{ route('Customer.checkout') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Checkout →
                </a>

            </span>



        </div>
    </div>
</div>