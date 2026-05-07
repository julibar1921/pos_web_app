<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-600 rounded-lg text-white">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <span>{{ __('Gestion des Utilisateurs') }}</span>
            </div>
            <button @click="$dispatch('open-modal', 'add-user')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                <i class="fas fa-plus mr-2"></i> Nouvel Utilisateur
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-100 border-l-4 border-rose-500 text-rose-700 rounded-r-lg shadow-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Utilisateur</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Rôle</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-bold text-gray-800">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($user->roles as $role)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $role->name === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="$dispatch('open-modal', 'edit-user-{{ $user->id }}')" class="p-2 text-gray-400 hover:text-indigo-600">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-rose-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>

                                <!-- Modal Edition -->
                                <x-modal name="edit-user-{{ $user->id }}" focusable>
                                    <form method="post" action="{{ route('users.update', $user) }}" class="p-8 text-left">
                                        @csrf @method('PUT')
                                        <h2 class="text-2xl font-black text-gray-800 mb-6">Modifier Utilisateur</h2>
                                        <div class="space-y-4">
                                            <div>
                                                <x-input-label value="Nom" />
                                                <x-text-input name="name" type="text" :value="$user->name" class="mt-1 block w-full" required />
                                            </div>
                                            <div>
                                                <x-input-label value="Email" />
                                                <x-text-input name="email" type="email" :value="$user->email" class="mt-1 block w-full" required />
                                            </div>
                                            <div>
                                                <x-input-label value="Rôle" />
                                                <select name="role" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm font-bold">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ strtoupper($role->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label value="Nouveau Mot de passe (optionnel)" />
                                                <x-text-input name="password" type="password" class="mt-1 block w-full" />
                                            </div>
                                        </div>
                                        <div class="mt-8 flex justify-end gap-3">
                                            <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700">Enregistrer</button>
                                        </div>
                                    </form>
                                </x-modal>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Ajout -->
    <x-modal name="add-user" focusable>
        <form method="post" action="{{ route('users.store') }}" class="p-8 text-left">
            @csrf
            <h2 class="text-2xl font-black text-gray-800 mb-6">Nouvel Utilisateur</h2>
            <div class="space-y-4">
                <div>
                    <x-input-label value="Nom" />
                    <x-text-input name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label value="Email" />
                    <x-text-input name="email" type="email" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label value="Rôle" />
                    <select name="role" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm font-bold">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Mot de passe" />
                    <x-text-input name="password" type="password" class="mt-1 block w-full" required />
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700">Créer l'utilisateur</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
