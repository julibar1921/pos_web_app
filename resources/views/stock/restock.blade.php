<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-500 rounded-lg text-white">
                    <i class="fas fa-magic text-xl"></i>
                </div>
                <span>{{ __('Assistant Réapprovisionnement') }}</span>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-gray-900 text-white rounded-xl font-bold text-sm hover:bg-black transition-all flex items-center gap-2 no-print">
                <i class="fas fa-print"></i>
                Imprimer la Liste
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mb-8 p-6 bg-amber-50 border-l-4 border-amber-400 rounded-r-2xl no-print">
            <div class="flex gap-4">
                <div class="text-amber-500 text-2xl">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h4 class="font-black text-amber-900 uppercase text-xs tracking-widest">Comment ça marche ?</h4>
                    <p class="text-sm text-amber-800 mt-1">
                        Cette liste regroupe automatiquement tous les produits dont le stock est inférieur à <strong>5 unités</strong>. 
                        Elle est organisée par fournisseur pour vous aider à passer vos commandes plus rapidement.
                    </p>
                </div>
            </div>
        </div>

        @forelse($lowStockProducts as $supplierName => $products)
        <div class="mb-10 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                <h3 class="font-black text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-indigo-500">
                        <i class="fas fa-truck"></i>
                    </span>
                    {{ $supplierName }}
                </h3>
                <span class="px-3 py-1 bg-rose-100 text-rose-600 text-[10px] font-black rounded-full uppercase tracking-tighter">
                    {{ $products->count() }} articles à commander
                </span>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            <th class="px-6 py-4">Produit</th>
                            <th class="px-6 py-4">Code</th>
                            <th class="px-6 py-4 text-center">Stock Actuel</th>
                            <th class="px-6 py-4 text-right">Prix Achat Estimé</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $product->name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $product->category->name }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $product->barcode }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-rose-50 text-rose-600 text-xs font-black rounded-lg">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                {{ number_format($product->purchase_price, 2) }} {{ \App\Models\Setting::get('currency', 'DT') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="py-20 text-center">
            <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-800">Stock Parfait !</h3>
            <p class="text-gray-400 mt-2">Tous vos produits sont au-dessus du seuil d'alerte.</p>
        </div>
        @endforelse
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .shadow-sm { shadow: none !important; border: 1px solid #eee !important; }
        }
    </style>
</x-app-layout>
