{{-- resources/views/join.blade.php --}}

<x-app-layout>
    <div class="bg-gray-900 min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-emerald-400">Pievienoties medību grupām</h1>
                <p class="mt-3 text-lg text-gray-300">Izvēlies medību veidu un reģistrējies grupā (pagaidām tikai testa režīmā)</p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                
                <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700 hover:border-emerald-500 transition">
                    <h2 class="text-2xl font-bold text-white mb-4">Staltbriežu medības</h2>
                    <p class="text-gray-400 mb-6">Pievienojies staltbriežu medību grupai un satiec citus medniekus.</p>
                    <form action="#" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="name" placeholder="Vārds" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="email" name="email" placeholder="E-pasts" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="submit"
                                class="w-full bg-emerald-600 text-white font-semibold py-2 rounded-lg hover:bg-emerald-700 transition">
                            Pieteikties
                        </button>
                    </form>
                </div>

                <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700 hover:border-emerald-500 transition">
                    <h2 class="text-2xl font-bold text-white mb-4">Mežacūku medības</h2>
                    <p class="text-gray-400 mb-6">Pagaidu pieteikšanās mežacūku medību grupai.</p>
                    <form action="#" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="name" placeholder="Vārds" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="email" name="email" placeholder="E-pasts" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="submit"
                                class="w-full bg-emerald-600 text-white font-semibold py-2 rounded-lg hover:bg-emerald-700 transition">
                            Pieteikties
                        </button>
                    </form>
                </div>

                <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700 hover:border-emerald-500 transition">
                    <h2 class="text-2xl font-bold text-white mb-4">Pīļu medības</h2>
                    <p class="text-gray-400 mb-6">Pievienojies pīļu medību entuziastiem.</p>
                    <form action="#" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="name" placeholder="Vārds" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="email" name="email" placeholder="E-pasts" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="submit"
                                class="w-full bg-emerald-600 text-white font-semibold py-2 rounded-lg hover:bg-emerald-700 transition">
                            Pieteikties
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
