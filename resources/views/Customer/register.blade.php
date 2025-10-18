<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Signup Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 via-red-50 to-yellow-100 min-h-screen">

  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
      
      <!-- Heading -->
      <h2 class="text-2xl font-bold text-center text-purple-700 mb-2">📝 Customer Signup</h2>
      <p class="text-center text-gray-600 mb-6">Create your account to start ordering</p>

      <!-- Success / Error messages -->
      @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 border border-green-300 rounded-lg p-3">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="mb-4 text-red-700 bg-red-100 border border-red-300 rounded-lg p-3">
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

        <!-- Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Full Name</label>
          <input type="text" name="name" value="{{ old('name') }}" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5"
            placeholder="John Doe">
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Email Address</label>
          <input type="email" name="email" value="{{ old('email') }}" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5"
            placeholder="example@email.com">
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Phone</label>
          <input type="text" name="phone" value="{{ old('phone') }}" maxlength="11" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5"
            placeholder="03XXXXXXXXX">
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5"
            placeholder="••••••">
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" name="password_confirmation" required
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-2.5"
            placeholder="••••••">
        </div>

        <!-- Submit -->
        <div>
          <button type="submit"
            class="w-full bg-purple-600 text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
            🚀 Sign Up
          </button>
        </div>
      </form>

      <!-- Already have account -->
      <p class="text-sm text-gray-600 text-center mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="text-purple-700 font-bold hover:underline">Login here</a>
      </p>
    </div>
  </div>

</body>
</html>
