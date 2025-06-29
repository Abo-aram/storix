<!DOCTYPE html>
<html lang="en" class="overflow-hidden" >
<head>
 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>[x-cloak] { display: none; }</style>

    <title>{{ $title ?? 'Storix' }}</title>

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!--tailwindcss-->
     
    
</head>

<body class="flex min-h-screen" >
    
    <!-- Sidebar -->
    @csrf
    <aside class="bg-gray-300 text-white w-64 p-2 pt-5 flex flex-col shadow-xl ">
        
        <!-- Logo and User Info -->
    
        <h1 class="text-2xl font-bold mb-6 tracking-wide text-center text-cyan-500 ">Storix</h1>
        <div class="rounded-xl p-1 mb-3 flex flex-row items-center justify-around">
            <img src="{{ asset('images/logo.png') }}" alt="User Avatar" class="w-10 h-10 rounded-full ">
            <p id="userName" class="text-green-400 text-sm ">Welcome</p>
            <img src="{{ asset('svg/whiteGear.svg') }}" alt="Arrow Icon" class="w-4 h-4 cursor-pointer hover:scale-110 transition-transform ">
        </div>
       
    
    
    

            
        <nav class="flex flex-col gap-4 mb-4 ">
           <ul class="space-y-2 ">
                <li class="bg-gray-700 rounded-3xl p-2 pl-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-3xl p-2 pl-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-3xl p-2 pl-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-3xl p-2 pl-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-3xl p-2 pl-4">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                
            </ul>

            
           </ul>
        </nav>


        <div id="folderDiv" class="w-full bg-gray-200  pb-4 rounded-xl relative border border-gray-300 shadow-sm">
            <div class="flex justify-between items-center px-2 pt-2">
                <label for="folderList" class="text-gray-800 text-md">Folders</label>
                <img id="addFolderBtn" src="{{ asset('svg/plus.svg') }}"
                    alt="Add Folder" class="w-8 h-8 cursor-pointer hover:scale-110 transition-transform" />
            </div>

            <button id="moreFoldersBtn"
                class="bg-blue-200 text-sm text-gray-800 rounded-2xl px-3 py-1 hover:bg-blue-300 transition-all duration-200 hover:scale-105 absolute -bottom-3 right-2/5 flex items-center">
                <svg width="20" height="20" viewBox="0 0 24 24" class="stroke-gray-800">
                    <polyline points="4,7 12,17 20,7" fill="none" stroke-width="2" />
                </svg>
            </button>

            <ul id="folderList"
                class="space-y-2 max-h-32 p-2 overflow-y-auto hide-scrollbar transition-max-height text-black">
                <!-- folders will be dynamically inserted here -->
            </ul>
        </div>


    
    </aside>

    

    <!-- Main Content -->
    <div class="bg-gray-300 flex flex-1 ">
        <main class="flex-1 pt-4 pb-4 pr-4">
            {{ $slot }}
        </main>
     
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
