<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="glass-avatar">
        <i class="fa fa-user"></i>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="glass-input-group">
            <i class="fa fa-envelope"></i>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Adresse Email">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="glass-input-group mt-4">
            <i class="fa fa-lock"></i>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Mot de passe">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="options">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded bg-transparent border-gray-400">
                <span class="ms-2">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline hover:text-white" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button type="submit" class="glass-btn">
            SE CONNECTER
        </button>
    </form>
</x-guest-layout>
