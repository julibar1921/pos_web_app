<x-app-layout>
    <x-slot name="header">
        Gestion des Permissions
    </x-slot>

    <div class="modern-card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des Permissions</h2>
            <a href="{{ route('permissions.create') }}" class="modern-btn">
                <i class="fa fa-plus mr-2"></i> Ajouter
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom de la Permission</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $key => $permission)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td class="font-medium text-gray-900">{{ $permission->name }}</td>
                            <td>
                                <a class="text-indigo-600 hover:text-indigo-900 mr-3" href="{{ route('permissions.edit',$permission->id) }}"><i class="fa fa-edit"></i> Modifier</a>
                                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette permission ?')"><i class="fa fa-trash"></i> Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
