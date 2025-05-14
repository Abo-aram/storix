<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Storix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800">Welcome Back</h2>
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="form-checkbox text-blue-600">
                    <span class="ml-2 text-gray-600">Remember me</span>
                </label>
                <a href="{{route('rese')}}" class="text-blue-500 hover:underline">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">Login</button>

            <p class="text-center text-sm text-gray-600 mt-4">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register here</a>
            </p>
        </form>
    </div>
 
    @include('partials.messages')
    

</body>
</html>
