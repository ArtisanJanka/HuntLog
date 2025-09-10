<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Contact Us') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-gray-800 shadow-lg sm:rounded-lg p-8 text-center">
                <h3 class="text-2xl font-bold text-emerald-400 mb-6">Get in Touch</h3>

                <div class="space-y-4 text-gray-300">
                    <p class="flex items-center justify-center space-x-2">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.518 4.553a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.553 1.518a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="font-semibold">29490737</span>
                    </p>

                    <p class="flex items-center justify-center space-x-2">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 12H8m0 0l-4-4m4 4l-4 4m12-4h4"/>
                        </svg>
                        <span class="font-semibold">huntlogs@gmail.com</span>
                    </p>
                </div>
            </div>

            <div class="bg-gray-800 shadow-lg sm:rounded-lg p-8">
                <h3 class="text-xl font-bold text-emerald-400 mb-6">Send us a Message</h3>

               
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
                        <button type="submit"
                                class="px-6 py-2 bg-emerald-600 text-white rounded-md font-semibold hover:bg-emerald-700 transition">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
