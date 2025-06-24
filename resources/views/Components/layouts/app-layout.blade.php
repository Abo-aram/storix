<!DOCTYPE html>
<html lang="en" >
<head>
 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>[x-cloak] { display: none; }</style>

    <title>{{ $title ?? 'Storix' }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!--tailwindcss-->
     
    
</head>

<body class="flex min-h-screen" >
    
    <!-- Sidebar -->
    @csrf
    <aside class="bg-gray-950 text-white w-64 p-6 flex flex-col shadow-xl ">
        
        <!-- Logo and User Info -->
    
    <h1 class="text-2xl font-bold mb-6 tracking-wide text-center text-cyan-500 ">Storix</h1>
    <div class="rounded-xl p-1 mb-3 flex flex-row items-center justify-around">
         <img src="{{ asset('images/logo.png') }}" alt="User Avatar" class="w-10 h-10 rounded-full ">
         <p id="userName" class="text-green-400 text-sm ">Welcome</p>
         <img src="{{ asset('svg/whiteGear.svg') }}" alt="Arrow Icon" class="w-4 h-4 cursor-pointer hover:scale-110 transition-transform ">
    </div>
       
    
    
    <hr class="border-gray-700 mb-6">

            
        <nav class="flex flex-col gap-4 mb-4 ">
           <ul class="space-y-2 ">
                <li class="bg-gray-700 rounded-lg p-2">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-lg p-2">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-lg p-2">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-lg p-2">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                <li class="bg-gray-700 rounded-lg p-2">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a>
                </li>
                
            </ul>

            
           </ul>
        </nav>


        <div id="folderDiv" class="w-full  bg-black pb-4 rounded-xl relative   border-gray-800 border-1 " >
            <div>
                <label for="folderList" class="text-gray-700 text-md  pl-2">Folders</label>
                <img id='addFolderBtn' src="{{ asset('svg/whitePlus.svg') }}" alt="Folder Icon" class="w-6 h-6 cursor-pointer hover:scale-110 transition-transform absolute right-2 top-2">
        
            </div>
            
            <button id="moreFoldersBtn" class="bg-cyan-500 text-xl  text-black rounded-2xl pl-2 pr-2 hover:bg-gray-400 transition-all duration-200 hover:scale-110 absolute  -bottom-3 right-2/5  ">
                <svg width="24" height="24" viewBox="0 0 24 24">
            <polyline points="4,7 12,17 20,7" fill="none" stroke="black" stroke-width="2"/>
            </svg>

            </button>
            <ul id="folderList" class="space-y-2 max-h-32 p-2  hide-scrollbar transition-max-height">
         
               
                
            </ul>


        </div>

    
    </aside>


    <!-- Main Content -->
    <div class="bg-gray-200 flex flex-1">
        <main class="flex-1 p-4">
            {{ $slot }}
        </main>
     
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
