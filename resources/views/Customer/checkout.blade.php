<x-customer-layout>
    <div class="max-w-7xl mx-auto px-6 py-12 bg-gray-50 min-h-screen"
        x-data="checkoutCart({{ $cartItems->map(fn($i) => [
            'id' => $i->id,
            'name' => $i->listing->name,
            'price' => $i->price,
            'discountedprice' => $i->listing->discountedprice,
            'quantity' => $i->quantity,
            'image' => $i->listing->image ? Storage::url($i->listing->image) : 'https://via.placeholder.com/80'
        ]) }})">

        <h1 class="text-4xl font-bold text-gray-800 mb-10 text-center">🛒 Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- 🧁 Cart Items -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 transition hover:shadow-xl">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Your Items</h2>

                    <template x-if="items.length > 0">
                        <div class="space-y-6">
                            <template x-for="item in items" :key="item.id">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-6">
                                    <!-- Image + Name -->
                                    <div class="flex items-center space-x-5 mb-4 sm:mb-0">
                                        <img :src="item.image" :alt="item.name"
                                            class="w-20 h-20 object-cover rounded-xl shadow-sm border border-gray-200">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900" x-text="item.name"></h3>
                                            <p class="text-sm text-gray-500">
                                                <span x-text="item.discountedprice"></span> PKR each
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Quantity + Price -->
                                    <div class="flex items-center space-x-5">
                                        <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                                            <button @click="updateQty(item.id, -1)"
                                                class="text-gray-600 hover:text-gray-900 font-bold text-lg px-2">−</button>
                                            <span class="text-gray-800 font-medium px-2" x-text="item.quantity"></span>
                                            <button @click="updateQty(item.id, 1)"
                                                class="text-gray-600 hover:text-gray-900 font-bold text-lg px-2">+</button>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-lg font-bold text-blue-600"
                                                x-text="(item.discountedprice * item.quantity) + ' PKR'"></p>
                                            <button @click="removeItem(item.id)"
                                                class="text-red-500 hover:text-red-700 text-sm mt-1 font-medium">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="items.length === 0">
                        <p class="text-gray-500 text-center py-6">Your cart is empty 🛍️</p>
                    </template>
                </div>

                <!-- 📍 Address Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6 transition hover:shadow-xl">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Delivery Address</h2>

                    @if($addresses->count() > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($addresses as $address)
                                <div
                                    class="flex justify-between items-center border border-gray-200 hover:border-blue-400 transition rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" name="address_id" value="{{ $address->id }}"
                                            {{ $loop->first ? 'checked' : '' }}
                                            class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-gray-700 font-medium">
                                            {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->country }}
                                        </span>
                                    </div>
                                    <form action="{{ route('Customer.address.destroy', $address->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">🗑</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mb-4">No saved addresses yet. Please add one below.</p>
                    @endif

                    <button type="button"
                        onclick="document.getElementById('addAddressForm').classList.toggle('hidden')"
                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm transition">
                        + Add New Address
                    </button>

                    <form id="addAddressForm" action="{{ route('Customer.address.store') }}" method="POST"
                        class="mt-4 space-y-3 hidden">
                        @csrf
                        <input type="text" name="address_line1" placeholder="Address Line 1"
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400"
                            required>
                        <input type="text" name="city" placeholder="City"
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400"
                            required>
                        <input type="text" name="state" placeholder="State"
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400">
                        <input type="text" name="postal_code" placeholder="Postal Code"
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400">
                        <input type="text" name="country" placeholder="Country"
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400"
                            required>
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-3 rounded-lg hover:shadow-lg transition font-semibold">
                            Save Address
                        </button>
                    </form>
                </div>

                <!-- ✏️ Special Instructions -->
                <div class="bg-white rounded-2xl shadow-lg p-6 transition hover:shadow-xl">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-3">Special Instructions</h2>
                    <textarea name="special_instructions" form="placeOrderForm"
                        placeholder="Write any notes for the Restaurant Owner..."
                        class="w-full border border-gray-200 rounded-xl p-4 text-gray-700 focus:ring focus:ring-blue-100 focus:border-blue-400"
                        rows="3"></textarea>
                </div>
            </div>

            <!-- 💳 Order Summary -->
            <div id="orderSummary" class="bg-white rounded-2xl shadow-xl p-6 h-fit sticky top-24">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Order Summary</h2>

                <template x-if="items.length > 0">
                    <div class="space-y-4 text-gray-700">
                        <div class="flex justify-between text-base">
                            <span>Subtotal</span>
                            <span x-text="subtotal() + ' PKR'"></span>
                        </div>

                        <div class="flex justify-between text-base">
                            <span>Delivery Fee</span>
                            <span>200 PKR</span>
                        </div>

                        <div class="flex justify-between font-bold text-lg text-gray-900 border-t pt-3">
                            <span>Total</span>
                            <span x-text="(subtotal() + 200) + ' PKR'"></span>
                        </div>

                        <!-- 🧾 Place Order -->
                        <form id="placeOrderForm" action="{{ route('Customer.placeorder') }}" method="POST"
                            class="mt-8">
                            @csrf
                            <input type="hidden" name="address_id" value="{{ $addresses->first()->id ?? '' }}">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 rounded-xl hover:shadow-lg hover:from-blue-700 hover:to-indigo-800 transition font-semibold text-lg"
                                :disabled="items.length === 0"
                                :class="{'opacity-50 cursor-not-allowed': items.length === 0}">
                                Place Order →
                            </button>
                        </form>
                    </div>
                </template>

                <template x-if="items.length === 0">
                    <p class="text-gray-500 text-center py-10">No items in cart 🛒</p>
                </template>
            </div>
        </div>
    </div>

    <script>
        function checkoutCart(initialItems) {
            return {
                items: initialItems,
                subtotal() {
                    return this.items.reduce((sum, i) => sum + (i.discountedprice * i.quantity), 0);
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
