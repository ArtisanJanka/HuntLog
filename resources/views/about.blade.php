<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-3xl text-green-900 tracking-wide">
            {{ __('About Us') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 text-white-100">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-gray-800 shadow-lg rounded-lg p-8">
                <h3 class="text-2xl font-semibold mb-4 text-green-400">Our Story</h3>
                <p class="mb-6 text-lg leading-relaxed">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequuntur suscipit enim, sint quaerat repellendus ea dolores accusamus sit similique ratione exercitationem rerum iste, explicabo aperiam esse quia delectus hic placeat.
                </p>

                <h3 class="text-2xl font-semibold mb-4 text-green-400">Our Mission</h3>
                <p class="mb-6 text-lg leading-relaxed">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ducimus animi quibusdam aliquam sed aspernatur quae ea ipsum sit explicabo alias consequatur, assumenda laudantium recusandae sint quisquam rerum minus voluptatem ad.
                </p>

                <h3 class="text-2xl font-semibold mb-4 text-green-400">Our Values</h3>
                <ul class="list-disc list-inside space-y-2 text-lg">
                    <li>ssss</li>
                    <li>bbbbb</li>
                    <li>vvvvv</li>
                    <li>aaaaa</li>
                </ul>
            </div>
        </div>
    </div>
    
    <footer class="mt-12 bg-gray-800 text-gray-300 py-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center px-6">
            <p class="text-sm">&copy; {{ date('Y') }} Hunt</p>
        </div>
    </footer>
</x-app-layout>