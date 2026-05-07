<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}" class="p-2 bg-gray-100 rounded-lg text-gray-500 hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <span>{{ __('Créer une Catégorie') }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Détails de la Catégorie</h3>
                </div>
                <form action="{{ route('categories.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 ml-1">Nom de la catégorie</label>
                        <input type="text" name="name" required
                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50/50"
                            placeholder="Ex: Boissons, Boulangerie...">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 ml-1">Couleur d'identification</label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="color" value="#4f46e5"
                                class="h-12 w-20 rounded-xl border-gray-200 p-1 bg-gray-50/50 cursor-pointer">
                            <p class="text-xs text-gray-400">Cette couleur aidera à identifier la catégorie sur l'interface de vente.</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 ml-1">Description (Optionnel)</label>
                        <textarea name="description" rows="3" 
                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50/50"
                            placeholder="Brève description de la catégorie..."></textarea>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all active:scale-95">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
