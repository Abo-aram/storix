<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@if (session('message'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 4000)" 
        x-show="show" 
        x-transition 
        class="fixed  right-6 max-w-sm w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white p-4 pl-6 pr-10 rounded-xl shadow-xl flex items-center space-x-4 z-50"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10 10 0 0012 2z" />
        </svg>

        <span class="flex-1 text-sm font-medium">
            {{ session('message') }}
        </span>

        <button @click="show = false" class="absolute top-2 right-2 text-white hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif
