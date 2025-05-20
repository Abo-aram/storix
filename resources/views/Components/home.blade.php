

<x-layouts.app-layout title="Home">
    @include('partials.messages')

    <button id="bb"
    >notify</button>

<div class="w-full bg-white shadow-md px-6 py-4 flex items-center justify-between  top-0 rounded-2xl  z-50 border-b border-gray-200 border-2">
    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="w-full flex items-center justify-between gap-4">
        @csrf

      
        {{-- Left: File input --}}
        <label class="flex items-center gap-3 text-20">
            <span class="text-gray-600 font-medium">Choose a file</span>
            <input
                type="file"
                name="file"
                required
                class="text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-semibold
                       file:bg-blue-50 file:text-blue-700
                       hover:file:bg-blue-100
                       transition duration-150"
            >
        </label>

        {{-- Right: Upload button --}}
        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition duration-200"
        >
            Upload
        </button>
    </form>
</div>

{{-- Padding to avoid content being hidden under fixed header --}}
<div class="pt-4"></div>

<!-- Uploaded Files Section -->
<div class="mt-6">

    <h2 class="text-xl font-bold text-gray-800 mb-4">Uploaded Files</h2>
    
    <div class="flex justify-between border-b border-gray-300 mb-4">

                <form method="GET" class="mb-6 flex flex-wrap gap-4 items-center">
            <!-- Filter by Type -->
            <select name="type" class="border rounded px-3 py-2 text-sm">
                <option value="">All Types</option>
                <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Other Files</option>
            </select>

            <!-- Sort by -->
            <select name="sort" class="border rounded px-3 py-2 text-sm">
                <option value="">Sort By</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A–Z)</option>
                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z–A)</option>
                <option value="size_asc" {{ request('sort') === 'size_asc' ? 'selected' : '' }}>Size (Small → Large)</option>
                <option value="size_desc" {{ request('sort') === 'size_desc' ? 'selected' : '' }}>Size (Large → Small)</option>
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                Apply
            </button>
        </form>

        <form method="GET" id="fomr" class="mb-6 flex flex-wrap gap-4 items-center">
            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search files..."
                class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            />

            
        </form>



    </div>
   

    @if(count($files) > 0)
        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
            {{-- Loop through each file and display it --}}
            @foreach($files as $file)
                <div class="bg-white shadow-lg relative rounded-xl  p-4 flex flex-col justify-between border border-gray-200 hover:shadow-xl transition">
                    @if ($file->extension == 'png' || $file->extension == 'jpg' || $file->extension == 'jpeg')
                        <img src="{{ asset('storage/' . $file->path) }}" alt="{{ $file->original_name }}" class="w-full h-32 object-cover rounded-lg mb-4">
                        
                    @else
                    <svg class="self-center" width="128" height="128" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect width="128" height="128" rx="16" fill="url(#gradient)"/>
  <path d="M76 32H44L32 44V96H96V32H76Z" fill="white"/>
  <path d="M76 32V44H88L76 32Z" fill="#E3E3E3"/>
  <path d="M40 56H88M40 68H88M40 80H72" stroke="#5E5E5E" stroke-width="4" stroke-linecap="round"/>
  <defs>
    <linearGradient id="gradient" x1="64" y1="0" x2="64" y2="128" gradientUnits="userSpaceOnUse">
      <stop stop-color="#2196F3"/>
      <stop offset="1" stop-color="#1976D2"/>
    </linearGradient>
  </defs>
                    </svg>

                    
                    @endif
                  
                  
                    
                    <div>
                        <h3 class="text-gray-700 font-semibold truncate">{{ $file->original_name }}</h3>
                        <p class="text-sm text-gray-500">Size: {{ number_format($file->size / 1024, 2) }} KB</p>
                        <p class="text-sm text-gray-400">Uploaded: {{ $file->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <a href="{{ url('download/' . $file->id) }}" class="btn btn-success">
                            Download
                        </a>


                        <form action="{{route('delete',[$file->id])}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No files uploaded yet.</p>
    @endif
</div>


</x-layouts.app-layout> 
