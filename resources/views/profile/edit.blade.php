<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Profile Information --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-6">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            {{-- User Waypoints --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('Your Waypoints') }}</h2>
                <ul class="space-y-2">
                    @forelse($waypoints as $waypoint)
                        <li class="bg-gray-100 p-3 rounded flex justify-between items-center">
                            <div>
                                <strong>{{ $waypoint->name }}</strong>
                                <span class="text-sm text-gray-600">
                                    ({{ $waypoint->latitude }}, {{ $waypoint->longitude }})
                                </span>
                                <p class="text-gray-700">{{ $waypoint->description }}</p>
                            </div>
                            <a href="{{ route('map.show', $waypoint->id) }}" class="text-indigo-600 hover:underline">
                                View on Map
                            </a>
                        </li>
                    @empty
                        <li class="text-gray-500">No waypoints yet.</li>
                    @endforelse
                </ul>
            </div>

            {{-- User Polygons --}}
            
        </div>
    </div>
</x-app-layout>
