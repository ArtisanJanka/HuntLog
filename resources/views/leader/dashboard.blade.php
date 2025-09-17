<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="{ tab: 'users' }">
        <h1 class="text-3xl font-bold mb-6">Leader Dashboard</h1>

        <div class="flex space-x-4 mb-6">
            <button @click="tab = 'users'" 
                    :class="tab === 'users' ? 'bg-emerald-600 text-white' : 'bg-gray-700 text-gray-200'"
                    class="px-4 py-2 rounded font-semibold transition">Your Users</button>

            <button @click="tab = 'requests'" 
                    :class="tab === 'requests' ? 'bg-emerald-600 text-white' : 'bg-gray-700 text-gray-200'"
                    class="px-4 py-2 rounded font-semibold transition">Pending Requests</button>
        </div>

        <div x-show="tab === 'users'" class="space-y-6">
            <div class="bg-gray-800 p-6 rounded shadow-md">
                <h2 class="text-xl font-semibold text-white mb-4">Add New User</h2>
                <form action="{{ route('leader.users.store') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="text" name="name" placeholder="Name" class="w-full p-2 rounded bg-gray-700 text-white" required>
                    <input type="email" name="email" placeholder="Email" class="w-full p-2 rounded bg-gray-700 text-white" required>
                    <input type="password" name="password" placeholder="Password" class="w-full p-2 rounded bg-gray-700 text-white" required>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full p-2 rounded bg-gray-700 text-white" required>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded mt-2">Add User</button>
                </form>
            </div>

            <div class="bg-gray-800 rounded shadow-md overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-white">Name</th>
                            <th class="px-4 py-2 text-white">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-gray-200">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-gray-200">{{ $user->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-gray-400 text-center">No users yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="tab === 'requests'" class="space-y-4">
            @forelse($requests as $user)
                <div class="bg-gray-800 p-4 rounded flex justify-between items-center">
                    <div>
                        <div class="text-white font-semibold">{{ $user->name }}</div>
                        <div class="text-gray-400 text-sm">{{ $user->email }}</div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('leader.requests.approve', $user) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-emerald-600 text-white rounded">Approve</button>
                        </form>
                        <form action="{{ route('leader.requests.reject', $user) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-gray-400 text-center">No pending requests</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
