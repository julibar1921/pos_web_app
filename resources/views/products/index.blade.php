<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-500 rounded-lg text-white">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <span>{{ __('Catalogue des Produits') }}</span>
            </div>
            @can('create products')
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg shadow-emerald-200">
                <i class="fas fa-plus mr-2"></i> Nouveau Produit
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6" x-data="{}">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Prix de Vente</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden border border-gray-50">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <i class="fas fa-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-800">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $product->barcode ?? 'Pas de code-barres' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-bold rounded-full" style="background-color: {{ $product->category->color }}15; color: {{ $product->category->color }}">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-indigo-600">
                                    {{ number_format($product->selling_price, 2) }} {{ \App\Models\Setting::get('currency', 'DA') }}
                                </div>
                                <div class="text-[10px] text-gray-400">Achat: {{ number_format($product->purchase_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="text-sm font-bold {{ $product->stock_quantity < 5 ? 'text-rose-500' : 'text-emerald-500' }}">
                                        {{ $product->stock_quantity }}
                                    </div>
                                    <div class="w-16 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                        <div class="h-full {{ $product->stock_quantity < 5 ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ min(100, $product->stock_quantity * 5) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('products.label', $product) }}" target="_blank" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors" title="Imprimer Étiquette">
                                        <i class="fas fa-barcode"></i>
                                    </a>
                                    <button @click="$dispatch('open-modal', 'adjust-stock-{{ $product->id }}')" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors" title="Ajuster le stock">
                                        <i class="fas fa-cubes"></i>
                                    </button>
                                    @can('edit products')
                                    <a href="{{ route('products.edit', $product) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete products')
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                                
                                <!-- Modal Ajustement Stock -->
                                <x-modal name="adjust-stock-{{ $product->id }}" focusable>
                                    <form method="post" action="{{ route('stock.adjust', $product) }}" class="p-8 text-left">
                                        @csrf
                                        <h2 class="text-2xl font-black text-gray-800 mb-2">Ajuster le stock</h2>
                                        <p class="text-sm text-gray-500 mb-6">{{ $product->name }} - Actuel: <span class="font-bold">{{ $product->stock_quantity }}</span></p>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <x-input-label for="quantity" value="Quantité à ajouter/retirer" />
                                                <x-text-input name="quantity" type="number" step="0.01" class="mt-1 block w-full" required placeholder="Ex: 10 ou -5" />
                                            </div>
                                            <div>
                                                <x-input-label for="type" value="Type de mouvement" />
                                                <select name="type" required class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm font-bold">
                                                    <option value="restock">Réapprovisionnement</option>
                                                    <option value="adjustment">Ajustement manuel</option>
                                                    <option value="damage">Perte / Casse</option>
                                                    <option value="return">Retour client</option>
                                                </select>
                                            </div>
                                            <div class="md:col-span-2">
                                                <x-input-label for="notes" value="Notes / Raison" />
                                                <textarea name="notes" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Expliquez brièvement le changement..."></textarea>
                                            </div>
                                        </div>

                                        <div class="mt-8 flex justify-end gap-3">
                                            <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                                Enregistrer le mouvement
                                            </button>
                                        </div>
                                    </form>
                                </x-modal>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-4">
                                        <i class="fas fa-boxes text-4xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-400">Catalogue vide</h3>
                                    <p class="text-gray-400">Ajoutez votre premier produit pour commencer à vendre.</p>
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
