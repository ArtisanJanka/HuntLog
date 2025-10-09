<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
    @csrf
    @method('patch')

    <div>
        <x-input-label for="name" :value="__('Vārds')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="$user->name" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" :value="__('E-Pasts')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="$user->email" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Saglabāt') }}</x-primary-button>
    </div>
</form>
