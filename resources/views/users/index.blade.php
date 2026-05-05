<x-app-layout>
    <x-slot name="header">
        Gestion des Utilisateurs
    </x-slot>

    <div class="modern-card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des Utilisateurs</h2>
            <a href="{{ route('users.create') }}" class="modern-btn">
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
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôles</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td class="font-medium text-gray-900">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $v }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <a class="text-indigo-600 hover:text-indigo-900 mr-3" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit"></i> Modifier</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"><i class="fa fa-trash"></i> Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
