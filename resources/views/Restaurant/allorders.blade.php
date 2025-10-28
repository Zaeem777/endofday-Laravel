<x-layout>

    <!-- All Orders -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">

            <!-- Header + Filter -->
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-purple-100 gap-3">

                <h2 class="text-xl font-bold text-gray-800">All Orders</h2>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <form method="GET" action="{{ route('Restaurant.allorders') }}" class="flex items-center space-x-2">
                        <label for="status" class="text-sm text-gray-600 font-medium">Filter by:</label>
                        <select name="status" id="status" onchange="this.form.submit()"
                            class="border-gray-300 rounded-md text-sm text-gray-700 focus:ring-purple-500 focus:border-purple-500">
                            <option value="all" {{ ($status ?? '') === 'all' || !$status ? 'selected' : '' }}>All</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ ($status ?? '') === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <span class="text-sm text-gray-500">{{ $orders->count() }} total orders</span>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-3">Order ID</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Items</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Order Date</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <!-- Order ID -->
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">
                                    <a href="{{ route('Restaurant.show.order', $order->id) }}"
                                        class="hover:text-purple-600 transition">
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
                                            'inprocess' => 'bg-purple-100 text-purple-800',
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

                                <!-- Order Date -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('Restaurant.show.order', $order->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-purple-600 rounded-lg shadow hover:bg-purple-700 transition">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-gray-500">
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