<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-rose-500 rounded-lg text-white">
                <i class="fas fa-undo text-xl"></i>
            </div>
            <span>{{ __('Retours et Annulations') }}</span>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <!-- Barre de Filtres -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 transition-all hover:shadow-md">
            <form action="{{ route('orders.refunds') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Recherche</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}"
                            class="w-full rounded-xl border-gray-100 bg-gray-50 focus:border-rose-500 focus:ring-rose-200 transition-all pl-10 py-2 text-sm"
                            placeholder="N° Ticket, N° Vente Initiale, ou Nom Client...">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Du</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:border-rose-500 focus:ring-rose-200 transition-all py-2">
                </div>

                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Au</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:border-rose-500 focus:ring-rose-200 transition-all py-2">
                    </div>
                    <button type="submit" class="bg-rose-600 text-white p-2.5 rounded-xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-100">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if(request()->anyFilled(['q', 'date_from', 'date_to']))
                        <a href="{{ route('orders.refunds') }}" class="bg-gray-100 text-gray-500 p-2.5 rounded-xl hover:bg-gray-200 transition-all">
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
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Ticket Retour</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Vente Initiale</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Vendeur / Client</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Montant Retourné</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Paiement</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Date du Retour</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($refunds as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-rose-100 text-rose-600 rounded-lg text-xs font-black tracking-tight">R-{{ $order->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-black tracking-tight">#{{ $order->refund_of_order_id }}</span>
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
                                <div class="text-sm font-black text-rose-600">
                                    -{{ number_format($order->total_amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}
                                </div>
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
                                    <a href="{{ route('orders.print', $order) }}" target="_blank" class="p-2 bg-white border border-gray-100 text-gray-400 rounded-xl hover:text-rose-600 hover:border-rose-100 hover:shadow-sm transition-all" title="Imprimer le ticket de retour">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-undo text-2xl"></i>
                                </div>
                                <p class="font-bold">Aucun retour trouvé</p>
                                <p class="text-xs mt-1">Essayez d'autres filtres ou vérifiez votre recherche.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-50">
                {{ $refunds->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
