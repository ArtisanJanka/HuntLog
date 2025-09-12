<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-gray-900 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-white mb-6 border-b border-gray-700 pb-3">Edit Gallery Image</h1>

        <form method="POST" action="{{ route('admin.gallery.update', $item) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Hunting Type --}}
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Hunting Type</label>
                <select name="hunting_type_id" class="w-full bg-gray-800 text-white rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500 border border-gray-700">
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @selected(old('hunting_type_id', $item->hunting_type_id)==$type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('hunting_type_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Title (optional)</label>
                <input name="title" value="{{ old('title', $item->title) }}" class="w-full bg-gray-800 text-white rounded-lg p-3 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500">
                @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Image Upload --}}
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Replace Image (optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-gray-800 text-white rounded-lg p-3 border border-gray-700 focus:ring-emerald-500 focus:border-emerald-500">
                @error('image') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror

                <div class="mt-4">
                    <p class="text-gray-400 mb-2 font-medium">Current Image:</p>
                    <img src="{{ $item->url() }}" alt="Current Image" class="w-full rounded-lg shadow-md object-cover max-h-64">
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.gallery.index') }}" class="px-5 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
