<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-black-200 mb-6">Admin Dashboard</h1>
        <p class="text-black-400 mb-8">Welcome, {{ Auth::user()->name }}! Manage the system here.</p>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-200 mb-2">Gallery Items</h2>
                <p class="text-gray-400">Total: {{ $galleryCount }}</p>
                <a href="{{ route('admin.gallery.index') }}" class="text-emerald-500">Manage Gallery</a>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-200 mb-2">Hunting Types</h2>
                <p class="text-gray-400">Total: {{ $huntingTypeCount }}</p>
                <a href="{{ route('admin.hunting-types.index') }}" class="text-emerald-500">Manage Types</a>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-200 mb-2">Messages</h2>
                <p class="text-gray-400">Total: {{ $messageCount }}</p>
                <a href="{{ route('admin.messages.index') }}" class="text-emerald-500">View All Messages</a>
            </div>
        </div>

        <!-- Users -->
        <div class="bg-gray-800 rounded shadow-md overflow-hidden mb-10">
            <h2 class="text-xl font-semibold text-gray-200 p-4 border-b border-gray-700">All Users</h2>
            <table class="w-full text-left">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-white">Name</th>
                        <th class="px-4 py-2 text-white">Email</th>
                        <th class="px-4 py-2 text-white">Role</th>
                        <th class="px-4 py-2 text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-gray-700">
                        <td class="px-4 py-2 text-white">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-white">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-white">
                            {{ $user->is_admin ? 'Admin' : ($user->is_leader ? 'Leader' : 'User') }}
                        </td>
                        <td class="px-4 py-2 flex space-x-2">
                            @if(!$user->is_leader)
                            <form action="{{ route('admin.users.makeLeader', $user) }}" method="POST">
                                @csrf
                                <button class="px-2 py-1 bg-emerald-500 text-white rounded hover:bg-emerald-600">Make Leader</button>
                            </form>
                            @else
                            <form action="{{ route('admin.users.removeLeader', $user) }}" method="POST">
                                @csrf
                                <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Remove Leader</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Recent Messages -->
        <div class="bg-gray-800 rounded shadow-md overflow-hidden">
            <h2 class="text-xl font-semibold text-gray-200 p-4 border-b border-gray-700">Recent Messages</h2>
            <table class="w-full text-left">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-white">Name</th>
                        <th class="px-4 py-2 text-white">Email</th>
                        <th class="px-4 py-2 text-white">Message</th>
                        <th class="px-4 py-2 text-white">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                    <tr class="border-b border-gray-700">
                        <td class="px-4 py-2 text-white">{{ $msg->name }}</td>
                        <td class="px-4 py-2 text-white">{{ $msg->email }}</td>
                        <td class="px-4 py-2 text-white">{{ Str::limit($msg->message, 50) }}</td>
                        <td class="px-4 py-2 text-white">{{ $msg->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-gray-400">No messages yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
