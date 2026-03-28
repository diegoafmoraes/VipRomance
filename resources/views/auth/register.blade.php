<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white/90 backdrop-blur rounded-2xl shadow-xl border border-rose-100 px-6 py-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-extrabold text-rose-600">
                    Criar conta 💘
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    Entre no VipRomance e comece sua jornada.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Nome --}}
                <div>
                    <x-input-label for="name" value="Seu nome" />
                    <x-text-input
                        id="name"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-rose-400 focus:ring-rose-400"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- Username --}}
                <div>
                    <x-input-label for="username" value="Nome de usuário" />
                    <x-text-input
                        id="username"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-rose-400 focus:ring-rose-400"
                        type="text"
                        name="username"
                        :value="old('username')"
                        required
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-500">Ex: Matrix_22</p>
                </div>

                {{-- Email --}}
                <div>
                    <x-input-label for="email" value="E-mail" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-rose-400 focus:ring-rose-400"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Sexo / Buscando --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="sex" value="Eu sou" />
                        <select
                            id="sex"
                            name="sex"
                            class="block mt-1 w-full rounded-xl border-gray-300 focus:border-rose-400 focus:ring-rose-400"
                            required>
                            <option value="">Selecione</option>
                            <option value="M" @selected(old('sex') === 'M')>Homem</option>
                            <option value="F" @selected(old('sex') === 'F')>Mulher</option>
                        </select>
                        <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                    </div>

                        <select
                            id="seeking"
                            name="seeking"
                            class="block mt-1 w-full rounded-xl border-gray-300 focus:border-rose-400 focus:ring-rose-400"
                            required>
                            <option value="">Selecione</option>
                            <option value="M" @selected(old('seeking') === 'M')>Homem</option>
                            <option value="F" @selected(old('seeking') === 'F')>Mulher</option>
                        </select>
                        <x-input-error :messages="$errors->get('seeking')" class="mt-2" />
                    </div>
                </div>

                {{-- Senha --}}
                <div>
                    <x-input-label for="password" value="Senha" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-rose-400 focus:ring-rose-400"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Confirmar senha --}}
                <div>
                    <x-input-label for="password_confirmation" value="Confirmar senha" />
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-rose-400 focus:ring-rose-400"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full justify-center rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 border-0 shadow hover:opacity-90">
                        Criar conta
                    </x-primary-button>
                </div>

                <div class="text-center text-sm text-gray-600 pt-2">
                    Já tem conta?
                    <a href="{{ route('login') }}"
                       class="font-semibold text-rose-600 hover:text-rose-700 underline">
                        Entrar agora
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>