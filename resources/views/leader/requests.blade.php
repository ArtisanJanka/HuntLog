<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-emerald-400 mb-8 text-center">Pending Group Requests</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($requests as $request)
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition duration-300">
                <div class="p-4 space-y-2">
                    <div class="text-white font-bold text-lg">{{ $request->user->name }}</div>
                    <div class="text-gray-400 text-sm">{{ $request->user->email }}</div>
                    <div class="text-gray-300 text-sm">Hunting Type: {{ $request->huntingType->name ?? 'N/A' }}</div>
                </div>
                <div class="bg-gray-900 p-3 flex justify-between items-center">
                    <form action="{{ route('leader.requests.approve', $request) }}" method="POST" class="flex-1 mr-1">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('leader.requests.reject', $request) }}" method="POST" class="flex-1 ml-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($requests->isEmpty())
                <div class="col-span-full text-center text-gray-400 mt-6">
                    No pending group requests at the moment.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
