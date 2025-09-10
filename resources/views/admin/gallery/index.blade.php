<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-200">Gallery Items</h1>
            <a href="{{ route('admin.gallery.create') }}" 
               class="px-4 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600 transition">
               Add New Item
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galleryItems as $item)
                <div class="bg-gray-800 rounded-lg shadow overflow-hidden relative">
                    <img src="{{ $item->url() }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-white font-semibold">{{ $item->title }}</h2>
                            <p class="text-gray-400 text-sm">{{ $item->huntingType->name }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.gallery.edit', $item) }}" 
                               class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm">
                               Edit
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm"
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
