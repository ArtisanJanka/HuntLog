<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-black-200 mb-6">Admin Dashboard</h1>
        <p class="text-gray-400 mb-8">Welcome, {{ Auth::user()->name }}! You can manage gallery items and hunting types here.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-200 mb-2">Gallery Items</h2>
                <p class="text-gray-400">Total items: {{ \App\Models\GalleryItem::count() }}</p>
                <a href="{{ route('admin.gallery.create') }}" class="mt-4 inline-block px-4 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600 transition">
                    Add New Gallery Item
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="mt-2 inline-block px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600 transition">
                    View All Items
                </a>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-200 mb-2">Hunting Types</h2>
                <p class="text-gray-400">Total types: {{ \App\Models\HuntingType::count() }}</p>
                <a href="{{ route('admin.hunting-types.index') }}" class="mt-4 inline-block px-4 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600 transition">
                    Manage Hunting Types
                </a>
            </div>
        </div>

        <h2 class="text-2xl font-semibold text-black-200 mb-4">Latest Gallery Items</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach(\App\Models\GalleryItem::latest()->take(8)->get() as $item)
                <div class="bg-gray-700 p-3 rounded-lg shadow-md relative">
                    <img src="{{ $item->url() }}" alt="{{ $item->title ?? $item->huntingType->name }}" class="w-full h-40 object-cover rounded mb-2">
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
    </div>
</x-app-layout>
