<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">

        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Profile Information') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </header>

        {{-- Include the update form --}}
        @include('profile.partials.update-profile-information-form', ['user' => $user])

        {{-- Include password update form --}}
        @include('profile.partials.update-password-form', ['user' => $user])

        {{-- User Polygons List --}}
        <section class="mt-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Jūsu poligoni</h2>

            @if($polygons->isEmpty())
                <p class="text-gray-600">Jums vēl nav saglabātu poligonu.</p>
            @else
                <ul class="space-y-2">
                    @foreach($polygons as $polygon)
                        <li class="bg-gray-200 text-gray-900 p-3 rounded">
                            <span class="font-semibold">{{ $polygon->name }}</span>
                            {{-- Optional: show coordinates as JSON --}}
                            <div class="text-sm text-gray-700 mt-1">
                                {{ $polygon->coordinates }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
