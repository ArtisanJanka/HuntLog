<x-app-layout>
    <div class="relative py-16 min-h-screen bg-gray-900">

        {{-- Background image --}}
        <div class="absolute inset-0">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/94/Eurasian_brown_bear_%28Ursus_arctos_arctos%29_female_1.jpg" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-70"></div> {{-- dark overlay --}}
        </div>

        <div class="relative max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-12 z-10">

            <div class="bg-gray-800 bg-opacity-90 shadow-xl sm:rounded-2xl p-10 text-center">
                <h1 class="text-4xl font-extrabold text-emerald-400 mb-6">Get in Touch</h1>
                <p class="text-gray-300 mb-8">Reach out to us directly or send us a message using the form below.</p>

                <div class="flex flex-col sm:flex-row justify-center gap-10 text-gray-300">
                    <p class="flex items-center space-x-3">
                        <!-- phone icon -->
                        <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.518 4.553a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.553 1.518a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="font-semibold text-lg">29490737</span>
                    </p>

                    <p class="flex items-center space-x-3">
                        <!-- email icon -->
                        <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 8l7.5 5L18 8m-15 8h18V6H3v10z"/>
                        </svg>
                        <span class="font-semibold text-lg">huntlogs@gmail.com</span>
                    </p>
                </div>
            </div>

            <div class="bg-gray-800 bg-opacity-90 shadow-xl sm:rounded-2xl p-10">
                <h2 class="text-2xl font-bold text-emerald-400 mb-6 text-center">Send us a Message</h2>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-emerald-600 text-white rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contacts.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm text-white focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm text-white focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-300">Message</label>
                        <textarea name="message" id="message" rows="5" required
                                  class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm text-white focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
