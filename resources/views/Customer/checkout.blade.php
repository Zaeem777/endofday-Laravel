<x-customer-layout>
    <div class="max-w-7xl mx-auto px-4 py-10" x-data="checkoutCart({{ $cartItems->map(fn($i) => [
    'id' => $i->id,
    'name' => $i->listing->name,
    'price' => $i->price,
    'quantity' => $i->quantity,
    'image' => $i->listing->image ? Storage::url($i->listing->image) : 'https://via.placeholder.com/80'
]) }})">

        <h1 class="text-3xl font-bold text-gray-800 mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Cart Items -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Your Items</h2>

                <!-- Items -->
                <template x-if="items.length > 0">
                    <div class="space-y-4">
                        <template x-for="item in items" :key="item.id">
                            <div class="flex items-center justify-between border-b pb-4">

                                <!-- Image + Name -->
                                <div class="flex items-center space-x-4">
                                    <img :src="item.image" :alt="item.name" class="w-16 h-16 object-cover rounded">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900" x-text="item.name"></h3>
                                        <p class="text-gray-500 text-sm"><span x-text="item.price"></span> PKR each</p>
                                    </div>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-3">
                                    <button @click="updateQty(item.id, -1)"
                                        class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition">
                                        <span class="text-gray-700 font-bold">−</span>
                                    </button>

                                    <span class="px-3 py-1 bg-gray-100 rounded text-gray-800 font-medium"
                                        x-text="item.quantity"></span>

                                    <button @click="updateQty(item.id, 1)"
                                        class="w-7 h-7 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition">
                                        <span class="text-gray-700 font-bold">+</span>
                                    </button>
                                </div>

                                <!-- Subtotal + Remove -->
                                <div class="text-right">
                                    <p class="text-blue-600 font-bold" x-text="(item.price * item.quantity) + ' PKR'">
                                    </p>
                                    <button @click="removeItem(item.id)"
                                        class="text-red-500 hover:text-red-700 text-sm mt-2">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Empty Cart -->
                <template x-if="items.length === 0">
                    <p class="text-gray-500 text-center py-6">Your cart is empty 🛒</p>
                </template>
            </div>

            <!-- Order Summary -->
            <div id="orderSummary" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Order Summary</h2>

                <template x-if="items.length > 0">
                    <div class="space-y-2 text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span x-text="subtotal() + ' PKR'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>Delivery Fee</span>
                            <span>200 PKR</span>
                        </div>

                        <div class="flex justify-between font-bold text-gray-800 border-t pt-2">
                            <span>Total</span>
                            <span x-text="(subtotal() + 200) + ' PKR'"></span>
                        </div>

                        <form action="{{ route('Customer.placeorder') }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                                :disabled="items.length === 0"
                                :class="{'opacity-50 cursor-not-allowed': items.length === 0}">
                                Place Order →
                            </button>
                        </form>
                    </div>
                </template>

                <template x-if="items.length === 0">
                    <p class="text-gray-500 text-center py-6">No items in cart 🛒</p>
                </template>
            </div>

        </div>
    </div>

    <script>
        function checkoutCart(initialItems) {
            return {
                items: initialItems,

                subtotal() {
                    return this.items.reduce((sum, i) => sum + (i.price * i.quantity), 0);
                },

                async updateQty(id, change) {
                    let item = this.items.find(i => i.id === id);
                    if (!item) return;

                    let newQty = item.quantity + change;
                    if (newQty < 1) return this.removeItem(id);

                    let url = "{{ route('Customer.checkout.update', ['cart' => ':id']) }}".replace(':id', id);
                    let response = await fetch(url, {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ quantity: newQty })
                    });

                    let data = await response.json();
                    if (data.success) {
                        item.quantity = data.quantity;
                    }
                },

                async removeItem(id) {
                    let url = "{{ route('Customer.checkout.update', ['cart' => ':id']) }}".replace(':id', id);
                    let response = await fetch(url, {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ quantity: 0 })
                    });

                    let data = await response.json();
                    if (data.success && data.removed) {
                        this.items = this.items.filter(i => i.id !== id);
                    }
                }
            };
        }
    </script>
</x-customer-layout>