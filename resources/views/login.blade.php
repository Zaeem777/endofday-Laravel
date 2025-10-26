<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body
  class="bg-gradient-to-br from-pink-100 via-purple-100 to-yellow-50 min-h-screen flex items-center justify-center p-6">

  <div class="relative w-full max-w-md">
    <!-- Decorative glowing shapes -->
    <div class="absolute -top-10 -left-10 w-32 h-32 bg-purple-300 rounded-full blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-pink-200 rounded-full blur-3xl opacity-30 animate-pulse">
    </div>

    <!-- Login Card -->
    <div class="relative bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl px-8 py-10 border border-white/40">

      <!-- Header -->
      <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-2">Welcome Back</h2>
      <p class="text-center text-gray-500 mb-8">Login to continue to your account</p>

      <!-- Form -->
      <form action="/login" method="POST" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
          <input type="email" name="email" placeholder="you@example.com" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
          <input type="password" name="password" placeholder="••••••••" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Remember / Forgot -->
        <div class="flex items-center justify-between text-sm text-gray-600">
          <a href="/forgotpassword" class="text-purple-700 font-medium hover:underline">Forgot password?</a>
        </div>

        <!-- Submit -->
        <div>
          <button type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:scale-[1.02] transform transition-all focus:outline-none focus:ring-4 focus:ring-purple-300">
            Login
          </button>
        </div>

        <!-- Divider -->
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="bg-white/80 px-3 text-gray-500">or</span>
          </div>
        </div>

        <!-- Sign Up Link -->
        <p class="text-center text-gray-600">
          Don’t have an account?
          <a href="/Customer/register" class="text-purple-700 font-semibold hover:underline">Sign Up</a>
        </p>
      </form>
    </div>
  </div>

</body>

</html>