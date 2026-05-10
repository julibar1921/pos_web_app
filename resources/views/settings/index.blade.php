<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-500 rounded-lg text-white">
                <i class="fas fa-cogs text-xl"></i>
            </div>
            <span>{{ __('Paramètres de l\'Entreprise') }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm animate-fade-in-down" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Section: Informations Générales -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-white to-gray-50">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center mr-3 text-indigo-500">
                                    <i class="fas fa-building text-sm"></i>
                                </span>
                                Identité de l'Entreprise
                            </h3>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Nom de l'entreprise</label>
                                <input type="text" name="company_name" value="{{ \App\Models\Setting::get('company_name') }}" 
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50/50"
                                    placeholder="Ex: Épicerie Moderne">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Numéro fiscal / NIF</label>
                                <input type="text" name="tax_number" value="{{ \App\Models\Setting::get('tax_number') }}" 
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50/50"
                                    placeholder="Ex: 123456789">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Devise</label>
                                <select name="currency" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50/50">
                                    <option value="EUR" {{ \App\Models\Setting::get('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                    <option value="USD" {{ \App\Models\Setting::get('currency') == 'USD' ? 'selected' : '' }}>Dollar ($)</option>
                                    <option value="DT" {{ \App\Models\Setting::get('currency') == 'DT' ? 'selected' : '' }}>Dinar Tunisien (DT)</option>
                                    <option value="MAD" {{ \App\Models\Setting::get('currency') == 'MAD' ? 'selected' : '' }}>Dirham (DH)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Taux de TVA (%)</label>
                                <input type="number" step="0.01" name="tax_rate" value="{{ \App\Models\Setting::get('tax_rate', 0) }}" 
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50/50"
                                    placeholder="Ex: 19.00">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Coordonnées -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-white to-gray-50">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center mr-3 text-emerald-500">
                                    <i class="fas fa-map-marker-alt text-sm"></i>
                                </span>
                                Contact & Localisation
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 ml-1">Email professionnel</label>
                                    <input type="email" name="company_email" value="{{ \App\Models\Setting::get('company_email') }}" 
                                        class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50"
                                        placeholder="contact@entreprise.com">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 ml-1">Téléphone</label>
                                    <input type="text" name="company_phone" value="{{ \App\Models\Setting::get('company_phone') }}" 
                                        class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50"
                                        placeholder="+213 00 00 00 00">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Adresse complète</label>
                                <textarea name="company_address" rows="3" 
                                    class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-200 transition-all px-4 py-3 bg-gray-50/50"
                                    placeholder="Rue, Ville, Code Postal">{{ \App\Models\Setting::get('company_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Ticket -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-white to-gray-50">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center mr-3 text-amber-500">
                                    <i class="fas fa-receipt text-sm"></i>
                                </span>
                                Personnalisation des Tickets
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-600 ml-1">Message de pied de page (Footer)</label>
                                <textarea name="footer_text" rows="3" 
                                    class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-200 transition-all px-4 py-3 bg-gray-50/50"
                                    placeholder="Ex: Merci de votre visite ! À bientôt.">{{ \App\Models\Setting::get('footer_text') }}</textarea>
                                <p class="text-xs text-gray-400 italic">Ce texte apparaîtra en bas de chaque ticket imprimé.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar: Logo & Actions -->
                <div class="space-y-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                        <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center mr-3 text-purple-500">
                                    <i class="fas fa-image text-sm"></i>
                                </span>
                                Logo de l'Entreprise
                            </h3>
                        </div>
                        <div class="p-8 text-center" x-data="{ photoName: null, photoPreview: null }">
                            <input type="file" class="hidden" x-ref="photo" name="logo"
                                @change="
                                    photoName = $event.target.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($event.target.files[0]);
                                ">

                            <div class="relative inline-block group">
                                <div class="w-40 h-40 mx-auto rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50 group-hover:border-purple-300 transition-all">
                                    <template x-if="!photoPreview">
                                        @php $logo = \App\Models\Setting::get('logo'); @endphp
                                        @if($logo)
                                            <img src="{{ Storage::url($logo) }}" class="w-full h-full object-contain">
                                        @else
                                            <i class="fas fa-store text-4xl text-gray-300"></i>
                                        @endif
                                    </template>
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="w-full h-full object-contain">
                                    </template>
                                </div>
                                <button type="button" class="absolute -bottom-3 -right-3 w-10 h-10 bg-white shadow-lg rounded-full border border-gray-100 text-purple-500 flex items-center justify-center hover:bg-purple-500 hover:text-white transition-all"
                                    @click="$refs.photo.click()">
                                    <i class="fas fa-camera text-sm"></i>
                                </button>
                            </div>
                            <p class="mt-4 text-xs text-gray-400">Format recommandé : PNG ou JPG (max 2MB)</p>
                        </div>
                    </div>

                    <div class="sticky top-6 space-y-6">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all flex items-center justify-center gap-3 active:scale-95">
                            <i class="fas fa-save"></i>
                            Enregistrer les Modifications
                        </button>

                        <div class="bg-gray-900 rounded-2xl shadow-xl overflow-hidden p-6 space-y-4">
                            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-shield-alt"></i>
                                Maintenance & Exports
                            </h3>
                            <div class="grid grid-cols-1 gap-3">
                                <a href="{{ route('settings.export.products') }}" class="flex items-center gap-3 p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-all border border-white/10 group">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <i class="fas fa-file-csv"></i>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-[10px] font-black text-white uppercase tracking-tight">Exporter Produits</div>
                                        <div class="text-[9px] text-gray-400">Format CSV (Excel)</div>
                                    </div>
                                </a>

                                <a href="{{ route('settings.export.orders') }}" class="flex items-center gap-3 p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-all border border-white/10 group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-[10px] font-black text-white uppercase tracking-tight">Exporter Ventes</div>
                                        <div class="text-[9px] text-gray-400">Historique complet</div>
                                    </div>
                                </a>

                                <a href="{{ route('settings.backup') }}" class="flex items-center gap-3 p-3 bg-indigo-500/20 hover:bg-indigo-500/30 rounded-xl transition-all border border-indigo-500/20 group">
                                    <div class="w-8 h-8 rounded-lg bg-white text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-[10px] font-black text-white uppercase tracking-tight italic">Sauvegarde Totale</div>
                                        <div class="text-[9px] text-indigo-200">Télécharger la base de données</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Section: Import -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 space-y-4">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-file-import"></i>
                                Importation Catalogue
                            </h3>
                            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateImport()">
                                @csrf
                                <div class="p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-center" id="csv-drop-zone">
                                    <input type="file" name="csv_file" class="hidden" id="csv_import" accept=".csv"
                                        onchange="document.getElementById('csv-filename').textContent = this.files[0]?.name ?? 'Aucun fichier choisi'">
                                    <label for="csv_import" class="cursor-pointer block">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-indigo-400 mb-2"></i>
                                        <div class="text-[10px] font-bold text-gray-600">Choisir un fichier .CSV</div>
                                        <div id="csv-filename" class="text-[9px] text-indigo-500 mt-1 font-semibold">Aucun fichier choisi</div>
                                        <div class="text-[8px] text-gray-400 mt-1">Format: Nom, Code, P.Achat, P.Vente, Stock, Catégorie</div>
                                    </label>
                                </div>
                                <button type="submit" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-black transition-all">
                                    LANCER L'IMPORTATION
                                </button>
                            </form>
                            <script>
                                function validateImport() {
                                    const file = document.getElementById('csv_import');
                                    if (!file.value) {
                                        alert('Veuillez sélectionner un fichier CSV avant de lancer l\'importation.');
                                        return false;
                                    }
                                    return true;
                                }
                            </script>
                        </div>
                        
                        <p class="text-center mt-4 text-xs text-gray-500 px-4">
                            Les modifications seront appliquées immédiatement sur tous les documents futurs.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.3s ease-out;
        }
    </style>
</x-app-layout>
