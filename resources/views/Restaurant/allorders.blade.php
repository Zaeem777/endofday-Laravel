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
                            <th class="px-6 py-3">Rating</th>
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

                                <!-- ⭐ Rating Column -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    @if(!empty($order->review))
                                        <div class="flex items-center space-x-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $order->review ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.975a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.385 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.176 0l-3.385 2.46c-.785.57-1.84-.197-1.54-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Not rated</span>
                                    @endif
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
                                <td colspan="8" class="px-6 py-6 text-center text-gray-500">
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