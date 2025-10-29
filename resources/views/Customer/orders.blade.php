<x-customer-layout>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-6xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                My Orders
            </h1>
        </div>

        <!-- 🔽 Filter Dropdown -->
        <div class="flex items-center justify-end mb-8">
            <form method="GET" action="{{ route('Customer.orders.show') }}" class="flex items-center gap-3">
                <label for="status" class="font-medium text-gray-700">Filter by Status:</label>
                <select id="status" name="status"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                    onchange="this.form.submit()">
                    <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>All Orders</option>
                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="inprocess" {{ ($status ?? '') === 'inprocess' ? 'selected' : '' }}>In Process</option>
                    <option value="ready" {{ ($status ?? '') === 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ ($status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>

        @if($status && $status !== 'all')
            <p class="text-sm text-gray-600 mb-6">
                Showing <span class="font-semibold text-blue-600">{{ ucfirst($status) }}</span> orders
            </p>
        @endif

        <!-- Orders Section -->
        @if ($orders->isEmpty())
            <div class="bg-white p-10 rounded-2xl shadow-sm border text-center">
                <p class="text-gray-500 text-lg">You haven’t placed any orders yet.</p>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($orders as $order)
                    <div x-data="{ open: false, showReview: false, rating: 0 }"
                        class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">

                        <!-- Order Header Image -->
                        <div class="relative h-40 w-full overflow-hidden">
                            @php
                                $firstItem = $order->items->first();
                                $restaurantImage = $firstItem && $firstItem->listing && $firstItem->listing->owner && $firstItem->listing->owner->image
                                    ? asset('storage/' . $firstItem->listing->owner->image)
                                    : Storage::url('no-image.jpg');
                            @endphp
                            <img src="{{ $restaurantImage }}" alt="Restaurant Image"
                                class="w-full h-40 object-cover transition-transform duration-300 hover:scale-105">

                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($order->status === 'inprocess') bg-blue-100 text-blue-700
                                    @elseif($order->status === 'ready') bg-indigo-100 text-indigo-700
                                    @elseif($order->status === 'completed') bg-green-100 text-green-700
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            @php $firstItem = $order->items->first(); @endphp

                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        {{ $firstItem && $firstItem->listing && $firstItem->listing->owner
                                            ? $firstItem->listing->owner->name
                                            : 'Restaurant Deleted' }}
                                    </h2>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>
                                <p class="text-xl font-bold text-blue-600">
                                    ₨ {{ number_format($order->total_price, 0) }}
                                </p>
                            </div>

                            <!-- Review Button -->
                            <div class="mt-4 review-section">
                                @if ($order->status === 'completed' && $order->review_status === 'Not Reviewed')
                                    <button @click="showReview = !showReview"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium review-btn">
                                        Leave a Review
                                    </button>
                                @elseif ($order->status === 'completed' && $order->review_status === 'Reviewed')
                                    <span class="px-4 py-2 rounded-lg bg-green-100 text-green-700 font-semibold text-sm">
                                        Reviewed ({{ $order->review }} ★)
                                    </span>
                                @endif
                            </div>

                            <!-- Review Section -->
                            <div x-show="showReview" x-transition class="mt-4 border-t pt-4">
                                <p class="text-gray-700 mb-2 font-medium">Rate your experience:</p>
                                <div class="flex space-x-2">
                                    <template x-for="i in 5">
                                        <svg 
                                            @click="
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
                                                .then(async (res) => {
                                                    const data = await res.json().catch(() => null);
                                                    if (data?.success) {
                                                        showReview = false;
                                                        Swal.fire({
                                                            title: 'Thank You!',
                                                            text: '⭐ Thanks for your review!',
                                                            icon: 'success',
                                                            confirmButtonColor: '#3085d6'
                                                        }).then(()=>window.location.reload());
                                                    }
                                                })
                                                .catch(() => Swal.fire({title: 'Error', text: 'Error submitting review.', icon: 'error'}));
                                            "
                                            :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                            class="w-8 h-8 cursor-pointer transition-transform transform hover:scale-110"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.975a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.385 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.176 0l-3.385 2.46c-.785.57-1.84-.197-1.54-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z" />
                                        </svg>
                                    </template>
                                </div>
                            </div>

                            <!-- Details + Cancel Buttons -->
                            <div class="mt-6 flex justify-between items-center border-t pt-4">
                                <button @click="open = !open"
                                    class="text-sm font-medium text-blue-700 hover:text-blue-900 transition">
                                    <span x-show="!open">See Details ↓</span>
                                    <span x-show="open">Hide Details ↑</span>
                                </button>

                                @if ($order->cancel && $order->status !== 'cancelled' && $order->status !== 'completed')
                                    <button @click.prevent="
                                        Swal.fire({
                                            title: 'Cancel Order?',
                                            text: 'Are you sure you want to cancel this order?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Yes, cancel it'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                fetch('{{ route('Customer.orders.cancel', $order->id) }}', {
                                                    method: 'PATCH',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'Accept': 'application/json', 
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        Swal.fire({
                                                            title: 'Cancelled!',
                                                            text: data.message,
                                                            icon: 'success'
                                                        }).then(()=>window.location.reload());
                                                    } else {
                                                        Swal.fire({
                                                            title: 'Error',
                                                            text: data.message || 'Error cancelling order.',
                                                            icon: 'error'
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                    "
                                        class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                                        Cancel Order
                                    </button>
                                @endif
                            </div>

                            <!-- Order Details -->
                            <div x-show="open" x-collapse x-transition class="mt-4 text-sm text-gray-700 space-y-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <p><span class="font-medium">Subtotal:</span> ₨ {{ number_format($order->subtotal) }}</p>
                                    <p><span class="font-medium">Delivery Fee:</span> ₨ {{ number_format($order->delivery_fee) }}</p>
                                    <p><span class="font-medium">Total:</span> ₨ {{ number_format($order->total_price) }}</p>
                                    <p><span class="font-medium">Payment:</span> {{ ucfirst($order->payment_status) }}</p>
                                </div>

                                <p><span class="font-medium">Instructions:</span> {{ $order->special_instructions ?? 'N/A' }}</p>

                                @if($order->items->count() > 0)
                                    <div>
                                        <p class="font-medium text-gray-800 mt-4 mb-2">Items</p>
                                        <ul class="divide-y divide-gray-100 border rounded-lg overflow-hidden bg-gray-50">
                                            @foreach($order->items as $item)
                                                <li class="flex justify-between items-center p-3 hover:bg-gray-100 transition">
                                                    <div class="flex items-center space-x-3">
                                                        <img src="{{ $item->listing && $item->listing->image
                                                            ? Storage::url($item->listing->image)
                                                            : Storage::url('no-image.jpg') }}"
                                                            class="w-10 h-10 rounded-lg object-cover border">
                                                        <div>
                                                            <p class="font-medium">{{ $item->listing->name ?? 'Item Deleted' }}</p>
                                                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="font-semibold text-gray-900">
                                                        ₨ {{ number_format($item->price * $item->quantity, 0) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-customer-layout>
