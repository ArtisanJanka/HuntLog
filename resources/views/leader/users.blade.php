<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-white mb-4">Your Users</h1>

        <a href="#" onclick="document.getElementById('addUserForm').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-500 text-white rounded mb-4 inline-block">Add User</a>

        <div id="addUserForm" class="hidden mb-6 bg-gray-800 p-6 rounded shadow-md">
            <form action="{{ route('leader.users.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Name" class="w-full mb-2 p-2 rounded bg-gray-700 text-white" required>
                <input type="email" name="email" placeholder="Email" class="w-full mb-2 p-2 rounded bg-gray-700 text-white" required>
                <input type="password" name="password" placeholder="Password" class="w-full mb-2 p-2 rounded bg-gray-700 text-white" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full mb-2 p-2 rounded bg-gray-700 text-white" required>
                <button class="px-4 py-2 bg-emerald-600 rounded text-white">Add User</button>
            </form>
        </div>

        <table class="w-full text-left bg-gray-800 rounded shadow-md overflow-hidden">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-white">Name</th>
                    <th class="px-4 py-2 text-white">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-gray-700">
                    <td class="px-4 py-2 text-gray-200">{{ $user->name }}</td>
                    <td class="px-4 py-2 text-gray-200">{{ $user->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
