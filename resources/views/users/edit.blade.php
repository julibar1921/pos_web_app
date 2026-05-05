<x-app-layout>
    <x-slot name="header">
        Modifier l'Utilisateur
    </x-slot>

    <div class="glass-card p-6 max-w-2xl mx-auto">
        @if (count($errors) > 0)
            <div class="bg-red-500 bg-opacity-50 text-white p-4 rounded mb-4">
                <strong>Oups!</strong> Il y a des problèmes avec vos entrées.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            
            <div class="glass-input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="name" value="{{ $user->name }}" placeholder="Nom" required>
            </div>

            <div class="glass-input-group">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" value="{{ $user->email }}" placeholder="Email" required>
            </div>

            <div class="glass-input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Nouveau Mot de passe (Laisser vide si inchangé)">
            </div>

            <div class="mb-6">
                <strong class="text-white mb-2 block">Rôle:</strong>
                <select name="roles[]" multiple class="w-full border-b border-white text-white p-2 outline-none" style="background: rgba(0,0,0,0.5);">
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ in_array($role, $userRole) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="glass-btn">Soumettre</button>
                <a class="text-gray-300 mt-4 block underline hover:text-white" href="{{ route('users.index') }}"> Retour</a>
            </div>
        </form>
    </div>
</x-app-layout>
