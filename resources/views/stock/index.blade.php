<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-emerald-500 rounded-lg text-white">
                <i class="fas fa-history text-xl"></i>
            </div>
            <span>{{ __('Journal des Mouvements de Stock') }}</span>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- ===== BARRE DE FILTRES ===== --}}
        <form method="GET" action="{{ route('stock.index') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Recherche Produit --}}
                <div class="lg:col-span-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Produit</label>
                    <div class="relative">
                        <input type="text" name="product" value="{{ request('product') }}"
                            class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 pl-10 py-2.5 text-sm transition-all"
                            placeholder="Nom du produit...">
                        <div class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-box text-sm"></i></div>
                    </div>
                </div>

                {{-- Filtre Utilisateur --}}
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Utilisateur</label>
                    <div class="relative">
                        <select name="user_id" class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 pl-10 py-2.5 text-sm transition-all">
                            <option value="">Tous</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute left-3 top-2.5 text-gray-400 pointer-events-none"><i class="fas fa-user text-sm"></i></div>
                    </div>
                </div>

                {{-- Filtre Type --}}
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Type</label>
                    <div class="relative">
                        <select name="type" class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 pl-10 py-2.5 text-sm transition-all">
                            <option value="">Tous les types</option>
                            <option value="restock"  {{ request('type') == 'restock'    ? 'selected' : '' }}>Réappro</option>
                            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Ajustement</option>
                            <option value="damage"   {{ request('type') == 'damage'     ? 'selected' : '' }}>Casse / Perte</option>
                            <option value="return"   {{ request('type') == 'return'     ? 'selected' : '' }}>Retour</option>
                            <option value="sale"     {{ request('type') == 'sale'       ? 'selected' : '' }}>Vente</option>
                        </select>
                        <div class="absolute left-3 top-2.5 text-gray-400 pointer-events-none"><i class="fas fa-tag text-sm"></i></div>
                    </div>
                </div>

                {{-- Boutons Actions --}}
                <div class="flex flex-col justify-end gap-2">
                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-2.5 rounded-xl hover:bg-emerald-700 transition-all text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    @if(request()->hasAny(['product','user_id','type','date_from','date_to']))
                    <a href="{{ route('stock.index') }}" class="w-full bg-gray-100 text-gray-600 font-bold py-2.5 rounded-xl hover:bg-gray-200 transition-all text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                    @endif
                </div>

                {{-- Date De --}}
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Date du</label>
                    <div class="relative">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 pl-10 py-2.5 text-sm transition-all">
                        <div class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-calendar text-sm"></i></div>
                    </div>
                </div>

                {{-- Date Au --}}
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 block">Au</label>
                    <div class="relative">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 pl-10 py-2.5 text-sm transition-all">
                        <div class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-calendar-check text-sm"></i></div>
                    </div>
                </div>

            </div>

            {{-- Active filters badges --}}
            @if(request()->hasAny(['product','user_id','type','date_from','date_to']))
            <div class="mt-4 flex flex-wrap gap-2 items-center">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Filtres actifs :</span>
                @if(request('product'))
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                        <i class="fas fa-box mr-1"></i>{{ request('product') }}
                    </span>
                @endif
                @if(request('user_id'))
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full border border-indigo-200">
                        <i class="fas fa-user mr-1"></i>{{ $users->find(request('user_id'))?->name }}
                    </span>
                @endif
                @if(request('type'))
                    <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                        <i class="fas fa-tag mr-1"></i>{{ request('type') }}
                    </span>
                @endif
                @if(request('date_from') || request('date_to'))
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-full border border-purple-200">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ request('date_from') ?? '...' }} → {{ request('date_to') ?? '...' }}
                    </span>
                @endif
                <span class="text-xs text-gray-400 font-semibold ml-2">{{ $movements->total() }} résultat(s)</span>
            </div>
            @endif
        </form>

        {{-- ===== TABLEAU ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php
                            $colors = [
                                'sale'       => 'bg-blue-50 text-blue-600',
                                'restock'    => 'bg-emerald-50 text-emerald-600',
                                'adjustment' => 'bg-amber-50 text-amber-600',
                                'damage'     => 'bg-rose-50 text-rose-600',
                                'return'     => 'bg-purple-50 text-purple-600',
                            ];
                            $labels = [
                                'sale'       => 'Vente',
                                'restock'    => 'Réappro',
                                'adjustment' => 'Ajustement',
                                'damage'     => 'Casse/Perte',
                                'return'     => 'Retour',
                            ];
                        @endphp
                        @forelse($movements as $movement)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-semibold">{{ $movement->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $movement->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-800">{{ $movement->product->name }}</div>
                                <div class="text-[10px] text-gray-400">Stock actuel : {{ $movement->product->stock_quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-black px-3 py-1 rounded-full {{ $movement->quantity > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity, 3) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase {{ $colors[$movement->type] ?? 'bg-gray-50 text-gray-600' }}">
                                    {{ $labels[$movement->type] ?? $movement->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-black">
                                        {{ substr($movement->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-600 font-semibold">{{ $movement->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-400 line-clamp-2 max-w-xs">{{ $movement->notes ?? '—' }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <i class="fas fa-search text-4xl text-gray-200 mb-3 block"></i>
                                <p class="font-bold text-gray-400">Aucun mouvement trouvé</p>
                                <p class="text-sm text-gray-300 mt-1">Essayez de modifier vos filtres de recherche</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($movements->hasPages())
            <div class="p-6 border-t border-gray-50 flex items-center justify-between">
                <div class="text-xs text-gray-400 font-semibold">
                    Affichage {{ $movements->firstItem() }}–{{ $movements->lastItem() }} sur {{ $movements->total() }} mouvements
                </div>
                {{ $movements->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
