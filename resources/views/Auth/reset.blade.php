<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reset Password - Storix</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
  <div class="max-w-4xl w-full bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">

    <!-- Left Side (Info panel) -->
    <div class="hidden md:flex md:w-1/2 bg-blue-600 items-center justify-center p-8">
      <div class="text-white text-center space-y-4">
        <h2 class="text-3xl font-bold">Reset Your Password</h2>
        <p class="text-lg">Enter your email, reset code, and new password below to regain access to your account securely.</p>
        <p class="mt-6 text-xl font-semibold text-blue-300 break-words">{{$token}}</p>
      </div>
    </div>

    <!-- Right Side (Form) -->
    <div class="w-full md:w-1/2 p-8 space-y-6">
      <h2 class="text-2xl font-bold text-center text-gray-800">Reset Your Password</h2>
      <form action="{{route('reset-password.post')}}" method="POST" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            required
            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Token -->
        <div>
          <label for="token" class="block text-sm font-medium text-gray-700">Reset Code</label>
          <input
            type="text"
            id="token"
            name="token"
            required
            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- New Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
          <input
            type="password"
            id="password"
            name="password"
            required
            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            required
            class="mt-1 w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Submit -->
        <div>
          <button
            type="submit"
            class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition"
          >
            Reset Password
          </button>
        </div>
      </form>

      <div class="text-center text-sm text-gray-500">
        Remembered your password?
        <a href="{{route('login')}}" class="text-blue-600 hover:underline">Login</a>
      </div>
    </div>
  </div>

  @include('partials.messages')
</body>
</html>
