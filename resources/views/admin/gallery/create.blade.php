<x-app-layout>
    <div class="max-w-xl mx-auto p-6">
        <h1 class="text-2xl font-semibold text-white mb-4">Add Image</h1>
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 mb-1">Hunting Type</label>
                <select name="hunting_type_id" class="w-full bg-gray-800 text-white rounded p-2" required>
                    <option value="">Select type…</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @selected(old('hunting_type_id')==$type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('hunting_type_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-300 mb-1">Title (optional)</label>
                <input name="title" value="{{ old('title') }}" class="w-full bg-gray-800 text-white rounded p-2">
                @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-300 mb-1">Image</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-gray-800 text-white rounded p-2" required>
                @error('image') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 bg-gray-700 rounded text-white">Cancel</a>
                <button class="px-4 py-2 bg-emerald-600 rounded text-white">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
