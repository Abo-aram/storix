@if (session('message'))
        <div class="fixed bottom-4 right-4 bg-blue-500 text-white p-4 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
        
@endif