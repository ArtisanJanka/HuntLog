<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-gray-100 min-h-screen flex flex-col justify-center items-center py-20 px-6">
        <div class="text-center max-w-3xl">
            <h1 class="text-6xl font-extrabold text-emerald-400 mb-6">
                Laipni lūdzam HuntLog
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Sekojiet līdzi savām medību aktivitātēm, pievienojieties grupām un dalieties ar citiem medniekiem.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('about') }}" 
                   class="px-8 py-4 bg-gray-700 text-gray-200 font-semibold rounded-lg shadow-md hover:bg-gray-600 transition">
                    Par mums
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-gray-900 text-gray-100 min-h-screen flex flex-col justify-center px-6">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-emerald-400 mb-12">Ko Tu vari darīt?</h2>
            <div class="grid gap-10 md:grid-cols-3">
                
                <div class="bg-gray-800 rounded-xl p-12 shadow-md hover:border-emerald-500 border border-gray-700 transition">
                    <h3 class="text-2xl font-semibold mb-3 text-white">📊 Sekot aktivitātēm</h3>
                    <p class="text-gray-400 text-lg">Izseko savus medību ierakstus un rezultātus ērtā veidā.</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-12 shadow-md hover:border-emerald-500 border border-gray-700 transition">
                    <h3 class="text-2xl font-semibold mb-3 text-white">🤝 Pievienoties grupām</h3>
                    <p class="text-gray-400 text-lg">Atrodi un pievienojies dažādām medību grupām pēc interesēm.</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-12 shadow-md hover:border-emerald-500 border border-gray-700 transition">
                    <h3 class="text-2xl font-semibold mb-3 text-white">📌 Dalīties pieredzē</h3>
                    <p class="text-gray-400 text-lg">Sazinies ar citiem medniekiem un dalies ar padomiem.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Call to Action Section with Background Image -->
    <section class="relative min-h-screen flex flex-col justify-center items-center px-6 text-gray-100">
        <!-- Background image -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1558022103-603c34ab10ce?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Zm9ycmVzdHxlbnwwfHwwfHx8MA%3D%3D" 
                 alt="Forest background" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gray-900/75"></div> <!-- Dark overlay for readability -->
        </div>

        <!-- Content -->
        <div class="relative max-w-3xl text-center">
            <h2 class="text-4xl font-bold text-emerald-400 mb-4">Sāc savu medību pieredzi ar HuntLog!</h2>
            <p class="text-lg text-gray-200 mb-8">Reģistrējies jau šodien un pievienojies medību entuziastu kopienai.</p>
            <a href="{{ route('register') }}" 
               class="px-8 py-4 bg-emerald-600 text-white font-semibold rounded-lg shadow-lg hover:bg-emerald-700 transition">
                Reģistrēties
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center px-6">
            <p>&copy; {{ date('Y') }} HuntLog. Visas tiesības aizsargātas.</p>
            <div class="flex space-x-4 mt-3 md:mt-0">
                <a href="{{ route('contacts') }}" class="hover:text-emerald-400">Kontakti</a>
                <a href="{{ route('about') }}" class="hover:text-emerald-400">Par mums</a>
            </div>
        </div>
    </footer>
</x-app-layout>