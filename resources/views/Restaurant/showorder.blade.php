<x-layout>
    <div class="min-h-screen py-10">
        <div class="max-w-5xl mx-auto px-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">🧾 Order Details</h1>
                </div>
                <a href="{{ route('Restaurant.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    ← Back to Dashboard
                </a>
            </div>

            <!-- Order Summary Card -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden mb-10">
                <div
                    class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Order #{{ $order->id }}</h2>
                        <p class="text-gray-500 text-sm mt-1">Placed on
                            {{ $order->created_at->format('F j, Y • g:i A') }}
                        </p>
                    </div>
                    @php
                        $statusColors = [
                            'completed' => 'green',
                            'pending' => 'yellow',
                            'in process' => 'purple',
                            'ready' => 'blue',
                            'cancelled' => 'red',

                        ];
                        $color = $statusColors[strtolower($order->status)] ?? 'gray';
                    @endphp
                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST"
                        class="flex items-center space-x-3 mt-3 sm:mt-0">
                        @csrf
                        @method('PATCH')

                        <select name="status"
                            class="px-3 py-1 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="inprocess" {{ $order->status === 'inprocess' ? 'selected' : '' }}>In
                                Process</option>
                            <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancel
                            </option>

                        </select>

                        <button type="submit"
                            class="px-4 py-1 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                            Update
                        </button>
                    </form>

                </div>

                <!-- Customer Info -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Customer Info</h3>
                        <div class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-4">
                            {{-- <h3 class="text-sm font-semibold text-gray-500 uppercase">Customer Info</h3> --}}
                            <p class="text-gray-800 font-medium mt-2">{{ $order->user->name ?? 'Guest' }}</p>
                            <p class="text-gray-600 text-sm">{{ $order->user->email ?? 'N/A' }}</p>
                            <p class="text-gray-600 text-sm mt-3">
                                📞 <span class="font-medium">{{ $order->user->phone ?? 'No phone provided' }}</span>
                            </p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Delivery Details</h3>

                        @if ($order->address)
                            <div class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-gray-800 font-medium">
                                    {{ $order->address->address_line1 }}
                                </p>

                                <p class="text-gray-600 text-sm mt-1">
                                    {{ $order->address->city }}
                                    @if ($order->address->state)
                                        , {{ $order->address->state }}
                                    @endif
                                    @if ($order->address->postal_code)
                                        – {{ $order->address->postal_code }}
                                    @endif
                                </p>
                            </div>
                        @else
                            <p class="text-gray-500 mt-2 italic">No address provided for this order.</p>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Ordered Items -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">🛍️ Ordered Items</h3>
                    <span class="text-sm text-gray-500">Total Items: {{ $order->items->count() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Item</th>
                                <th class="px-6 py-3 text-left font-semibold">Category</th>
                                <th class="px-6 py-3 text-center font-semibold">Quantity</th>
                                <th class="px-6 py-3 text-right font-semibold">Price</th>
                                <th class="px-6 py-3 text-right font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->listing->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $item->listing->category ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right">₨ {{ number_format($item->listing->price, 0) }}</td>
                                    <td class="px-6 py-4 text-right font-semibold">
                                        ₨ {{ number_format($item->quantity * $item->listing->price, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right font-semibold text-gray-700">Total:</td>
                                <td class="px-6 py-4 text-right font-bold text-green-600">
                                    ₨ {{ number_format($order->total_price, 0) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->

        </div>
    </div>
</x-layout>