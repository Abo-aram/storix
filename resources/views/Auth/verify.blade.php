<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Email Verification Required</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    @if ($isVerified)
        <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg text-center">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Verify Your Email</h1>
    <p class="text-gray-600 text-sm mb-6">
      We've sent a verification link to your email. Please check your inbox and click the link to verify your account.
    </p>

    <p class="text-gray-500 text-sm mb-6">
      Once verified, you can log in to your account.
    </p>

    <a href="{{ route('login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition">
      Go to Login
    </a>

    <div class="mt-6 text-xs text-gray-400">
      Didn't receive the email? Check your spam folder or request another one.
    </div>
  </div>
    @else
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg text-center">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Verify Your Email</h1>
    <p class="text-gray-600 text-sm mb-6">
      We've sent a verification link to your email. Please check your inbox and click the link to verify your account.
    </p>

    <p class="text-gray-500 text-sm mb-6">
      Once verified, you can log in to your account.
    </p>

    <a  href="#" class="pointer-events-none cursor-not-allowed inline-block bg-gray-600 hover:bg-gray-800 text-white text-sm font-medium px-6 py-2 rounded-lg transition">
      Go to Login
    </a>

    <div class="mt-6 text-xs text-gray-400">
      Didn't receive the email? Check your spam folder or request another one.
    </div>
  </div>
  @endif
  
</body>
</html>
