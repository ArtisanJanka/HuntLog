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
        <div class="bg-gray-700 p-3 rounded-lg shadow-md relative">
            <img src="{{ asset('storage/' . $item->image_path) }}" 
                 alt="{{ $item->title ?? $item->huntingType->name }}" 
                 class="w-full h-40 object-cover rounded mb-2">
            <div class="text-gray-200 font-semibold">{{ $item->title ?? $item->huntingType->name }}</div>
            <div class="flex justify-between mt-2">
                <a href="{{ route('admin.gallery.edit', $item) }}" class="text-emerald-400 hover:text-emerald-500 text-sm">Edit</a>
                <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-500 text-sm">Delete</button>
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
