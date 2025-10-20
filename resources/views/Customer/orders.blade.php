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
                    <div x-data="{ open: false }" class="bg-white shadow-md rounded-lg border hover:shadow-lg transition p-6">

                        <!-- Order Header -->
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
                                <p class="text-xl font-bold text-blue-600">
                                    {{ number_format($order->total_price, 0) }} PKR
                                </p>
                                <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full
                                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                                                            @elseif($order->status === 'completed') bg-green-100 text-green-700
                                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                                            @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Toggle Button -->
                        <div class="mt-4 text-right">
                            <button @click="open = !open"
                                class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                <span x-show="!open">See Details ↓</span>
                                <span x-show="open">Hide Details ↑</span>
                            </button>
                        </div>

                        <!-- Order Details -->
                        <div x-show="open" x-collapse class="mt-6 border-t pt-6 space-y-4 text-sm">
                            <div class="grid grid-cols-2 gap-4 text-gray-700">
                                <p><span class="font-medium">Subtotal:</span> {{ number_format($order->subtotal, 2) }} PKR</p>
                                <p><span class="font-medium">Delivery Fee:</span> {{ number_format($order->delivery_fee, 2) }}
                                    PKR</p>
                                <p><span class="font-medium">Total:</span> {{ number_format($order->total_price, 2) }} PKR</p>
                                <p><span class="font-medium">Payment:</span> {{ ucfirst($order->payment_status) }}</p>
                            </div>

                            <p class="text-gray-700"><span class="font-medium">Instructions:</span>
                                {{ $order->special_instructions ?? 'N/A' }}</p>

                            <!-- Items -->
                            @if($order->listing_ids)
                                @php
                                    $listingIds = json_decode($order->listing_ids, true);
                                    $listings = \App\Models\Listings::whereIn('id', $listingIds)->get();
                                @endphp
                                <div>
                                    <span class="font-medium text-gray-800">Items:</span>
                                    <ul class="mt-2 divide-y divide-gray-200 border rounded-lg">
                                        @foreach($listings as $listing)
                                            <li class="flex justify-between items-center p-3 hover:bg-gray-50">
                                                <div class="flex items-center space-x-3">
                                                    <img src="{{ $listing->image ? Storage::url($listing->image) : 'https://via.placeholder.com/40' }}"
                                                        class="w-10 h-10 rounded object-cover">
                                                    <span class="text-gray-700">{{ $listing->name }}</span>
                                                </div>
                                                <span
                                                    class="font-medium text-gray-800">{{ number_format($listing->discountedprice ?? $listing->price, 0) }}
                                                    PKR</span>
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