<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-rose-500 rounded-lg text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <span>{{ __('Gestion des Clients & Crédits') }}</span>
            </div>
            @can('create customers')
            <button @click="$dispatch('open-modal', 'add-customer')" class="inline-flex items-center px-4 py-2 bg-rose-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 focus:outline-none focus:border-rose-900 focus:ring ring-rose-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg shadow-rose-200">
                <i class="fas fa-plus mr-2"></i> Nouveau Client
            </button>
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($customers as $customer)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <a href="{{ route('customers.show', $customer) }}" class="flex items-center gap-3 hover:opacity-75 transition-opacity">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl font-black uppercase">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 leading-tight">{{ $customer->name }}</h4>
                                <p class="text-xs text-gray-400">{{ $customer->phone ?? 'Pas de téléphone' }}</p>
                            </div>
                        </a>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('customers.edit', $customer) }}" class="p-2 text-gray-400 hover:text-indigo-600"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Supprimer ce client ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-4 mb-4">
                        <div class="flex items-center justify-between mb-1">
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dette Actuelle</div>
                            <a href="{{ route('customers.show', $customer) }}" class="text-[10px] font-bold text-indigo-500 hover:underline">Voir l'historique</a>
                        </div>
                        <div class="text-xl font-black {{ $customer->balance > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ number_format($customer->balance, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}
                        </div>
                    </div>
                </div>

                @if($customer->balance > 0)
                <button @click="$dispatch('open-modal', 'repay-{{ $customer->id }}')" class="w-full bg-rose-500 text-white px-4 py-3 rounded-2xl text-[10px] font-black uppercase hover:bg-rose-600 transition-all shadow-lg shadow-rose-100">
                    <i class="fas fa-hand-holding-usd mr-2"></i> Enregistrer un Remboursement
                </button>

                <!-- Modal Remboursement -->
                <x-modal name="repay-{{ $customer->id }}" focusable>
                    <form method="post" action="{{ route('customers.repay', $customer) }}" class="p-8">
                        @csrf
                        <h2 class="text-2xl font-black text-gray-800 mb-2">Enregistrer un versement</h2>
                        <p class="text-sm text-gray-500 mb-6">Client: <span class="font-bold">{{ $customer->name }}</span> | Dette: <span class="text-rose-600 font-bold">{{ number_format($customer->balance, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</span></p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="amount" value="Montant versé" />
                                <x-text-input name="amount" type="number" step="0.01" class="mt-1 block w-full" required autofocus placeholder="0.00" />
                            </div>
                            <div>
                                <x-input-label for="payment_method" value="Mode de Paiement" />
                                <select name="payment_method" required class="mt-1 block w-full rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500 shadow-sm text-sm font-bold">
                                    <option value="cash">Espèces</option>
                                    <option value="transfer">Virement</option>
                                    <option value="check">Chèque</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="notes" value="Notes additionnelles" />
                                <textarea name="notes" rows="2" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500 shadow-sm" placeholder="Ex: Paiement fin de mois..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                            <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-rose-700 transition-all">
                                Valider le paiement
                            </button>
                        </div>
                    </form>
                </x-modal>
                @else
                <div class="text-center py-3 bg-emerald-50 text-emerald-600 rounded-2xl text-[10px] font-black uppercase">
                    Compte à jour <i class="fas fa-check ml-1"></i>
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                <i class="fas fa-users text-4xl text-gray-100 mb-4"></i>
                <p class="text-gray-400 font-bold">Aucun client enregistré</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Ajout Client -->
    <x-modal name="add-customer" focusable>
        <form method="post" action="{{ route('customers.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-black text-gray-800 mb-6">Nouveau Client</h2>
            
            <div class="space-y-4">
                <div>
                    <x-input-label for="name" value="Nom Complet" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                </div>
                <div>
                    <x-input-label for="phone" value="Téléphone" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="address" value="Adresse (Optionnel)" />
                    <textarea name="address" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-rose-700 transition-all">
                    Enregistrer le Client
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
