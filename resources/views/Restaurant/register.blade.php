<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bakery Owner Signup</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body
  class="bg-gradient-to-br from-rose-100 via-orange-100 to-yellow-50 min-h-screen flex items-center justify-center p-6">

  <div class="relative w-full max-w-lg">
    <!-- Decorative Circles -->
    <div class="absolute -top-10 -left-10 w-40 h-40 bg-purple-200 rounded-full blur-3xl opacity-40 animate-pulse"></div>
    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-pink-200 rounded-full blur-3xl opacity-40 animate-pulse">
    </div>

    <!-- Signup Card -->
    <div class="relative bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl px-8 py-10 border border-white/40">

      <!-- Header -->
      <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-2">Bakery Owner Signup</h2>
      <p class="text-center text-gray-500 mb-8">Join us and start sharing your sweet creations!</p>

      <!-- Form -->
      <form action="/Restaurantowner/register" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Bakery Name -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Restaurant Name</label>
          <input type="text" name="name" placeholder="Sweet Delights" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
          @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Address -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
          <input type="text" name="address" placeholder="123 Main Street" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
          @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
          <input type="email" name="email" placeholder="bakery@example.com" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
          @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
          <input type="tel" name="phone" placeholder="+92 300 1234567" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
          @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
            @error('password_confirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Image Upload -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Bakery Image</label>
          <input type="file" name="image" accept="image/*"
            class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 transition" />
        </div>

        <!-- Submit Button -->
        <div>
          <button type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:scale-[1.02] transform transition-all focus:outline-none focus:ring-4 focus:ring-purple-300">
            Sign Up
          </button>
        </div>

        <!-- Login Link -->
        <p class="text-center text-gray-600 mt-6">
          Already have an account?
          <a href="/login" class="text-purple-700 font-semibold hover:underline">Login</a>
        </p>
      </form>
    </div>
  </div>

</body>

</html>