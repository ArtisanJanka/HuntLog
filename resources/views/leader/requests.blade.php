<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-white mb-4">Pending Requests</h1>

        @foreach($requests as $user)
        <div class="bg-gray-800 p-4 rounded mb-2 flex justify-between items-center">
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
        @endforeach
    </div>
</x-app-layout>
