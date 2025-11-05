<x-admin-layout>
    <!-- 🌤 Main Content -->
    <main class="flex-1 p-8 space-y-6">

        <!-- Top Bar -->
        <header class="flex items-center justify-between bg-white p-4 rounded-xl shadow-sm">
            <h2 class="text-xl font-semibold">Dashboard Overview</h2>
        </header>

        <!-- Stats Cards -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm">Total Restaurants</h3>
                <p class="text-3xl font-bold mt-2 text-primary">{{ $restaurant_owners }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm">Total Customers</h3>
                <p class="text-3xl font-bold mt-2 text-primary">{{ $customers }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm">Total Orders</h3>
                <p class="text-3xl font-bold mt-2 text-primary">{{ $orders }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm">Revenue</h3>
                <p class="text-3xl font-bold mt-2 text-green-600">{{ $revenues }}</p>
            </div>
        </section>

        <!-- Recent Orders Table -->
        <section class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>

            @if ($recentOrders->count() > 0)
                <!-- ✅ Table container with its own vertical scrollbar -->
                <div class="mt-4 overflow-y-auto max-h-96 border rounded-lg">
                    <table class="w-full border-collapse min-w-max">
                        <thead class="sticky top-0 bg-gray-50 z-10">
                            <tr class="text-left border-b text-gray-600">
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3 px-4">Customer</th>
                                <th class="py-3 px-4">Restaurant</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @foreach ($recentOrders as $order)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('Restaurant.show.order', $order->id) }}"
                                            class="text-blue-600 hover:underline font-semibold">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2">{{ $order->user->name ?? 'Guest' }}</td>
                                    <td class="px-4 py-2">{{ $order->items->sum('quantity') }}</td>
                                    <td class="px-4 py-2">₨ {{ number_format($order->total_price, 0) }}</td>
                                    <td class="px-4 py-2">
                                        @php
                                            $statusColors = [
                                                'completed' => 'green',
                                                'pending' => 'yellow',
                                                'in progress' => 'blue'
                                            ];
                                            $color = $statusColors[strtolower($order->status)] ?? 'gray';
                                        @endphp
                                        <span class="px-2 py-1 text-xs bg-{{ $color }}-100 text-{{ $color }}-700 rounded">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-6 flex flex-col items-center justify-center text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-gray-400 mb-3" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m2 0a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">
                        No orders have been placed in the last 24 hours.
                    </p>
                </div>
            @endif
        </section>

        <!-- Analytics Placeholder -->
        <section class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Analytics Overview</h3>
            <div class="h-48 flex items-center justify-center text-gray-400">
                📊 Chart will go here later
            </div>
        </section>

    </main>
</x-admin-layout>