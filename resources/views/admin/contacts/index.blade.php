<!-- resources/views/admin/contacts/index.blade.php -->
<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-emerald-400 mb-6">Contact Messages</h1>

        <div class="bg-gray-800 rounded-xl shadow p-6">
            @if($messages->isEmpty())
                <p class="text-gray-400">No messages yet.</p>
            @else
                <table class="min-w-full text-gray-200">
                    <thead>
                        <tr class="text-left border-b border-gray-700">
                            <th class="py-2 px-3">Name</th>
                            <th class="py-2 px-3">Email</th>
                            <th class="py-2 px-3">Message</th>
                            <th class="py-2 px-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $msg)
                            <tr class="border-b border-gray-700">
                                <td class="py-2 px-3 align-top">{{ $msg->name }}</td>
                                <td class="py-2 px-3 align-top">{{ $msg->email }}</td>
                                <td class="py-2 px-3 align-top">{{ $msg->message }}</td>
                                <td class="py-2 px-3 align-top">{{ $msg->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
