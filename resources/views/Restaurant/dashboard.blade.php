<x-layout>
    <div class="min-h-screen ">
        <!-- Page Heading -->
        <div class="max-w-7xl mx-auto px-4 py-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    🍽️ Restaurant Dashboard
                    @if($averageReview > 0)
                        <span class="text-yellow-500 text-2xl">
                            ★
                        </span>
                        <span class="text-gray-700 text-lg font-semibold">
                            {{ number_format($averageReview, 1) }}/5
                        </span>
                    @else
                        <span class="text-gray-500 text-sm ml-2">(No reviews yet)</span>
                    @endif
                </h1>
                <p class="text-gray-600">Welcome back, here’s an overview of your restaurant.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <a href="{{ route('Restaurant.createlisting') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    ➕ Create Listing
                </a>
                <a href="{{ route('Restaurant.showlistings') }}"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700 transition">
                    📋 View All Listings
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-sm font-semibold text-gray-500">Total Listings</h2>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ $totalListings }}</p>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-sm font-semibold text-gray-500">Orders Today</h2>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ $recentOrders->count() }}</p>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-sm font-semibold text-gray-500">Revenue</h2>
                <p class="text-2xl font-bold text-green-600 mt-2">₨ {{ number_format($revenue, 0) }}</p>
            </div>

            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-sm font-semibold text-gray-500">Pending Deliveries</h2>
                <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $pendingOrders }}</p>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="max-w-7xl mx-auto px-4 mt-8">
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
                @if($recentOrders->count() > 0)
                    <div class="mt-4 overflow-x-auto">

                        <table class="min-w-full text-left text-sm text-gray-600">
                            <thead>
                                <tr class="border-b text-gray-800">
                                    <th class="px-4 py-2">Order ID</th>
                                    <th class="px-4 py-2">Customer</th>
                                    <th class="px-4 py-2">Items</th>
                                    <th class="px-4 py-2">Total</th>
                                    <th class="px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">
                                            <a href="{{ route('Restaurant.show.order', $order->id) }}"
                                                class="text-blue-600 hover:underline font-semibold">
                                                #{{ $order->id }}
                                            </a>
                                        </td>

                                        {{-- Customer name from user relation --}}
                                        <td class="px-4 py-2">
                                            {{ $order->user->name ?? 'Guest' }}
                                        </td>

                                        {{-- Total quantity from items relation --}}
                                        <td class="px-4 py-2">
                                            {{ $order->items->sum('quantity') }}
                                        </td>

                                        {{-- Total price --}}
                                        <td class="px-4 py-2">₨ {{ number_format($order->total_price, 0) }}</td>

                                        {{-- Status --}}
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
                        <p class="text-gray-500 text-lg font-medium">No orders have been placed in the last 24 hours.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Graphs Section -->
        <div class="max-w-7xl mx-auto px-4 mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Chart -->
            <div class="bg-white shadow rounded-xl p-6 relative">
                <h2 class="text-lg font-semibold text-gray-800">Sales Overview</h2>
                <canvas id="salesChart" class="mt-4"></canvas>
                <div id="salesChartEmpty"
                    class="absolute inset-0 flex items-center justify-center text-gray-400 text-lg font-medium hidden">
                    No sales data available
                </div>
            </div>

            <!-- Top Categories -->
            <div class="bg-white shadow rounded-xl p-6 relative">
                <h2 class="text-lg font-semibold text-gray-800">Top Categories</h2>
                <canvas id="categoryChart" class="mt-4"></canvas>
                <div id="categoryChartEmpty"
                    class="absolute inset-0 flex items-center justify-center text-gray-400 text-lg font-medium hidden">
                    No category data available
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const salesCtx = document.getElementById('salesChart');
        const categoryCtx = document.getElementById('categoryChart');

        let salesChart = new Chart(salesCtx, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'Sales (₨)', data: [], borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.2)', fill: true, tension: 0.4 }] },
            options: { responsive: true }
        });

        let categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: { labels: [], datasets: [{ label: 'Top Categories', data: [], backgroundColor: ['#f87171', '#34d399', '#60a5fa', '#fbbf24', '#a78bfa'] }] },
            options: { responsive: true }
        });

        async function fetchChartData() {
            try {
                const response = await fetch("{{ route('Restaurant.chartdata') }}");
                const data = await response.json();

                // === Sales Chart ===
                const hasSalesData = data.sales.data.some(value => value > 0);
                const salesEmptyDiv = document.getElementById('salesChartEmpty');

                if (hasSalesData) {
                    salesChart.data.labels = data.sales.labels;
                    salesChart.data.datasets[0].data = data.sales.data;
                    salesChart.update();
                    salesEmptyDiv.classList.add('hidden');
                } else {
                    // Clear chart and show message
                    salesChart.data.labels = [];
                    salesChart.data.datasets[0].data = [];
                    salesChart.update();
                    salesEmptyDiv.classList.remove('hidden');
                }

                // === Category Chart ===
                const hasCategoryData = data.categories.data.length > 0;
                const categoryEmptyDiv = document.getElementById('categoryChartEmpty');

                if (hasCategoryData) {
                    categoryChart.data.labels = data.categories.labels;
                    categoryChart.data.datasets[0].data = data.categories.data;
                    categoryChart.update();
                    categoryEmptyDiv.classList.add('hidden');
                } else {
                    categoryChart.data.labels = [];
                    categoryChart.data.datasets[0].data = [];
                    categoryChart.update();
                    categoryEmptyDiv.classList.remove('hidden');
                }

            } catch (error) {
                console.error("Error fetching chart data:", error);
            }
        }


        // Initial load + auto refresh every 10 seconds
        fetchChartData();
        setInterval(fetchChartData, 10000);
    </script>

</x-layout>