<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-500 rounded-lg text-white">
                    <i class="fas fa-tags text-xl"></i>
                </div>
                <span>{{ __('Gestion des Catégories') }}</span>
            </div>
            @can('create categories')
            <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg shadow-indigo-200">
                <i class="fas fa-plus mr-2"></i> Nouvelle Catégorie
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Couleur</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Produits</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-lg shadow-sm" style="background-color: {{ $category->color }}"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-800">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 max-w-xs truncate">{{ $category->description ?? 'Aucune description' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-indigo-50 text-indigo-600">
                                    {{ $category->products_count }} produits
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    @can('edit categories')
                                    <a href="{{ route('categories.edit', $category) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete categories')
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-4">
                                        <i class="fas fa-folder-open text-4xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-400">Aucune catégorie trouvée</h3>
                                    <p class="text-gray-400">Commencez par créer votre première catégorie de produits.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
