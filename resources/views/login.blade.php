<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
      <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 via-red-50 to-yellow-100 min-h-screen">

  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
      
      <!-- Header -->
      <h2 class="text-2xl font-bold text-center text-purple-700 mb-6">Login</h2>

      <!-- Form -->
      <form action="/login" method="POST" class="space-y-5">
        @csrf
        
        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" placeholder="bakery@example.com" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5" />

          @error('email')
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
     
        <!-- Submit -->
        <div>
          <button type="submit"
            class="w-full bg-purple-600 text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
            Login
          </button>
        </div>

        <!-- Sign Up Link -->
        <div class="mt-8 text-center">
          <p>
            Don't have an account?
            <a href="/Customer/register" class="text-purple-700 font-bold hover:underline">Sign Up</a>
          </p>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
