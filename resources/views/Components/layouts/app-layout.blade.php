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
    <aside class="bg-gray-950 text-white w-64 min-h-screen p-6 flex flex-col shadow-xl">
    <h1 class="text-2xl font-bold mb-8 tracking-wide text-center">Storix</h1>


 <svg width="200" height="24" viewBox="0 0 180 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Horizontal line left -->
  <line x1="0" y1="12" x2="70" y2="12" stroke="#2563EB" stroke-width="2" stroke-linecap="round" />
  
  <!-- Small square (abstract file/folder) -->
  <rect x="80" y="6" width="20" height="12" fill="#2563EB" rx="2" ry="2" />
  
  <!-- Horizontal line right -->
  <line x1="110" y1="12" x2="230" y2="12" stroke="#2563EB" stroke-width="2" stroke-linecap="round" />
</svg>


    
        <nav class="flex flex-col gap-4">
           <ul class="space-y-2 ">
                <li class="bg-gray-700 rounded-lg p-2
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                
            </ul>

            
           </ul>
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
