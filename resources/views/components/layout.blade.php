<!DOCTYPE html>
<html lang="en" x-data="dashboardLayout()" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="//unpkg.com/alpinejs" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{{ $title ?? 'Restaurant Dashboard' }}</title>

    <script>
        function dashboardLayout() {
            return {
                sidebarOpen: false,
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                }
            };
        }
    </script>
</head>

<body class="bg-purple-100 min-h-screen flex flex-col">

    <!-- ✅ Navbar -->
    <nav class="bg-white shadow-md w-full z-20 relative">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <!-- Left Side -->
                <div class="flex items-center space-x-3">
                    <!-- Sidebar Toggle -->
                    <button @click="toggleSidebar" class="text-gray-600 hover:text-purple-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <a href="/Restaurant/dashboard" class="font-bold text-purple-700 text-lg">Dashboard</a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- ✅ Sidebar + Content -->
    <div class="flex flex-1 relative overflow-hidden">

        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 w-64 bg-white shadow-md z-30 flex flex-col">

            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-purple-700">Menu</h2>
                <button @click="toggleSidebar" class="text-gray-500 hover:text-red-600">✖</button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="/Restaurant/createlisting" class="block px-4 py-2 rounded hover:bg-purple-100">➕ Add
                    Listing</a>
                <a href="/Restaurant/showlistings" class="block px-4 py-2 rounded hover:bg-purple-100">📋 Show All
                    Listings</a>
                <a href="/Restaurant/allorders" class="block px-4 py-2 rounded hover:bg-purple-100">🛒 All Orders</a>
                <a href="/Restaurant/profile" class="block px-4 py-2 rounded hover:bg-purple-100">👤 Profile</a>
                <a href="/contact" class="block px-4 py-2 rounded hover:bg-purple-100">📞 Contact Us</a>
            </nav>
        </aside>

        <!-- ✅ Main Content -->
        <main class="flex-1 transition-all duration-300 ease-in-out p-6" :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">
            {{ $slot }}
        </main>
    </div>

    <x-flash-message />
</body>

</html>