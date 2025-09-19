<x-app-layout>
    <section class="py-16 bg-gray-900">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-600 text-white rounded">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-6 p-4 bg-red-600 text-white rounded">
                    {{ session('error') }}
                </div>
            @endif



            @php
                $items = \App\Models\GalleryItem::with('huntingType')->latest()->get();
            @endphp

            <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6">
                @foreach ($items as $item)
                    <div class="break-inside-avoid group relative overflow-hidden rounded-lg shadow-lg mb-6">
                        <img src="{{ $item->url() }}" alt="{{ $item->title ?? $item->huntingType->name }}"
                             class="w-full object-cover transform group-hover:scale-105 transition duration-500">

                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            {{-- Join form --}}
                            <form action="{{ route('join-group.store') }}" method="POST">

                                @csrf
                                <input type="hidden" name="hunting_type_id" value="{{ $item->huntingType->id }}">
                                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white font-semibold rounded hover:bg-emerald-600">
                                    Pievienojies {{ $item->huntingType->name }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
