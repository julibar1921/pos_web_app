<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-600 rounded-lg text-white">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <span>{{ __('Gestion des Fournisseurs') }}</span>
            </div>
            @can('create suppliers')
            <button @click="$dispatch('open-modal', 'add-supplier')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                <i class="fas fa-plus mr-2"></i> Nouveau Fournisseur
            </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($suppliers as $supplier)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl font-black uppercase">
                            {{ substr($supplier->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">{{ $supplier->name }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $supplier->contact_person ?? 'Aucun contact' }}</p>
                        </div>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="p-2 text-gray-400 hover:text-indigo-600"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Supprimer ce fournisseur ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-600"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                            <i class="fas fa-phone-alt text-xs"></i>
                        </div>
                        <span>{{ $supplier->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <span class="truncate">{{ $supplier->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                            <i class="fas fa-map-marker-alt text-xs"></i>
                        </div>
                        <span class="line-clamp-1">{{ $supplier->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                <i class="fas fa-truck text-4xl text-gray-100 mb-4"></i>
                <p class="text-gray-400 font-bold">Aucun fournisseur enregistré</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal: Nouveau Fournisseur -->
    <x-modal name="add-supplier" focusable>
        <form method="post" action="{{ route('suppliers.store') }}" class="p-8">
            @csrf
            <h2 class="text-2xl font-black text-gray-800 mb-6">Nouveau Fournisseur</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-input-label for="name" value="Nom de l'entreprise" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="contact_person" value="Personne de contact" />
                    <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="phone" value="Téléphone" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="address" value="Adresse" />
                    <textarea name="address" class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    Enregistrer le Fournisseur
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
