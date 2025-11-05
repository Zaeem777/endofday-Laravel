<!DOCTYPE html>
<html lang="en" x-data="dashboardLayout()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1e293b',
                        accent: '#f97316',
                    }
                }
            }
        }

        // Alpine component for sidebar toggle + persistence
        function dashboardLayout() {
            return {
                sidebarOpen: localStorage.getItem('sidebarOpen') === 'true',
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    localStorage.setItem('sidebarOpen', this.sidebarOpen);
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow-md w-full fixed top-0 z-20">
        <div class=" mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <!-- Left: Sidebar toggle -->
                <button @click="toggleSidebar" class="text-gray-600 hover:text-primary focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Title -->
                <h1 class="font-bold text-lg text-primary">Super Admin Dashboard</h1>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Sidebar + Main -->
    <div class="flex flex-1 pt-16 overflow-hidden">

        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 w-64 bg-secondary text-gray-200 flex flex-col z-30 lg:translate-x-0">

            <!-- Header -->
            <div class="flex items-center justify-between px-4 h-16 border-b border-gray-700">
                <h1 class="text-xl font-bold text-white">🍞 SuperAdmin</h1>
                <button @click="toggleSidebar" class="text-gray-400 hover:text-red-500">
                    ✖
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-primary text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 4h6m-6 4h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10m-9 4h8M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0013.586 3H10.414a1 1 0 00-.707.293L8.293 4.707A1 1 0 017.586 5H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Restaurants</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 10-4 0v6" />
                    </svg>
                    <span>Customers</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2a4 4 0 00-4-4H3m18 0h-2a4 4 0 00-4 4v2m1-6V5a2 2 0 00-2-2H9a2 2 0 00-2 2v6m8 8h-4" />
                    </svg>
                    <span>Orders</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 11V3H5a2 2 0 00-2 2v6h8zm0 0v10h10a2 2 0 002-2V11H11z" />
                    </svg>
                    <span>Analytics</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-gray-700 hover:bg-red-600 py-2 rounded-lg text-white">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">
            {{ $slot }}
        </main>
    </div>
</body>

</html>