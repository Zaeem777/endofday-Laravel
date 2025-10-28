<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="//unpkg.com/alpinejs" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <title>{{ $title ?? 'Customer Dashboard' }}</title>
</head>

<body class="bg-gradient-to-br from-purple-100 via-white to-green-100 min-h-screen flex flex-col"
    x-data="{ sidebarOpen: false }">

    <!-- Navbar -->
    <nav class="bg-white shadow-md w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <!-- Left Side -->
                <div class="flex items-center">
                    <!-- Sidebar Toggle Button -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-600 hover:text-purple-600 focus:outline-none">
                        <!-- Hamburger Icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <a href="/Customer/dashboard" class="ml-3 font-bold text-purple-700 text-lg">Customer Dashboard</a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-6">
                    <!-- Cart Dropdown -->
                    @if (!request()->routeIs('Customer.checkout'))
                        <x-cart-layout :cartItems="$cartItems ?? collect()" :cartCount="$cartCount ?? 0" />
                    @endif

                    <!-- Logout Button -->
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

    <!-- Sidebar + Content -->
    <div class="flex flex-1">

        <!-- Sidebar -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 w-64 bg-white shadow-md z-30 flex flex-col">

            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-purple-700">Menu</h2>
                <button @click="sidebarOpen = false" class="text-gray-500 hover:text-red-600">
                    ✖
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="/Customer/restaurants" class="block px-4 py-2 rounded hover:bg-purple-100">🍽️ Browse
                    Restaurants</a>
                <a href="/Customer/showorders" class="block px-4 py-2 rounded hover:bg-purple-100">🛒 My Orders</a>
                <a href="/Customer/profile" class="block px-4 py-2 rounded hover:bg-purple-100">👤 Profile</a>
                <a href="/contact" class="block px-4 py-2 rounded hover:bg-purple-100">📞 Contact Us</a>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>

    <x-flash-message />

    <!-- ✅ Toast Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-2"></div>

    <!-- ✅ JS -->
    <script>
        // Toast function
        function showToast(message, type = "success") {
            const container = document.getElementById("toast-container");
            const toast = document.createElement("div");

            const baseClasses = "px-4 py-2 rounded shadow-md text-white transition transform";
            const typeClasses = type === "error"
                ? "bg-red-600"
                : "bg-green-600";

            toast.className = `${baseClasses} ${typeClasses} opacity-0 translate-x-10`;
            toast.innerText = message;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove("opacity-0", "translate-x-10");
            }, 50);

            // Remove after 3s
            setTimeout(() => {
                toast.classList.add("opacity-0", "translate-x-10");
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function updateCart(itemId, delta) {
            const qtyEl = document.getElementById(`qty-${itemId}`);
            if (!qtyEl) return;

            const currentQty = parseInt(qtyEl.textContent.trim(), 10) || 0;
            const newQty = currentQty + delta;
            if (newQty < 1) return; // keep >= 1

            const incBtn = document.getElementById(`inc-btn-${itemId}`);
            const decBtn = document.getElementById(`dec-btn-${itemId}`);

            if (incBtn) incBtn.disabled = true;
            if (decBtn) decBtn.disabled = true;

            fetch(`/cart/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQty })
            })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        // 🧩 NEW: Display backend error (like stock limit)
                        const msg = data.message || 'Could not update cart.';
                        throw new Error(msg);
                    }
                    return data;
                })
                .then(data => {
                    if (!data || !data.success) {
                        // 🧩 NEW: Show backend-provided message if available
                        showToast(data.message || "Could not update cart.", "error");
                        return;
                    }

                    document.getElementById(`qty-${itemId}`).innerText = data.quantity;
                    document.getElementById(`itemTotal-${itemId}`).innerText = data.itemTotal + ' PKR';
                    const cartTotalEl = document.getElementById('cartTotal');
                    if (cartTotalEl) cartTotalEl.innerText = 'Total: ' + data.cartTotal + ' PKR';

                    const badge = document.querySelector('[data-cart-count]');
                    if (badge && typeof data.cartCount !== 'undefined') badge.innerText = data.cartCount;

                    showToast("Cart updated successfully!", "success");
                })
                .catch(err => {
                    console.error('Update cart failed', err);
                    // 🧩 NEW: Display actual backend error message
                    showToast(err.message || "Could not update cart. Try again.", "error");
                })
                .finally(() => {
                    if (incBtn) incBtn.disabled = false;
                    if (decBtn) decBtn.disabled = false;
                });
        }

        // Add to cart
        function addToCart(listingId) {
            fetch('/cart', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    listing_id: listingId,
                    quantity: 1
                })
            })
                .then(res => res.json())
                .then(data => {
                    // 🧩 CASE 1: Restaurant conflict detected
                    if (data.conflict) {
                        if (confirm(data.message)) {
                            fetch(`/cart/clear`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json",
                                    "X-Requested-With": "XMLHttpRequest",
                                }
                            })
                                .then(res => res.json())
                                .then(clearData => {
                                    if (clearData.success) {
                                        addToCart(listingId);
                                    } else {
                                        showToast("Failed to clear cart.", "error");
                                    }
                                })
                                .catch(err => {
                                    console.error("Cart clear error:", err);
                                    showToast("Could not clear cart.", "error");
                                });
                        }
                        return;
                    }

                    // 🧩 CASE 2: Add failed (including stock limit)
                    if (!data.success) {
                        // 🧩 NEW: Show backend message like “Only 2 items remaining”
                        showToast(data.message || "Could not add item.", "error");
                        return;
                    }

                    // 🧩 CASE 3: Successfully added to cart
                    let countEl = document.querySelector('[data-cart-count]');
                    if (countEl) countEl.textContent = data.cartCount;

                    let cartDropdown = document.querySelector('[data-cart-items]');
                    if (cartDropdown && data.cartItems) cartDropdown.innerHTML = data.cartItems;

                    let cartTotalEl = document.getElementById('cartTotal');
                    if (cartTotalEl) cartTotalEl.innerText = 'Total: ' + data.cartTotal + ' PKR';

                    showToast(data.message || "Item added to cart!", "success");
                })
                .catch(err => {
                    console.error("Add to cart error:", err);
                    // 🧩 NEW: Show backend error messages if available
                    showToast(err.message || "Could not add item.", "error");
                });
        }

    </script>
    <script>
        async function deleteCartItem(id) {
            try {
                let res = await fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error(await res.text());
                let data = await res.json();

                if (!data.success) {
                    showToast("Could not remove item.", "error");
                    return;
                }

                // ✅ Remove the item from the dropdown using the unique wrapper
                const itemRow = document.getElementById(`cart-item-${id}`);
                if (itemRow) itemRow.remove();

                // ✅ Update cart badge count
                const countEl = document.querySelector('[data-cart-count]');
                if (countEl) countEl.textContent = data.cartCount;

                // ✅ Update cart total
                const cartTotalEl = document.getElementById('cartTotal');
                if (cartTotalEl) {
                    cartTotalEl.innerText = 'Total: ' + data.cartTotal + ' PKR';
                }

                // ✅ Disable Checkout if cart empty
                const checkoutBtn = document.getElementById('checkoutBtn');
                if (checkoutBtn) {
                    checkoutBtn.disabled = (parseInt(data.cartCount) || 0) === 0;
                }

                // ✅ If empty, show placeholder
                const cartDropdown = document.querySelector('[data-cart-items]');
                if (data.cartCount === 0 && cartDropdown) {
                    cartDropdown.innerHTML = `<p class="text-gray-500 text-center py-6">Your cart is empty 🛒</p>`;
                }

                // ✅ Success toast
                showToast(data.message || "Item removed from cart!", "success");

            } catch (err) {
                console.error("Delete cart error:", err);
                showToast("Could not remove item. Try again.", "error");
            }
        }

    </script>


</body>

</html>