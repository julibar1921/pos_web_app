<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="p-2 bg-gray-100 rounded-lg text-gray-500 hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <span>{{ __('Modifier le Produit') }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')
            
            <!-- Left Side: Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Informations du Produit</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-600 ml-1">Nom du produit</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50"
                                placeholder="Ex: Coca-Cola 33cl, Pain de mie...">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Catégorie</label>
                                <select name="category_id" required
                                    class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Code-barres (Optionnel)</label>
                                <div class="relative">
                                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                                        class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50 pl-10"
                                        placeholder="Scanner ou saisir...">
                                    <div class="absolute left-3 top-3.5 text-gray-400">
                                        <i class="fas fa-barcode"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Unité de Mesure</label>
                                <select name="unit" required
                                    class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50 font-bold">
                                    <option value="unité" {{ $product->unit == 'unité' ? 'selected' : '' }}>Unité (pièce)</option>
                                    <option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>Kilogramme (Kg)</option>
                                    <option value="g" {{ $product->unit == 'g' ? 'selected' : '' }}>Gramme (g)</option>
                                    <option value="l" {{ $product->unit == 'l' ? 'selected' : '' }}>Litre (L)</option>
                                    <option value="ml" {{ $product->unit == 'ml' ? 'selected' : '' }}>Millilitre (ml)</option>
                                    <option value="pack" {{ $product->unit == 'pack' ? 'selected' : '' }}>Pack / Lot</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Prix</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-600 ml-1">Prix d'Achat</label>
                            <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50"
                                placeholder="0.00">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-600 ml-1">Prix de Vente</label>
                            <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50"
                                placeholder="0.00">
                        </div>
                    </div>
                    <div class="px-8 pb-6">
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-xl">
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">Gestion du stock</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Désactivez pour les services ou produits non quantifiables</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_stockable" value="1" class="sr-only peer" {{ $product->is_stockable ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Image -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Image du Produit</h3>
                    </div>
                    <div class="p-8 text-center" x-data="{ photoName: null, photoPreview: '{{ $product->image_path ? Storage::url($product->image_path) : null }}' }">
                        <input type="file" class="hidden" x-ref="photo" name="image"
                            @change="
                                photoName = $event.target.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($event.target.files[0]);
                            ">

                        <div class="relative inline-block group">
                            <div class="w-full aspect-square min-w-[200px] rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50 group-hover:border-emerald-300 transition-all">
                                <template x-if="!photoPreview">
                                    <i class="fas fa-image text-5xl text-gray-300"></i>
                                </template>
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                            </div>
                            <button type="button" class="absolute -bottom-3 -right-3 w-12 h-12 bg-white shadow-xl rounded-full border border-gray-100 text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all scale-100 active:scale-90"
                                @click="$refs.photo.click()">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <p class="mt-6 text-xs text-gray-400">Cliquez pour modifier la photo.</p>
                    </div>
                </div>

                <div class="sticky top-6">
                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-emerald-300 transition-all flex items-center justify-center gap-3 active:scale-95">
                        <i class="fas fa-save"></i>
                        Mettre à jour le Produit
                    </button>
                    
                    <div class="mt-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <div class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Stock Actuel</div>
                        <div class="text-xl font-black text-emerald-700">{{ $product->stock_quantity }} unités</div>
                        <p class="text-[10px] text-emerald-500 mt-1 italic">Pour modifier le stock, utilisez le module d'ajustement sur la page liste.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
