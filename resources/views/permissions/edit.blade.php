<x-app-layout>
    <x-slot name="header">
        Modifier la Permission
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

        <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
            @csrf
            @method('PUT')
            
            <div class="modern-input-group">
                <i class="fa fa-key"></i>
                <input type="text" name="name" value="{{ $permission->name }}" placeholder="Nom de la Permission" required>
            </div>

            <div class="flex justify-between items-center mt-8">
                <a class="text-gray-500 hover:text-gray-800 transition" href="{{ route('permissions.index') }}">
                    <i class="fa fa-arrow-left mr-1"></i> Retour
                </a>
                <button type="submit" class="modern-btn">
                    <i class="fa fa-save mr-2"></i> Soumettre
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
