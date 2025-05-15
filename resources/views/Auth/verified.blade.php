<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verified</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-md text-center w-full max-w-md">
     
            <div class="text-green-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">Email Verified!</h2>
            <p class="text-gray-600">Your email has been successfully verified. You can now log in.</p>
            <a href="{{ route('login') }}" class="mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all">Go to Login</a>
       
            
            <a href="#" class="mt-6 inline-block px-6 py-2 bg-gray-400 text-white rounded-full hover:bg-gray-500 transition-all">Resend Email</a>
     
    </div>

</body>
</html>
