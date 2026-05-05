<x-app-layout>
    <x-slot name="header">
        Modifier l'Utilisateur
    </x-slot>

    <div class="modern-card max-w-2xl mx-auto">
        @if (count($errors) > 0)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <strong>Oups!</strong> Il y a des problèmes avec vos entrées.<br><br>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            
            <div class="modern-input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="name" value="{{ $user->name }}" placeholder="Nom" required>
            </div>

            <div class="modern-input-group">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" value="{{ $user->email }}" placeholder="Email" required>
            </div>

            <div class="modern-input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Nouveau Mot de passe (Laisser vide si inchangé)">
            </div>

            <div class="mb-6">
                <strong class="text-gray-700 mb-2 block">Rôles:</strong>
                <select name="roles[]" multiple class="w-full bg-gray-50 border border-gray-200 text-gray-900 p-2 rounded-lg outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition h-32">
                    @foreach($roles as $role)
                        <option value="{{ $role }}" class="p-2 hover:bg-indigo-50" {{ in_array($role, $userRole) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-between items-center mt-8">
                <a class="text-gray-500 hover:text-gray-800 transition" href="{{ route('users.index') }}">
                    <i class="fa fa-arrow-left mr-1"></i> Retour
                </a>
                <button type="submit" class="modern-btn">
                    <i class="fa fa-save mr-2"></i> Soumettre
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
