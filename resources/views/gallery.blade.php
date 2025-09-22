<x-app-layout>
    <section class="bg-gray-900 min-h-screen py-6">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            

            @php
                $items = \App\Models\GalleryItem::with('huntingType')->latest()->get();
            @endphp

            @if($items->isEmpty())
                <p class="text-gray-400 text-center">Nav attēlu galerijā.</p>
            @else
                <div class="mx-auto columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6" style="column-gap: 1.5rem;">
                    @foreach ($items as $item)
                        <div class="break-inside-avoid mb-6 rounded-lg overflow-hidden relative group shadow-lg">
                            <img
                                src="{{ $item->url() }}"
                                alt="{{ $item->title ?? $item->huntingType->name }}"
                                class="w-full object-cover rounded transition-transform duration-500 transform group-hover:scale-105"
                            >

                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                <form action="{{ route('join-group.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hunting_type_id" value="{{ $item->huntingType->id }}">
                                    <button type="submit"
                                        class="px-4 py-2 bg-emerald-500 text-white font-semibold rounded hover:bg-emerald-600">
                                        Pievienojies {{ $item->huntingType->name }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
