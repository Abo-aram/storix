<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Storix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">

        <!-- Left Side (branding or info) -->
        <div class="hidden md:flex md:w-1/2 bg-blue-600 items-center justify-center p-8">
            <div class="text-white text-center space-y-4">
                <h2 class="text-3xl font-bold">Password Help</h2>
                <p class="text-lg">We’ll send you a secure link to reset your password and get back to your files in no time.</p>
            </div>
        </div>

        <!-- Right Side (Form) -->
        <div class="w-full md:w-1/2 p-8 space-y-6">
            <h2 class="text-2xl font-semibold text-center text-gray-800">Forgot Your Password?</h2>

            @if (session('message'))
                <div class="p-3 bg-green-100 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('request-reset-password.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror">

                    @error('email')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                    Send Reset Link
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
