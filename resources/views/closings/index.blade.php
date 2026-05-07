<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-600 rounded-lg text-white">
                <i class="fas fa-lock text-xl"></i>
            </div>
            <span>{{ __('Clôture de Caisse') }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-100 border-l-4 border-rose-500 text-rose-700 rounded-r-lg">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire de clôture -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 sticky top-6">
                    <h3 class="text-xl font-black text-gray-800 mb-6">Nouvelle Clôture</h3>
                    
                    @if($isClosedToday)
                        <div class="p-6 bg-emerald-50 rounded-2xl text-center">
                            <i class="fas fa-check-circle text-4xl text-emerald-500 mb-4"></i>
                            <p class="font-bold text-emerald-800">Caisse clôturée pour aujourd'hui</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('closings.store') }}" class="space-y-6">
                            @csrf
                            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                                <div class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Résumé du système</div>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm font-bold">
                                        <span class="text-gray-500">Ventes Espèces (Auj) :</span>
                                        <span class="text-emerald-600">+{{ number_format($cashSales, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm font-bold">
                                        <span class="text-gray-500">Dépenses (Auj) :</span>
                                        <span class="text-rose-500">-{{ number_format($expenses, 2) }}</span>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200 flex justify-between font-black">
                                        <span class="text-gray-800 uppercase text-[10px] tracking-widest">Attendu en Caisse :</span>
                                        <span class="text-indigo-600 text-lg">{{ number_format($expectedCash, 2) }} {{ \App\Models\Setting::get('currency', 'DA') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="actual_amount" value="Montant Réel en Caisse" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <input type="number" step="0.01" name="actual_amount" required class="block w-full pl-11 pr-4 py-4 bg-white border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-black text-gray-800 text-xl" placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <x-input-label for="notes" value="Observations / Notes" />
                                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-2xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Ex: Manque de 50 DA du à une erreur de rendu..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition duration-300">
                                VALIDER LA CLÔTURE
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Historique des clôtures -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="font-black text-gray-800 tracking-tight uppercase text-sm">Historique des Clôtures</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Attendu</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Réel</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Écart</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Utilisateur</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($closings as $closing)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600">
                                        {{ $closing->closed_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                        {{ number_format($closing->expected_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                        {{ number_format($closing->actual_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $closing->difference == 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                            {{ $closing->difference > 0 ? '+' : '' }}{{ number_format($closing->difference, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $closing->user->name }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fas fa-lock text-4xl mb-4"></i>
                                        <p class="font-bold">Aucune clôture enregistrée</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-gray-50">
                        {{ $closings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
