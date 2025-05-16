

<x-layouts.app-layout title="Home">
    @include('partials.messages')

<div class="w-full bg-white shadow-md px-6 py-4 flex items-center justify-between  top-0 rounded-2xl  z-50 border-b border-gray-200 border-2">
    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="w-full flex items-center justify-between gap-4">
        @csrf

        {{-- Left: File input --}}
        <label class="flex items-center gap-3">
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
<div class="pt-12"></div>

<!-- Uploaded Files Section -->
<div class="mt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Uploaded Files</h2>

    @if(count($files) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($files as $file)
                <div class="bg-white shadow-lg rounded-xl p-4 flex flex-col justify-between border border-gray-200 hover:shadow-xl transition">
                    <div>
                        <h3 class="text-gray-700 font-semibold truncate">{{ $file->original_name }}</h3>
                        <p class="text-sm text-gray-500">Size: {{ number_format($file->size / 1024, 2) }} KB</p>
                        <p class="text-sm text-gray-400">Uploaded: {{ $file->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank" 
                           class="text-blue-600 hover:underline text-sm">
                            View
                        </a>

                        {{-- <form action="{{ route('deleteFile', $file->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                Delete
                            </button>
                        </form> --}}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No files uploaded yet.</p>
    @endif
</div>


</x-layouts.app-layout> 
