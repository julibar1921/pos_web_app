<x-app-layout>
    <x-slot name="header">
        Modifier la Permission
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

        <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
            @csrf
            @method('PUT')
            
            <div class="glass-input-group">
                <i class="fa fa-key"></i>
                <input type="text" name="name" value="{{ $permission->name }}" placeholder="Nom de la Permission" required>
            </div>

            <div class="text-center">
                <button type="submit" class="glass-btn">Soumettre</button>
                <a class="text-gray-300 mt-4 block underline hover:text-white" href="{{ route('permissions.index') }}"> Retour</a>
            </div>
        </form>
    </div>
</x-app-layout>
