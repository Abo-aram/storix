<x-layouts.app-layout title="Home">
   
    @include('partials.messages')
    @include('partials.JSmessages')













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
            <div id="formDiv" class=" flex justify-between items-center p-4 ">
                <form id="uploadForm" class="w-full">
                    @csrf
                    <div class="flex gap-4 mb-3">
                        
                        <h3 class=" font-semibold text-gray-700 min-w-1/8 p-1 font text-lg  ">Rename File</h3>
                        <input type="text" name="stored_name" placeholder="Optional" id="stored_name"
                            class="w-full px-4 py-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-150">

                    </div>
                    <div id='fileDetailsDiv' class="flex gap-4 mb-4">
                        <h3 class=" font-semibold text-gray-700 min-w-1/8 p-1 font text-lg  ">Folder</h3>
                        <select id="folderSelector" class="rounded-lg border" >
                            <option value="">Select Folder</option>
                            
                        </select>
                        <input id="selectFolder" type="text" name="folder_id" placeholder="Optional"
                            class="w-full px-4 py-2 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-150">
                        
                    </div>
                    <p id='FolderError' class=" transition-all duration-75 text-red-500 text-md mb-4 mt-1 rounded  bg-red-100 flex items-center justify-center">this folder dose not exit</p>
                    <hr class="mb-4">
                    <div class="flex justify-center items-center p-4 mb-4 rounded-lg flex-col gap-8">


                        <div class="p-6 rounded-lg shadow-md w-full max-w-3xl">

                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload Documents</h2>

                            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-white p-8 hover:border-cyan-400 cursor-pointer transition duration-300"
                                id="dropzone">
                                <img src="{{ asset('svg/uploadFile.svg') }}" alt="Upload Icon"
                                    class="w-16 h-16 mb-4 text-gray-400">

                                <p class="text-lg font-medium text-gray-700">Upload a File</p>


                                <input  type="file" name="file" class="hidden" id="fileInput"  required/>
                            </div>

                            <h2  class="hiddeBeforSelect hidden text-xl font-semibold text-gray-800 mt-4">Selected File</h2>
                            <div class="hiddeBeforSelect hidden flex justify-around border-2 border-cyan-500 mt-2 rounded-lg bg-white p-8 cursor-pointer "
                                id="dropzone">
                                <img src="{{ asset('svg/file.svg') }}" alt="File Icon"
                                    class="w-16 h-16  text-gray-400">
                                

                                <h3 class="border border-cyan-500 rounded-lg flex items-center justify-center p-2  bg-cyan-100 text-black font-bold  ">Origianl Name: <span class="max-w-72 truncate" id="fileName" ></span ></h3>
                                
                                <h3 class="border border-cyan-500 rounded-lg flex items-center justify-center p-2 bg-cyan-100 text-black  font-bold " >Size: <span id="fileSize" > </span ></h3>




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
                    <select id="filter" name="type" class="border rounded px-3 py-2 text-sm" >
                        <option value="">All Types</option>
                        <option value="image" >Images</option>
                        <option value="other" >Other Files</option>
                    </select>

                    <!-- Sort by -->
                    <select id="sort" name="sort" class="border rounded px-3 py-2 text-sm">
                        <option value="">Sort By</option>
                        <option value="name_asc" >Name (A–Z)
                        </option>
                        <option value="name_desc" >Name (Z–A)
                        </option>
                        <option value="size_asc" >Size (Small →
                            Large)</option>
                        <option value="size_desc" >Size (Large →
                            Small)</option>
                        <option value="newest" >Newest</option>
                        <option value="oldest" >Oldest</option>
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        Apply
                    </button>
                </form>

                <form method="GET" id="fomr" class="mb-6 flex flex-wrap gap-4 items-center">
                    <input type="text" id="search" name="search""
                        placeholder="Search files..."
                        class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none" />


                </form>



            </div>

            
            <!--here are teh files -->
      
            <div id="fileSection" class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
                
                

            </div>
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