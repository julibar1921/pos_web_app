<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Rejoignez-nous</h1>
        <p class="text-gray-500 mt-1 font-medium">Créez votre compte administrateur</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Nom Complet</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-user"></i>
                </div>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-gray-700 placeholder-gray-400"
                    placeholder="Votre nom">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-envelope"></i>
                </div>
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-gray-700 placeholder-gray-400"
                    placeholder="votre@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Mot de passe</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-lock"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-gray-700 placeholder-gray-400"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Confirmer</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-transparent rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-gray-700 placeholder-gray-400"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all duration-300">
                CRÉER MON COMPTE
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-400 font-medium">
                Déjà inscrit ? 
                <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Se connecter</a>
            </p>
        </div>
    </form>
</x-guest-layout>
