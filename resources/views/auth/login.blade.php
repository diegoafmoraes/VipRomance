<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Login (Email ou Username) -->
        <div>
            <x-input-label for="login" value="E-mail ou nome de usuário" />

            <x-text-input id="login"
                class="block mt-1 w-full"
                type="text"
                name="login"
                :value="old('login')"
                required autofocus autocomplete="username"
                placeholder="Digite seu e-mail ou username" />

            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Lembrar password') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                {{ __('Recuperar password?') }}
            </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Entrar') }}
            </x-primary-button>

            <div class="mt-4 ml-4 text-center text-sm text-gray-600">
                Ainda não tem conta?<br />

                <a href="{{ route('register') }}"
                    class="font-semibold text-rose-600 hover:text-rose-700 underline">
                    Criar conta 💘
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>