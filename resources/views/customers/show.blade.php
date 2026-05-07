<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.index') }}" class="p-2 bg-gray-100 rounded-lg text-gray-500 hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <span>{{ __('Fiche Client : ') }} {{ $customer->name }}</span>
        </div>
    </x-slot>

    <div class="py-6 space-y-8">
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Coordonnées</div>
                <div class="text-lg font-bold text-gray-800">{{ $customer->phone ?? 'Pas de téléphone' }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $customer->address ?? 'Pas d\'adresse' }}</div>
            </div>

            <div class="bg-rose-50 p-6 rounded-2xl border border-rose-100">
                <div class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Dette Actuelle</div>
                <div class="text-3xl font-black text-rose-600">
                    {{ number_format($customer->balance, 2) }} {{ \App\Models\Setting::get('currency', 'DT') }}
                </div>
                <div class="text-[10px] text-rose-400 mt-1 italic">Dernier mouvement: {{ $customer->updated_at->format('d/m/Y') }}</div>
            </div>

            <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                <div class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Total Remboursé</div>
                <div class="text-3xl font-black text-emerald-600">
                    {{ number_format($repayments->sum('amount'), 2) }} {{ \App\Models\Setting::get('currency', 'DT') }}
                </div>
                <div class="text-[10px] text-emerald-400 mt-1 italic">Sur {{ $repayments->count() }} versements</div>
            </div>
        </div>

        <!-- History Tabs -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'repayments' }">
            <div class="flex border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'repayments'" :class="tab === 'repayments' ? 'bg-white border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:bg-gray-100'" class="px-8 py-4 text-sm font-bold transition-all">
                    Historique des Remboursements
                </button>
                <button @click="tab = 'orders'" :class="tab === 'orders' ? 'bg-white border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:bg-gray-100'" class="px-8 py-4 text-sm font-bold transition-all">
                    Historique des Achats
                </button>
            </div>

            <div class="p-0">
                <!-- Repayments Tab -->
                <div x-show="tab === 'repayments'" class="animate-fade-in">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Reçu par</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Méthode</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($repayments as $repayment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $repayment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                        {{ $repayment->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-emerald-600">
                                        + {{ number_format($repayment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-[10px] font-bold uppercase tracking-widest">
                                            {{ $repayment->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-400 italic">
                                        {{ $repayment->notes ?? '--' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Aucun remboursement enregistré.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div x-show="tab === 'orders'" class="animate-fade-in">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Ticket</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Vendeur</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-400">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-rose-600">
                                        - {{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('orders.print', $order) }}" target="_blank" class="text-indigo-500 hover:text-indigo-700">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Aucun achat enregistré.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
