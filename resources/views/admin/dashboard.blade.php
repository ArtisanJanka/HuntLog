<x-app-layout>
    <div class="min-h-screen bg-gray-900 text-gray-200 px-6 py-10 animate-fade-up">
        <header class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold text-emerald-400 mb-2">Admin Dashboard</h1>
            <p class="text-gray-400 text-lg">Welcome, {{ Auth::user()->name }}! Manage the system here.</p>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @php
                $cards = [
                    ['title'=>'Gallery Items','count'=>$galleryCount,'link'=>route('admin.gallery.index')],
                    ['title'=>'Hunting Types','count'=>$huntingTypeCount,'link'=>route('admin.hunting-types.index')],
                    ['title'=>'Messages','count'=>$messageCount,'link'=>route('admin.messages.index')],
                ];
            @endphp

            @foreach($cards as $card)
            <div class="bg-gray-800 p-6 rounded-2xl shadow-md transform transition duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/50">
                <div class="h-1 w-full rounded-t bg-gradient-to-r from-emerald-400 to-emerald-600 mb-4"></div>
                <h2 class="text-xl font-semibold text-gray-200 mb-2">{{ $card['title'] }}</h2>
                <p class="text-gray-400 mb-4">Total: {{ $card['count'] }}</p>
                <a href="{{ $card['link'] }}" class="text-emerald-400 hover:text-emerald-500 underline transition duration-200">Manage</a>
            </div>
            @endforeach
        </div>

        <!-- Users Table -->
        <div class="bg-gray-800 rounded-2xl shadow-md overflow-hidden mb-10 transform transition duration-500 hover:shadow-2xl hover:shadow-emerald-500/30">
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
                    <tr class="border-b border-gray-700 animate-fade-up hover:bg-gray-700 transition">
                        <td class="px-4 py-2 text-gray-200">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-gray-200">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-gray-200">
                            {{ $user->is_admin ? 'Admin' : ($user->is_leader ? 'Leader' : 'User') }}
                        </td>
                        <td class="px-4 py-2 flex space-x-2">
                            @if(!$user->is_leader)
                            <form action="{{ route('admin.users.makeLeader', $user) }}" method="POST">
                                @csrf
                                <button class="px-3 py-1 bg-emerald-400 text-gray-900 rounded-full hover:bg-emerald-500 hover:shadow-lg hover:shadow-emerald-500/50 transition duration-300">
                                    Make Leader
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.users.removeLeader', $user) }}" method="POST">
                                @csrf
                                <button class="px-3 py-1 bg-red-500 text-gray-900 rounded-full hover:bg-red-600 hover:shadow-lg hover:shadow-red-500/50 transition duration-300">
                                    Remove Leader
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Recent Messages -->
        <div class="bg-gray-800 rounded-2xl shadow-md overflow-hidden transform transition duration-500 hover:shadow-2xl hover:shadow-emerald-500/30">
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
                    <tr class="border-b border-gray-700 animate-fade-up hover:bg-gray-700 transition">
                        <td class="px-4 py-2 text-gray-200">{{ $msg->name }}</td>
                        <td class="px-4 py-2 text-gray-200">{{ $msg->email }}</td>
                        <td class="px-4 py-2 text-gray-200">{{ Str::limit($msg->message, 50) }}</td>
                        <td class="px-4 py-2 text-gray-200">{{ $msg->created_at->format('d M Y H:i') }}</td>
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

    {{-- Add animation CSS --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeInUp 0.5s ease forwards;
        }
    </style>
</x-app-layout>
