<x-app-layout>
    <div class="max-w-md mx-auto p-6">
        <h1 class="text-2xl font-semibold text-black mb-4">Create Type</h1>
        <form method="POST" action="{{ route('admin.hunting-types.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-black-300 mb-1">Name</label>
                <input name="name" class="w-full bg-gray-800 text-white rounded p-2" required>
                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-black-300 mb-1">Slug (optional)</label>
                <input name="slug" class="w-full bg-gray-800 text-white rounded p-2">
                @error('slug') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.hunting-types.index') }}" class="px-4 py-2 bg-gray-700 rounded text-white">Cancel</a>
                <button class="px-4 py-2 bg-emerald-600 rounded text-white">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
