<x-guest-layout>
    <form method="POST" action="{{ route('2fa') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="google2fa_secret" :value="__('Enter Two Factor Code')" />
            <x-text-input id="google2fa_secret" class="block mt-4 w-full" type="text" name="google2fa_secret" :value="old('google2fa_secret')"  autofocus />
            <x-input-error :messages="$errors->get('google2fa_secret')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ml-3">
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
