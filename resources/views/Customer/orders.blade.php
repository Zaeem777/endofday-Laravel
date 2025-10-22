<x-customer-layout>
    <div class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">My Orders</h1>

        @if($orders->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <p class="text-gray-600">You have not placed any orders yet.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div x-data="{ open: false, showReview: false, rating: 0 }"
                        class="bg-white shadow-md rounded-lg border hover:shadow-lg transition p-6">

                        <!-- Header -->
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="mt-1 text-gray-700 font-medium">
                                    {{ $order->restaurant->name ?? 'Restaurant Deleted' }}
                                </p>
                                <p class="text-sm text-gray-500 mt-4">
                                    Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                                                                                                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                                                                                                                                    @elseif($order->status === 'inprocess') bg-yellow-100 text-yellow-700
                                                                                                                                    @elseif($order->status === 'ready') bg-yellow-100 text-yellow-700
                                                                                                                                    @elseif($order->status === 'completed') bg-green-100 text-green-700
                                                                                                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                                                                                                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="text-xl font-bold mt-3 text-blue-600">
                                    {{ number_format($order->total_price, 0) }} PKR
                                </p>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-4 flex justify-between items-center">
                            <div>
                                @if ($order->status === 'completed' && $order->review_status === 'Not Reviewed')
                                    <button @click="showReview = !showReview"
                                        class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                        Review
                                    </button>
                                @elseif ($order->status === 'completed' && $order->review_status === 'Reviewed')
                                    <span {{-- x-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm
                                        font-medium --}} {{--
                                        class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        --}}
                                        class ="x-4 py-2 px-3 rounded-lg bg-green-100 text-green-700
                                        transition text-sm">
                                        Reviewed ({{ $order->review }} ★)
                                    </span>
                                @endif
                            </div>

                            <button @click="open = !open"
                                class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                <span x-show="!open">See Details ↓</span>
                                <span x-show="open">Hide Details ↑</span>
                            </button>
                        </div>

                        <!-- Review Stars -->
                        <div x-show="showReview" class="mt-4 border-t pt-4">
                            <p class="text-gray-700 mb-2 font-medium">Rate your experience:</p>
                            <div class="flex space-x-2">
                                <template x-for="i in 5">
                                    <svg @click="
                                                                                            rating = i;
                                                                                            fetch('{{ route('Customer.orders.review') }}', {
                                                                                                method: 'POST',
                                                                                                headers: {
                                                                                                    'Content-Type': 'application/json',
                                                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                                                },
                                                                                                body: JSON.stringify({
                                                                                                    order_id: {{ $order->id }},
                                                                                                    rating: i
                                                                                                })
                                                                                            })
                                                                                            .then(res => res.json())
                                                                                            .then(data => {
                                                                                                if (data.success) {
                                                                                                    showReview = false;
                                                                                                    alert('⭐ Thanks for your review!');
                                                                                                    reviewed = true; // optional if you use Alpine to hide the review button
                                                                                                } else {
                                                                                                    alert(data.message || 'Error submitting review.');
                                                                                                }
                                                                                            })
                                                                                            .catch(err => console.error(err));
                                                                                        "
                                        :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                        class="w-8 h-8 cursor-pointer transition" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.975a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.385 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.176 0l-3.385 2.46c-.785.57-1.84-.197-1.54-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z" />
                                    </svg>
                                </template>
                            </div>
                        </div>


                        <!-- Order Details -->
                        <div x-show="open" x-collapse class="mt-6 border-t pt-6 space-y-4 text-sm">
                            <div class="grid grid-cols-2 gap-4 text-gray-700">
                                <p><span class="font-medium">Subtotal:</span> {{ number_format($order->subtotal) }} PKR</p>
                                <p><span class="font-medium">Delivery Fee:</span> {{ number_format($order->delivery_fee) }} PKR
                                </p>
                                <p><span class="font-medium">Total:</span> {{ number_format($order->total_price) }} PKR</p>
                                <p><span class="font-medium">Payment:</span> {{ ucfirst($order->payment_status) }}</p>
                            </div>
                            <p class="text-gray-700"><span class="font-medium">Instructions:</span>
                                {{ $order->special_instructions ?? 'N/A' }}</p>

                            <!-- Items -->
                            @if($order->items->count() > 0)
                                <div>
                                    <span class="font-medium text-gray-800">Items:</span>
                                    <ul class="mt-3 divide-y divide-gray-200 border rounded-lg">
                                        @foreach($order->items as $item)
                                            <li class="flex justify-between items-center p-3 hover:bg-gray-50 transition">
                                                <div class="flex items-center space-x-3">
                                                    <img src="{{ $item->listing && $item->listing->image ? Storage::url($item->listing->image) : 'https://via.placeholder.com/40' }}"
                                                        class="w-10 h-10 rounded object-cover border">
                                                    <div>
                                                        <p class="text-gray-800 font-medium">
                                                            {{ $item->listing->name ?? 'Item Deleted' }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">Quantity: {{ $item->quantity }}</p>
                                                    </div>
                                                </div>
                                                <span class="font-semibold text-gray-900">
                                                    {{ number_format($item->price * $item->quantity, 0) }} PKR
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-customer-layout>