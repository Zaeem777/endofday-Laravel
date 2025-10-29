<x-customer-layout>
    <div class="max-w-7xl mx-auto px-6 py-12 bg-gray-50 min-h-screen"
        x-data="checkoutCart({{ $cartItems->map(fn($i) => [
            'id' => $i->id,
            'name' => $i->listing->name,
            'price' => $i->price,
            'discountedprice' => $i->listing->discountedprice,
            'quantity' => $i->quantity,
            'image' => $i->listing->image ? Storage::url($i->listing->image) : asset('storage/no-image.jpg'),
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
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-6">
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
                                <div class="flex justify-between items-center border border-gray-200 hover:border-blue-400 transition rounded-lg p-4">
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
                        <input type="text" name="address_line1" placeholder="Address Line 1" required
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400">
                        <input type="text" name="city" placeholder="City" required
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400">
                        <input type="text" name="state" placeholder="State"
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400">
                        <input type="text" name="postal_code" placeholder="Postal Code"
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400">
                        <input type="text" name="country" placeholder="Country" required
                            class="w-full border border-gray-200 rounded-lg p-3 focus:ring focus:ring-blue-100 focus:border-blue-400">
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

            <!-- 💳 Payment + Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-6 transition hover:shadow-xl">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-3">Payment Method</h2>

                <form id="paymentMethodForm" class="flex flex-col space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- 💵 Cash -->
                        <label
                            class="relative border-2 border-gray-200 rounded-xl p-5 flex items-center justify-center cursor-pointer hover:border-blue-500 transition group">
                            <input type="radio" name="payment_method" value="Cash"
                                class="absolute opacity-0 cursor-pointer">
                            <div class="flex flex-col items-center text-center space-y-2 group-hover:text-blue-600">
                                <span class="text-3xl">💵</span>
                                <span class="font-semibold text-gray-800">Cash on Delivery</span>
                                <span class="text-sm text-gray-500">Pay when your order arrives</span>
                            </div>
                        </label>

                        <!-- 💳 Card -->
                        <label
                            class="relative border-2 border-gray-200 rounded-xl p-5 flex items-center justify-center cursor-pointer hover:border-blue-500 transition group">
                            <input type="radio" name="payment_method" value="Card" checked
                                class="absolute opacity-0 cursor-pointer">
                            <div class="flex flex-col items-center text-center space-y-2 group-hover:text-blue-600">
                                <span class="text-3xl">💳</span>
                                <span class="font-semibold text-gray-800">Card Payment</span>
                                <span class="text-sm text-gray-500">Pay online securely</span>
                            </div>
                        </label>
                    </div>
                </form>

                <!-- 💳 Summary -->
                <div id="orderSummary" class="mt-10">
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
                            <form id="placeOrderForm" action="{{ route('Customer.placeorder') }}" method="POST" class="mt-8">
                                @csrf
                                <input type="hidden" name="address_id" value="{{ $addresses->first()->id ?? '' }}">
                                <input type="hidden" name="payment_method" id="paymentMethodInput" value="Card">

                                <button type="submit" id="placeOrderButton"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 rounded-xl hover:shadow-lg transition font-semibold text-lg">
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

            <!-- ✅ JavaScript -->
            <script>
            document.addEventListener('DOMContentLoaded', () => {
                const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
                const hiddenPaymentInput = document.getElementById('paymentMethodInput');

                paymentRadios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        if (radio.checked) {
                            hiddenPaymentInput.value = radio.value;
                            console.log('Payment method selected:', radio.value);
                        }
                    });
                });
            });
            </script>

        </div>
    </div>
<!-- ✅ Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");

    document.getElementById('placeOrderForm').addEventListener('submit', async function (e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
        const addressId = document.querySelector('input[name="address_id"]:checked')?.value;
        const specialInstructions = document.querySelector('textarea[name="special_instructions"]')?.value;

        // keep hidden input in sync for backend form submission
        document.getElementById('paymentMethodInput').value = paymentMethod;

        if (paymentMethod === 'Card') {
            e.preventDefault(); // prevent normal form submission

            try {
                const response = await fetch("{{ route('Customer.placeorder') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        address_id: addressId,
                        special_instructions: specialInstructions,
                        payment_method: "Card"
                    })
                });

                const data = await response.json();

                if (data.url) {
                    // ✅ Redirect to Stripe Checkout page
                    window.location.href = data.url;
                } else {
                    alert(data.error || "Unable to start Stripe payment.");
                }
            } catch (error) {
                console.error("Stripe checkout error:", error);
                alert("Something went wrong starting payment.");
            }
        } else {
            // ✅ For Cash, just let the form submit normally
            // (The backend will create the order with payment_status = 'unpaid')
        }
    });

    // ✅ Alpine.js cart logic (unchanged)
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
