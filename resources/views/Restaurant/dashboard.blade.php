<x-layout>
<div class="min-h-screen bg-gray-100">
    <!-- Page Heading -->
  <div class="max-w-7xl mx-auto px-4 py-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🍽️ Restaurant Dashboard</h1>
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
        <!-- Stats Cards -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-500">Total Listings</h2>
            <p class="text-2xl font-bold text-gray-800 mt-2">24</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-500">Orders Today</h2>
            <p class="text-2xl font-bold text-gray-800 mt-2">18</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-500">Revenue</h2>
            <p class="text-2xl font-bold text-green-600 mt-2">₨ 12,300</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-500">Pending Deliveries</h2>
            <p class="text-2xl font-bold text-yellow-600 mt-2">5</p>
        </div>
    </div>

    <!-- Graphs Section -->
    <div class="max-w-7xl mx-auto px-4 mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-800">Sales Overview</h2>
            <canvas id="salesChart" class="mt-4"></canvas>
        </div>

        <!-- Top Categories -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-800">Top Categories</h2>
            <canvas id="categoryChart" class="mt-4"></canvas>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="max-w-7xl mx-auto px-4 mt-8">
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
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
                        <tr class="border-b">
                            <td class="px-4 py-2">#1001</td>
                            <td class="px-4 py-2">Ali Khan</td>
                            <td class="px-4 py-2">3</td>
                            <td class="px-4 py-2">₨ 1,200</td>
                            <td class="px-4 py-2"><span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Completed</span></td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-4 py-2">#1002</td>
                            <td class="px-4 py-2">Sara Malik</td>
                            <td class="px-4 py-2">2</td>
                            <td class="px-4 py-2">₨ 850</td>
                            <td class="px-4 py-2"><span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Pending</span></td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-4 py-2">#1003</td>
                            <td class="px-4 py-2">Ahmed Raza</td>
                            <td class="px-4 py-2">5</td>
                            <td class="px-4 py-2">₨ 2,500</td>
                            <td class="px-4 py-2"><span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">In Progress</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart (Line)
    const salesCtx = document.getElementById('salesChart');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Sales (₨)',
                data: [1200, 1900, 3000, 2500, 2200, 3200, 2800],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                tension: 0.4,
                fill: true
            }]
        }
    });

    // Category Chart (Doughnut)
    const categoryCtx = document.getElementById('categoryChart');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pizza', 'Burgers', 'Drinks', 'Desserts'],
            datasets: [{
                label: 'Top Categories',
                data: [12, 9, 5, 7],
                backgroundColor: ['#f87171','#34d399','#60a5fa','#fbbf24']
            }]
        }
    });
</script>
</x-layout>
