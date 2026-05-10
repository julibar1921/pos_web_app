<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-500 rounded-lg text-white">
                <i class="fas fa-history text-xl"></i>
            </div>
            <span>{{ __('Historique des Ventes') }}</span>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Barre de Filtres -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 transition-all hover:shadow-md">
            <form action="{{ route('orders.history') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Recherche</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}"
                            class="w-full rounded-xl border-gray-100 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-200 transition-all pl-10 py-2 text-sm"
                            placeholder="N° Ticket ou Nom Client...">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Mode de Paiement</label>
                    <select name="payment_method" class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-200 transition-all py-2">
                        <option value="">Tous</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Carte</option>
                        <option value="credit" {{ request('payment_method') == 'credit' ? 'selected' : '' }}>Crédit</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Du</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-200 transition-all py-2">
                </div>

                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Au</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-200 transition-all py-2">
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white p-2.5 rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if(request()->anyFilled(['q', 'payment_method', 'date_from', 'date_to']))
                        <a href="{{ route('orders.history') }}" class="bg-gray-100 text-gray-500 p-2.5 rounded-xl hover:bg-gray-200 transition-all">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Ticket</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Vendeur / Client</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Remise</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Paiement</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-black tracking-tight">#{{ $order->id }}</span>
                                @if($order->isRefund())
                                    <span class="ml-2 px-2 py-0.5 bg-rose-100 text-rose-600 rounded text-[9px] font-black uppercase tracking-widest">Retour (#{{ $order->refund_of_order_id }})</span>
                                @elseif($order->refunds->isNotEmpty())
                                    <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-600 rounded text-[9px] font-black uppercase tracking-widest">Remboursé</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-bold text-gray-800">{{ $order->user->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-1">
                                        <i class="fas fa-user text-[8px]"></i>
                                        {{ $order->customer ? $order->customer->name : 'Client de passage' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-black {{ $order->isRefund() ? 'text-rose-600' : 'text-indigo-600' }}">
                                    {{ $order->isRefund() ? '-' : '' }}{{ number_format($order->total_amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->discount_amount > 0)
                                    <span class="text-xs font-bold text-rose-500">-{{ number_format($order->discount_amount, 3) }}</span>
                                @else
                                    <span class="text-[10px] text-gray-300">--</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $paymentStyles = [
                                        'cash' => 'bg-emerald-50 text-emerald-600',
                                        'card' => 'bg-blue-50 text-blue-600',
                                        'credit' => 'bg-rose-50 text-rose-600'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-[10px] font-black rounded-full {{ $paymentStyles[$order->payment_method] ?? 'bg-gray-50 text-gray-600' }} uppercase tracking-widest">
                                    {{ $order->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-gray-500">{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="$dispatch('open-modal', 'view-order-{{ $order->id }}')" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-all" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($order->isSale() && $order->refunds->isEmpty())
                                        <form action="{{ route('orders.refund', $order) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir effectuer un retour complet pour cette vente ? Le stock sera automatiquement restauré.');">
                                            @csrf
                                            <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-100 transition-all" title="Annuler / Retour">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 transition-all" title="Facture A4">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <a href="{{ route('orders.print', $order) }}" target="_blank" class="p-2 bg-white border border-gray-100 text-gray-400 rounded-xl hover:text-indigo-600 hover:border-indigo-100 hover:shadow-sm transition-all" title="Imprimer le ticket">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>

                                <!-- Order Details Modal -->
                                <x-modal name="view-order-{{ $order->id }}" focusable>
                                    <div class="p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <h2 class="text-lg font-bold text-gray-900">
                                                Détails de la commande #{{ $order->id }}
                                            </h2>
                                            <button @click="$dispatch('close')" class="text-gray-400 hover:text-gray-500 transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="bg-gray-50 rounded-xl p-4 mb-4 text-sm text-left">
                                            <div class="grid grid-cols-2 gap-2">
                                                <p><strong class="text-gray-600">Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                                <p><strong class="text-gray-600">Paiement:</strong> <span class="uppercase">{{ $order->payment_method }}</span></p>
                                                <p><strong class="text-gray-600">Vendeur:</strong> {{ $order->user->name }}</p>
                                                <p><strong class="text-gray-600">Client:</strong> {{ $order->customer ? $order->customer->name : 'Client de passage' }}</p>
                                            </div>
                                        </div>

                                        <div class="max-h-64 overflow-y-auto mb-4 border border-gray-100 rounded-xl">
                                            <table class="w-full text-left text-sm border-collapse">
                                                <thead class="bg-gray-50 sticky top-0">
                                                    <tr>
                                                        <th class="py-3 px-4 font-bold text-gray-600 text-xs uppercase tracking-wider">Produit</th>
                                                        <th class="py-3 px-4 font-bold text-gray-600 text-xs uppercase tracking-wider text-center">Qté</th>
                                                        <th class="py-3 px-4 font-bold text-gray-600 text-xs uppercase tracking-wider text-right">P.U.</th>
                                                        <th class="py-3 px-4 font-bold text-gray-600 text-xs uppercase tracking-wider text-right">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100">
                                                    @foreach($order->items as $item)
                                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                                        <td class="py-3 px-4">{{ $item->product ? $item->product->name : 'Produit supprimé' }}</td>
                                                        <td class="py-3 px-4 text-center font-semibold">{{ $item->quantity }}</td>
                                                        <td class="py-3 px-4 text-right">{{ number_format($item->unit_price, 3) }}</td>
                                                        <td class="py-3 px-4 text-right font-semibold">{{ number_format($item->subtotal, 3) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="space-y-2 mb-4 text-sm">
                                            @if($order->discount_amount > 0)
                                            <div class="flex justify-between items-center px-4 text-gray-600">
                                                <span>Sous-total</span>
                                                <span>{{ number_format($order->total_amount + $order->discount_amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center px-4 text-rose-500 font-medium">
                                                <span>Remise</span>
                                                <span>-{{ number_format($order->discount_amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</span>
                                            </div>
                                            @endif
                                            
                                            <div class="flex justify-between items-center bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                                                <span class="font-bold text-indigo-900 uppercase tracking-widest text-xs">Total à payer</span>
                                                <span class="font-black text-indigo-600 text-xl">{{ number_format($order->total_amount, 3) }} <span class="text-sm">{{ \App\Models\Setting::get('currency', 'DT') }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </x-modal>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-history text-2xl"></i>
                                </div>
                                <p class="font-bold">Aucune vente correspondante</p>
                                <p class="text-xs mt-1">Essayez d'autres filtres ou vérifiez votre recherche.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-50">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
