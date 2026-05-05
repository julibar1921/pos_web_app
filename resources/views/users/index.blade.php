<x-app-layout>
    <x-slot name="header">
        Gestion des Utilisateurs
    </x-slot>

    <div class="glass-card p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl text-white">Liste des Utilisateurs</h2>
            <a href="{{ route('users.create') }}" class="glass-btn text-center" style="width: auto; padding: 8px 16px;">
                <i class="fa fa-plus"></i> Ajouter
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-500 bg-opacity-50 text-white p-4 rounded mb-4">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="glass-table">
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
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                        <span class="bg-indigo-500 text-xs px-2 py-1 rounded">{{ $v }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <a class="text-blue-300 hover:text-white mr-2" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit"></i> Modifier</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-300 hover:text-white" onclick="return confirm('Êtes-vous sûr ?')"><i class="fa fa-trash"></i> Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
