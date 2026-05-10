<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-rose-500 rounded-lg text-white">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <span>{{ __('Gestion des Dépenses') }}</span>
            </div>
            <div class="flex gap-3">
                <button @click="$dispatch('open-modal', 'add-category')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150 shadow-sm">
                    <i class="fas fa-folder-plus mr-2 text-rose-500"></i> Nouvelle Catégorie
                </button>
                <button @click="$dispatch('open-modal', 'add-expense')" class="inline-flex items-center px-4 py-2 bg-rose-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:ring ring-rose-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg shadow-rose-200">
                    <i class="fas fa-plus mr-2"></i> Nouvelle Dépense
                </button>
            </div>
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

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($expense->entry_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-bold rounded-full uppercase">
                                    {{ $expense->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-800">{{ $expense->description ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-rose-600">
                                    {{ number_format($expense->amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @can('delete expenses')
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Supprimer cette dépense ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-file-invoice-dollar text-4xl mb-4"></i>
                                <p class="font-bold">Aucune dépense enregistrée</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Nouvelle Catégorie -->
    <x-modal name="add-category" focusable>
        <form method="post" action="{{ route('expense-categories.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-black text-gray-800 mb-6">Nouvelle Catégorie</h2>
            <div>
                <x-input-label for="cat_name" value="Nom de la catégorie" />
                <x-text-input id="cat_name" name="name" type="text" class="mt-1 block w-full" required placeholder="Ex: Loyer, Électricité, Achats..." />
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-rose-700">Enregistrer</button>
            </div>
        </form>
    </x-modal>

    <!-- Modal: Nouvelle Dépense -->
    <x-modal name="add-expense" focusable>
        <form method="post" action="{{ route('expenses.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-black text-gray-800 mb-6">Enregistrer une Dépense</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="expense_category_id" value="Catégorie" />
                    <select name="expense_category_id" required class="mt-1 block w-full rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500 shadow-sm">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="amount" value="Montant" />
                    <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="entry_date" value="Date" />
                    <x-text-input id="entry_date" name="entry_date" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full" required />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="description" value="Description" />
                    <textarea name="description" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500 shadow-sm"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-rose-700">Enregistrer la dépense</button>
            </div>
        </form>
    </x-modal>

</x-app-layout>
