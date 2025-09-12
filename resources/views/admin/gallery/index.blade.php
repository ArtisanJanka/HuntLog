<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <h1 class="text-3xl font-bold text-black-200 mb-4 sm:mb-0">Gallery Items</h1>
            <a href="{{ route('admin.gallery.create') }}" 
               class="px-5 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition shadow">
               Add New Item
            </a>
        </div>

        {{-- Gallery Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galleryItems as $item)
                <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 relative">
                    <img src="{{ $item->url() }}" alt="{{ $item->title }}" class="w-full h-56 object-cover">
                    
                    {{-- Info Overlay --}}
                    <div class="p-4">
                        <h2 class="text-white text-lg font-semibold truncate">{{ $item->title ?: 'Untitled' }}</h2>
                        <p class="text-gray-400 text-sm mt-1 truncate">{{ $item->huntingType->name }}</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="absolute top-2 right-2 flex flex-col space-y-2">
                        <a href="{{ route('admin.gallery.edit', $item) }}" 
                           class="px-3 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm shadow">
                           Edit
                        </a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm shadow">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($galleryItems->isEmpty())
            <p class="text-gray-400 mt-6 text-center">No gallery items found. Start by adding a new one!</p>
        @endif
    </div>
</x-app-layout>
