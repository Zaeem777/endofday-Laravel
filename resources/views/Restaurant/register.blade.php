<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Owner Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 via-red-50 to-yellow-100 min-h-screen">

  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
      
      <!-- Header -->
      <h2 class="text-2xl font-bold text-center text-purple-700 mb-6">Bakery Owner Signup</h2>

      <!-- Form -->
      <form action="/Restaurantowner/register" method="POST" class="space-y-5" enctype="multipart/form-data">
        @csrf
        
        <!-- Bakery Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Restaurant Name</label>
          <input type="text" name="name" placeholder="Sweet Delights" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5" />

          @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Address -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Address</label>
          <input type="text" name="address" placeholder="123 Main Street" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5" />

          @error('address')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" placeholder="bakery@example.com" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5" />

          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Phone Number</label>
          <input type="tel" name="phone" placeholder="+92 300 1234567" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5" />

          @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5" />

          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" name="password_confirmation" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5" />

          @error('password_confirmation')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Upload Image -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Upload Bakery Image</label>
          <input type="file" name="image" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200" />
        </div>

        <!-- Submit -->
        <div>
          <button type="submit"
            class="w-full bg-purple-600 text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
            Sign Up
          </button>
        </div>

        <!-- Login Link -->
        <div class="mt-8 text-center">
          <p>
            Already have an account?
            <a href="/login" class="text-purple-700 font-bold hover:underline">Login</a>
          </p>
        </div>
      
      </form>
    </div>
  </div>

</body>
</html>
