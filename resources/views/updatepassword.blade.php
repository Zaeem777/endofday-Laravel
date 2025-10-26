<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
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
        <div class="absolute -top-10 -left-10 w-32 h-32 bg-purple-300 rounded-full blur-3xl opacity-30 animate-pulse">
        </div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-pink-200 rounded-full blur-3xl opacity-30 animate-pulse">
        </div>

        <!-- Card -->
        <div class="relative bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl px-8 py-10 border border-white/40">

            <!-- Header -->
            <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-2">Reset Your Password</h2>
            <p class="text-center text-gray-500 mb-8">Enter a new password to secure your account</p>

            <!-- 🔔 Message Alerts -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-800 border border-green-300 px-4 py-3 rounded text-center">
                    {{ session('success') }}
                </div>
            @endif

            {{-- @if (session('error'))
            <div class="mb-4 bg-red-100 text-red-800 border border-red-300 px-4 py-3 rounded text-center">
                {{ session('error') }}
            </div>
            @endif --}}

            @if (session('status'))
                <div class="mb-4 bg-blue-100 text-blue-800 border border-blue-300 px-4 py-3 rounded text-center">
                    {{ session('status') }}
                </div>
            @endif

            {{-- @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-800 border border-red-300 px-4 py-3 rounded">
                <ul class="list-disc list-inside text-left">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif --}}

            <!-- Form -->
            <form method="POST" action="{{ route('resetpassword') }}" class="space-y-6">
                @csrf

                <!-- Hidden token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ request('email') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" />
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:scale-[1.02] transform transition-all focus:outline-none focus:ring-4 focus:ring-purple-300">
                        Update Password
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

                <!-- Back to Login -->
                <p class="text-center text-gray-600">
                    Remembered your password?
                    <a href="/login" class="text-purple-700 font-semibold hover:underline">Login</a>
                </p>
            </form>
        </div>
    </div>

</body>

</html>