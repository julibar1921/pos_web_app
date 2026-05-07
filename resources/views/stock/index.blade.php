<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-emerald-500 rounded-lg text-white">
                <i class="fas fa-history text-xl"></i>
            </div>
            <span>{{ __('Historique des Mouvements de Stock') }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($movements as $movement)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-800">{{ $movement->product->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-black {{ $movement->quantity > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'sale' => 'bg-blue-50 text-blue-600',
                                        'restock' => 'bg-emerald-50 text-emerald-600',
                                        'adjustment' => 'bg-amber-50 text-amber-600',
                                        'damage' => 'bg-rose-50 text-rose-600',
                                        'return' => 'bg-purple-50 text-purple-600',
                                    ];
                                    $labels = [
                                        'sale' => 'Vente',
                                        'restock' => 'Réappro',
                                        'adjustment' => 'Ajustement',
                                        'damage' => 'Casse/Perte',
                                        'return' => 'Retour',
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase {{ $colors[$movement->type] ?? 'bg-gray-50' }}">
                                    {{ $labels[$movement->type] ?? $movement->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $movement->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-400 line-clamp-1">{{ $movement->notes ?? '-' }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-history text-4xl mb-4"></i>
                                <p class="font-bold">Aucun mouvement enregistré</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-50">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
