<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Bonjour !</h1>
        <p class="text-gray-500 mt-1 font-medium">Connectez-vous à votre espace gestion</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-envelope"></i>
                </div>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-gray-700 placeholder-gray-400"
                    placeholder="votre@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between ml-1 mb-2">
                <label for="password" class="text-xs font-black text-gray-400 uppercase tracking-widest block">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition" href="{{ route('password.request') }}">
                        Oublié ?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-lock"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-gray-700 placeholder-gray-400"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center ml-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5 transition-all cursor-pointer" name="remember">
                <span class="ms-3 text-sm font-bold text-gray-600">{{ __('Rester connecté') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                SE CONNECTER
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-sm text-gray-400 font-medium">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">S'inscrire</a>
            </p>
        </div>
    </form>
</x-guest-layout>
