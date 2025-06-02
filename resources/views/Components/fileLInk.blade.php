<x-layouts.app-layout title="=download">

<div class="flex justify-center items-center mt-12">
    <div class="bg-white shadow-lg border-2 border-gray-200 rounded-2xl p-6 w-full max-w-xl text-center">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Your Download Link</h2>
        
        <div class="bg-gray-100 border border-gray-300 rounded-lg px-4 py-3 mb-4 flex items-center justify-between">
            <p id="downloadLink" class="text-sm text-gray-700 truncate">
                {{ route('download', ['id' => $id,'isLink'=>false]) }}
            </p>
            <button id="copyBtn" onclick="copyToClipboard()" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">
                Copy
            </button>
        </div>

        <p class="text-xs text-gray-500">Click the button to copy the link to your clipboard</p>
    </div>
</div>

</x-layouts.app-layout>
