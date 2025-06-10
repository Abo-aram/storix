<x-layouts.app-layout title="Home">
    @include('partials.messages')

    <div id="blurDiv" class="transition-all duration-100">

        <div id="uploadDiv" class=" pl-6 pr-6  pb-2  border-2 rounded-2xl ">
            <div id="header" class=" flex justify-between items-center p-4">
                <div>

                    <h1 class="text-2xl font-bold text-gray-800">File Upload</h1>
                    <p>click the button to open file upload form</p>

                </div>

                <img id="formBtn" src="{{ asset('svg/plus.svg') }}" alt="Logo"
                    class=" rounded-full shadow-md bg-cyan-500 h-12 w-12 cursor-pointer hover:scale-125 transition-all dur100"
                    >


            </div>
            <div id="formDiv" class=" flex justify-between items-center p-4">
                <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="w-full">
                    @csrf
                    <div class="flex gap-4 mb-3">
                        <h3 class=" font-semibold text-gray-700 min-w-1/8 p-1 font text-lg  ">Rename File</h3>
                        <input type="text" name="FileRename" placeholder="Optional"
                            class="w-full px-4 py-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-150">

                    </div>
                    <div class="flex gap-4 mb-4">
                        <h3 class=" font-semibold text-gray-700 min-w-1/8 p-1 font text-lg  ">Folder</h3>
                        <select id="folderSelector" class="rounded-lg border">
                            <option value="">Select Folder</option>
                            <option value="folder1">Folder 1</option>
                            <option value="folder2">Folder 2</option>
                            <option value="folder3">Folder 3</option>
                        </select>
                        <input id="selectFolder" type="text" name="FileRename" placeholder="Optional"
                            class="w-full px-4 py-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-150">

                    </div>
                    <hr class="mb-4">
                    <div class="flex justify-center items-center p-4 mb-4 rounded-lg flex-col gap-8">


                        <div class="p-6 rounded-lg shadow-md w-full max-w-3xl">

                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload Documents</h2>

                            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-white p-8 hover:border-cyan-400 cursor-pointer transition duration-300"
                                id="dropzone">
                                <img src="{{ asset('svg/uploadFile.svg') }}" alt="Upload Icon"
                                    class="w-16 h-16 mb-4 text-gray-400">

                                <p class="text-lg font-medium text-gray-700">Upload a File</p>


                                <input type="file" name="file" class="hidden" id="fileInput" />
                            </div>

                            <h2  class="hiddeBeforSelect hidden text-xl font-semibold text-gray-800 mt-4">Selected File</h2>
                            <div class="hiddeBeforSelect hidden flex justify-around border-2 border-cyan-500 mt-2 rounded-lg bg-white p-8 cursor-pointer "
                                id="dropzone">
                                <img src="{{ asset('svg/file.svg') }}" alt="File Icon"
                                    class="w-16 h-16 mb-4 text-gray-400">
                                

                                <h3 id="fileName"><span class="text-cyan-600 font-semibold">Origianl Name:</span> name
                                    is here</h3>
                                
                                <h3 id="fileSize"><span class="text-cyan-600 font-semibold">Size: </span> File name is
                                    here</h3>




                            </div>
                        </div>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition duration-200 ">
                            Upload
                        </button>




                    </div>

                </form>

            </div>

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
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A–Z)
                        </option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z–A)
                        </option>
                        <option value="size_asc" {{ request('sort') === 'size_asc' ? 'selected' : '' }}>Size (Small →
                            Large)</option>
                        <option value="size_desc" {{ request('sort') === 'size_desc' ? 'selected' : '' }}>Size (Large →
                            Small)</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        Apply
                    </button>
                </form>

                <form method="GET" id="fomr" class="mb-6 flex flex-wrap gap-4 items-center">
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Search files..."
                        class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none" />


                </form>



            </div>


            @if(count($files) > 0)
                <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
                    {{-- Loop through each file and display it --}}
                    @foreach($files as $file)
                        <div
                            class="bg-white shadow-lg relative rounded-xl  p-4 flex flex-col justify-between border border-gray-200 hover:shadow-xl transition">
                            @if ($file->extension == 'png' || $file->extension == 'jpg' || $file->extension == 'jpeg')
                                <img src="{{ asset('storage/' . $file->path) }}" alt="{{ $file->original_name }}"
                                    class="w-full h-32 object-cover rounded-lg mb-4">

                            @else
                                <svg class="self-center" width="128" height="128" viewBox="0 0 128 128" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="128" height="128" rx="16" fill="url(#gradient)" />
                                    <path d="M76 32H44L32 44V96H96V32H76Z" fill="white" />
                                    <path d="M76 32V44H88L76 32Z" fill="#E3E3E3" />
                                    <path d="M40 56H88M40 68H88M40 80H72" stroke="#5E5E5E" stroke-width="4"
                                        stroke-linecap="round" />
                                    <defs>
                                        <linearGradient id="gradient" x1="64" y1="0" x2="64" y2="128"
                                            gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#2196F3" />
                                            <stop offset="1" stop-color="#1976D2" />
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
                                <div class="relative inline-block text-left">
                                    <button
                                        class=" dropdownBtn inline-flex justify-center w-full rounded-md bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 focus:outline-none">
                                        Download
                                    </button>

                                    <div
                                        class="dropdownMenu hidden absolute z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">

                                            <a href="{{ url('download/' . $file->id) . '/false'}}"
                                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                Download
                                            </a>
                                            <button onclick="requestURL({{ $file->id }})"
                                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                                Download Link
                                            </button>

                                        </div>
                                    </div>
                                </div>






                                <form action="{{route('delete', [$file->id])}}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this file?')">
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

    </div>



    <div class=" justify-center items-center mt-12 hidden " id="popup">
        <div
            class="  bg-white shadow-lg border-2  border-gray-500 rounded-2xl p-6 w-full max-w-xl text-center absolute top-2/5">
            <h1 class="absolute right-6 text-red-500 cursor-pointer " onclick="closePopup()">X</h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Your Download Link</h2>

            <div
                class="bg-gray-100 turnGreen transition-all duration-200 border border-gray-300 rounded-lg px-4 py-3 mb-4 flex items-center justify-between">
                <p id="downloadLink" class="text-sm text-gray-700 truncate">

                </p>
                <button id="copyBtn" onclick="copyToClipboard()"
                    class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">
                    Copy
                </button>
            </div>

            <p class="text-xs text-gray-500">Click the button to copy the link to your clipboard</p>
        </div>
    </div>


</x-layouts.app-layout>