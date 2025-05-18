<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Storix' }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!--tailwindcss-->
     
    
</head>

<body class="flex" >
    <!-- Sidebar -->
    <aside class="bg-gray-800 text-white w-64 min-h-screen p-6 flex flex-col shadow-xl">
    <h1 class="text-2xl font-bold mb-8 tracking-wide text-center">📦 Storix</h1>
    
    <nav class="flex flex-col gap-4">
        <a href="/" class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-700 transition">
            🏠 <span class="font-medium">Dashboard</span>
        </a>
        <a href="/files" class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-700 transition">
            📁 <span class="font-medium">My Files</span>
        </a>
        <a href="/upload" class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-700 transition">
            ⬆️ <span class="font-medium">Upload</span>
        </a>
        <a href="/settings" class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-700 transition">
            ⚙️ <span class="font-medium">Settings</span>
        </a>
    </nav>
</aside>


    <!-- Main Content -->
    <div class="bg-white flex flex-1">
        <main class="flex-1 p-4">
            {{ $slot }}
        </main>
     
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
