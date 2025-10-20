@if(isset($cartItems) && $cartItems->count())
    @foreach($cartItems as $item)
        @php
            $abc = $item->listing->discountedprice
        @endphp

        <div class="flex items-center justify-between py-3 border-b">
            <div>
                <p class="font-medium text-gray-800">{{ $item->listing->name }}</p>
                <p class="text-sm text-gray-500">{{ $item->listing->discountedprice }} PKR each</p>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Decrement -->
                <button id="dec-btn-{{ $item->id }}" type="button" @click.stop="window.updateCart({{ $item->id }}, -1)"
                    class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition">
                    <span class="text-gray-700 font-bold">−</span>
                </button>

                <!-- Quantity -->
                <span id="qty-{{ $item->id }}" class="px-3 py-1 bg-gray-100 rounded text-gray-800 font-medium">
                    {{ $item->quantity }}
                </span>

                <!-- Increment -->
                <button id="inc-btn-{{ $item->id }}" type="button" @click.stop="window.updateCart({{ $item->id }}, 1)"
                    class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition">
                    <span class="text-gray-700 font-bold">+</span>
                </button>

                <!-- Item Total -->
                <p id="itemTotal-{{ $item->id }}" class="font-semibold text-gray-700 w-16 text-right">
                    {{ $abc * $item->quantity }} PKR
                </p>
                <button type="button" @click="window.deleteCartItem({{ $item->id }})"
                    class="text-red-500 hover:text-red-700 ml-2">
                    ✖
                </button>
            </div>
        </div>
    @endforeach
@else
    <p class="text-gray-500 text-center py-6">Your cart is empty 🛒</p>
@endif