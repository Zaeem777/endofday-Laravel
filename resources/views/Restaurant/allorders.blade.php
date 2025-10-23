<x-layout>

    <!-- All Orders -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-blue-100">
                <h2 class="text-xl font-bold text-gray-800">All Orders</h2>
                <span class="text-sm text-gray-500">{{ $orders->count() }} total orders</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-3">Order ID</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Items</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <!-- Order ID -->
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">
                                    <a href="{{ route('Restaurant.show.order', $order->id) }}"
                                        class="hover:text-blue-600 transition">
                                        #{{ $order->id }}
                                    </a>
                                </td>

                                <!-- Customer -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $order->user->name ?? 'Guest User' }}
                                </td>

                                <!-- Total items -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $order->items->sum('quantity') }} items
                                </td>

                                <!-- Total price -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">
                                    ₨ {{ number_format($order->total_price, 0) }}
                                </td>

                                <!-- Status with color badge -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'inprocess' => 'bg-blue-100 text-blue-800',
                                            'ready' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $color = $statusColors[strtolower($order->status)] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('Restaurant.show.order', $order->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 transition">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layout>