<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Signup</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-8px);
      }
    }
  </style>
</head>

<body
  class="bg-gradient-to-br from-purple-100 via-pink-50 to-yellow-100 min-h-screen flex items-center justify-center px-4">

  <div
    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 border border-purple-100 animate-[float_5s_ease-in-out_infinite]">

    <!-- Logo / Heading -->
    <div class="text-center space-y-1">

      <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-700 to-pink-600">
        Customer Signup
      </h2>
      <p class="text-gray-500">Create your account to start ordering</p>
    </div>

    <!-- Success / Error Messages -->
    @if(session('success'))
      <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-sm">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Signup Form -->
    <form action="{{ route('customer.register') }}" method="POST" class="space-y-5">
      @csrf

      <!-- Full Name -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required
          class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-3 placeholder-gray-400"
          placeholder="John Doe" />
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required
          class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-3 placeholder-gray-400"
          placeholder="example@email.com" />
      </div>

      <!-- Phone -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" maxlength="11" required
          class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-3 placeholder-gray-400"
          placeholder="03XXXXXXXXX" />
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
          class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-3 placeholder-gray-400"
          placeholder="••••••" />
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" required
          class="w-full rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 p-3 placeholder-gray-400"
          placeholder="••••••" />
      </div>

      <!-- Submit Button -->
      <div class="pt-2">
        <button type="submit"
          class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold py-3 rounded-xl shadow-lg hover:scale-[1.02] transition transform duration-200 hover:shadow-purple-300">
          🚀 Create Account
        </button>
      </div>
    </form>

    <!-- Footer -->
    <p class="text-sm text-gray-600 text-center mt-4">
      Already have an account?
      <a href="{{ route('login') }}" class="text-purple-600 font-bold hover:underline">Login here</a>
    </p>

  </div>
</body>

</html>