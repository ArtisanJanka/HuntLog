<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Hunting Types</h1>

        <a href="{{ route('admin.hunting-types.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded mb-4 inline-block">Add New Type</a>

        <table class="w-full text-left bg-gray-800 rounded overflow-hidden">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-white">Name</th>
                    <th class="px-4 py-2 text-white">Slug</th>
                    <th class="px-4 py-2 text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($types as $type)
                <tr class="border-b border-gray-700">
                    <td class="px-4 py-2 text-white">{{ $type->name }}</td>
                    <td class="px-4 py-2 text-gray-400">{{ $type->slug }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('admin.hunting-types.edit', $type) }}" class="px-2 py-1 bg-blue-500 text-white rounded">Edit</a>
                        <form action="{{ route('admin.hunting-types.destroy', $type) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-500 text-white rounded">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
