<x-app-layout>
    <x-slot name="header">
        Modifier le Rôle
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

        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf
            @method('PUT')
            
            <div class="modern-input-group">
                <i class="fa fa-tag"></i>
                <input type="text" name="name" value="{{ $role->name }}" placeholder="Nom du Rôle" required>
            </div>

            <div class="mb-6">
                <strong class="text-gray-700 mb-4 block">Permissions:</strong>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-gray-50 p-4 rounded-lg border border-gray-200 max-h-64 overflow-y-auto">
                    @foreach($permission as $value)
                        <label class="inline-flex items-center cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                            <input type="checkbox" name="permission[]" value="{{ $value->id }}" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-3">
                            <span class="text-gray-700 select-none">{{ $value->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between items-center mt-8">
                <a class="text-gray-500 hover:text-gray-800 transition" href="{{ route('roles.index') }}">
                    <i class="fa fa-arrow-left mr-1"></i> Retour
                </a>
                <button type="submit" class="modern-btn">
                    <i class="fa fa-save mr-2"></i> Soumettre
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
