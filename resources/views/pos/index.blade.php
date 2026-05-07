<x-app-layout>
    <div class="h-[calc(100vh-100px)] overflow-hidden" x-data="posSystem()">
        <div class="flex h-full gap-4">
            
            <!-- Section GAUCHE: Produits & Recherche -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Barre de Recherche & Filtres -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-4 space-y-4">
                    <div class="relative flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" x-model="search" @input.debounce.300ms="filterProducts()"
                                class="w-full rounded-xl border-gray-100 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-200 transition-all pl-12 py-3"
                                placeholder="Rechercher un produit ou scanner un code-barres...">
                            <div class="absolute left-4 top-3.5 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <button @click="showHelpModal = true" class="w-12 h-12 rounded-xl bg-gray-100 text-gray-400 hover:bg-indigo-600 hover:text-white transition-all flex items-center justify-center" title="Aide (F1)">
                            <i class="fas fa-keyboard"></i>
                        </button>
                    </div>
                    
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        <button @click="selectedCategory = null; filterProducts()"
                            :class="selectedCategory === null ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                            class="px-4 py-2 rounded-xl text-sm font-bold whitespace-nowrap transition-all shadow-sm">
                            Tout
                        </button>
                        @foreach($categories as $category)
                        <button @click="selectedCategory = {{ $category->id }}; filterProducts()"
                            :class="selectedCategory === {{ $category->id }} ? 'text-white' : 'text-gray-600 bg-gray-50 hover:bg-gray-100'"
                            :style="selectedCategory === {{ $category->id }} ? 'background-color: {{ $category->color }}' : ''"
                            class="px-4 py-2 rounded-xl text-sm font-bold whitespace-nowrap transition-all shadow-sm border border-transparent">
                            {{ $category->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Grille de Produits -->
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div @click="addToCart(product)" 
                                class="bg-white rounded-2xl p-3 shadow-sm border border-transparent hover:border-indigo-300 hover:shadow-md transition-all cursor-pointer group active:scale-95">
                                <div class="aspect-square rounded-xl bg-gray-50 mb-3 overflow-hidden relative">
                                    <template x-if="product.image_path">
                                        <img :src="'/storage/' + product.image_path" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!product.image_path">
                                        <div class="w-full h-full flex items-center justify-center text-gray-200">
                                            <i class="fas fa-image text-3xl"></i>
                                        </div>
                                    </template>
                                    <div class="absolute top-2 right-2 px-2 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-[10px] font-bold text-gray-600 shadow-sm border border-gray-100">
                                        Stock: <span :class="product.stock_quantity < 5 ? 'text-rose-500' : 'text-emerald-500'" x-text="product.stock_quantity"></span>
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-gray-800 line-clamp-1 mb-1" x-text="product.name"></div>
                                <div class="flex justify-between items-center mt-1">
                                    <div class="text-xs text-indigo-600 font-bold" x-text="formatCurrency(product.selling_price)"></div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase" x-text="'/ ' + product.unit"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- État Vide -->
                    <template x-if="filteredProducts.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-gray-400">
                            <div class="p-6 bg-white rounded-full shadow-sm mb-4">
                                <i class="fas fa-search text-4xl"></i>
                            </div>
                            <p class="font-bold">Aucun produit trouvé</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Section DROITE: Panier -->
            <div class="w-96 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Client</label>
                    <select x-model="customerId" class="w-full rounded-xl border-gray-100 bg-white text-sm font-bold focus:border-indigo-500 focus:ring-indigo-200 transition-all px-4 py-2 shadow-sm">
                        <option value="">Client de passage (Comptant)</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ number_format($customer->balance, 3) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-indigo-500"></i>
                        Panier Actuel
                    </h3>
                    <button @click="clearCart()" class="text-xs text-rose-500 hover:text-rose-600 font-bold">
                        Vider
                    </button>
                </div>

                <!-- Liste des articles -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl group relative">
                            <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-white">
                                <template x-if="item.image_path">
                                    <img :src="'/storage/' + item.image_path" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!item.image_path">
                                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                                        <i class="fas fa-box text-sm"></i>
                                    </div>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-gray-800 truncate" x-text="item.name"></div>
                                <div class="text-[10px] text-gray-400" x-text="formatCurrency(item.price)"></div>
                            </div>
                            <div class="flex items-center gap-2 bg-white px-2 py-1 rounded-lg border border-gray-100">
                                <button @click="decrementQty(index)" class="text-gray-400 hover:text-indigo-600"><i class="fas fa-minus text-[10px]"></i></button>
                                <input type="number" x-model="item.quantity" @input="calculateTotals()" 
                                    :step="['kg', 'g', 'l', 'ml'].includes(item.unit) ? '0.001' : '1'"
                                    class="text-xs font-bold w-12 text-center border-none p-0 focus:ring-0">
                                <button @click="incrementQty(index)" class="text-gray-400 hover:text-indigo-600"><i class="fas fa-plus text-[10px]"></i></button>
                            </div>
                            <button @click="removeFromCart(index)" class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white rounded-full text-[8px] opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </template>
                    
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center px-6">
                            <i class="fas fa-cart-plus text-4xl text-gray-100 mb-4"></i>
                            <p class="text-sm text-gray-300 font-bold">Votre panier est vide</p>
                            <p class="text-[10px] text-gray-300 mt-1">Sélectionnez des produits pour commencer</p>
                        </div>
                    </template>
                </div>

                <!-- Totaux & Actions -->
                <div class="p-6 bg-gray-50 border-t border-gray-100 space-y-4">
                    <div class="space-y-2                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Sous-total</span>
                            <span x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <template x-if="discountValue > 0">
                            <div class="flex justify-between text-sm text-rose-500 font-bold">
                                <span>Remise (<span x-text="discountType === 'percentage' ? discountAmount + '%' : formatCurrency(discountAmount)"></span>)</span>
                                <span x-text="'-' + formatCurrency(discountValue)"></span>
                            </div>
                        </template>
                        <div class="flex justify-between text-lg font-black text-gray-900 border-t border-gray-100 pt-2">
                            <span>Total</span>
                            <span x-text="formatCurrency(total)"></span>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button @click="showDiscountModal = true" class="flex-1 bg-rose-50 text-rose-600 font-bold py-3 rounded-xl hover:bg-rose-100 transition-all text-sm">
                            <i class="fas fa-tag mr-2"></i> Remise
                        </button>
                    </div>

                    <button @click="showCheckoutModal = true" :disabled="cart.length === 0"
                        class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 disabled:opacity-50 disabled:active:scale-100">
                        PROCÉDER AU PAIEMENT
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL: Remise -->
        <div x-show="showDiscountModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showDiscountModal = false"></div>
            <div class="bg-white rounded-3xl w-full max-w-sm relative shadow-2xl overflow-hidden animate-fade-in-up p-8">
                <h3 class="text-xl font-black text-gray-800 mb-6">Appliquer une remise</h3>
                <div class="space-y-6">
                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        <button @click="discountType = 'fixed'" :class="discountType === 'fixed' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'" class="flex-1 py-2 rounded-lg font-bold text-sm transition-all">Montant Fixe</button>
                        <button @click="discountType = 'percentage'" :class="discountType === 'percentage' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'" class="flex-1 py-2 rounded-lg font-bold text-sm transition-all">Pourcentage (%)</button>
                    </div>
                    <div>
                        <x-input-label value="Valeur de la remise" />
                        <input type="number" x-model="discountAmount" @input="calculateTotals()" class="mt-1 block w-full rounded-xl border-gray-200 focus:ring-indigo-200 font-black text-lg" placeholder="0.00">
                    </div>
                    <button @click="showDiscountModal = false" class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl hover:bg-indigo-700 transition-all">
                        APPLIQUER
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL: Paiement -->
        <div x-show="showCheckoutModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showCheckoutModal = false"></div>
            <div class="bg-white rounded-3xl w-full max-w-md relative shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="p-8 space-y-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h2 class="text-2xl font-black text-gray-800">Finaliser la vente</h2>
                        <p class="text-gray-400 text-sm mt-1">Sélectionnez le mode de règlement</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4" :class="customerId ? 'grid-cols-3' : 'grid-cols-2'">
                        <button @click="paymentMethod = 'cash'" 
                            :class="paymentMethod === 'cash' ? 'ring-2 ring-indigo-600 bg-indigo-50 text-indigo-700' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'"
                            class="p-4 rounded-2xl border border-transparent transition-all flex flex-col items-center gap-2 group">
                            <i class="fas fa-money-bill-wave text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-[10px] uppercase">Espèces</span>
                        </button>
                        <button @click="paymentMethod = 'card'" 
                            :class="paymentMethod === 'card' ? 'ring-2 ring-indigo-600 bg-indigo-50 text-indigo-700' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'"
                            class="p-4 rounded-2xl border border-transparent transition-all flex flex-col items-center gap-2 group">
                            <i class="fas fa-credit-card text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-[10px] uppercase">Carte</span>
                        </button>
                        <template x-if="customerId">
                            <button @click="paymentMethod = 'credit'" 
                                :class="paymentMethod === 'credit' ? 'ring-2 ring-rose-600 bg-rose-50 text-rose-700' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'"
                                class="p-4 rounded-2xl border border-transparent transition-all flex flex-col items-center gap-2 group">
                                <i class="fas fa-book text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-bold text-[10px] uppercase">Crédit</span>
                            </button>
                        </template>
                    </div>

                    <div class="bg-gray-900 p-6 rounded-2xl text-white space-y-2">
                        <div class="flex justify-between items-center text-xs text-gray-400 font-bold uppercase tracking-widest">
                            <span>Sous-total</span>
                            <span x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <template x-if="discountValue > 0">
                            <div class="flex justify-between items-center text-xs text-rose-400 font-bold uppercase tracking-widest">
                                <span>Remise</span>
                                <span x-text="'-' + formatCurrency(discountValue)"></span>
                            </div>
                        </template>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-800">
                            <span class="text-gray-400 text-sm font-bold uppercase">Total net</span>
                            <span class="text-2xl font-black" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button @click="showCheckoutModal = false" class="flex-1 py-4 text-gray-500 font-bold hover:bg-gray-50 rounded-2xl transition-all">
                            Annuler
                        </button>
                        <button @click="submitOrder()" :disabled="submitting"
                            class="flex-2 bg-indigo-600 text-white font-black py-4 px-8 rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                            <template x-if="submitting">
                                <i class="fas fa-spinner fa-spin"></i>
                            </template>
                            <span>CONFIRMER LA VENTE</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: Aide Raccourcis -->
        <div x-show="showHelpModal" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showHelpModal = false"></div>
            <div class="bg-white rounded-3xl w-full max-w-sm relative shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-keyboard"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-800">Raccourcis Clavier</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-xs font-bold text-gray-500 uppercase">Aide</span>
                            <kbd class="px-2 py-1 bg-white border border-gray-200 rounded shadow-sm font-black text-xs">F1</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-xs font-bold text-gray-500 uppercase">Payer</span>
                            <kbd class="px-2 py-1 bg-white border border-gray-200 rounded shadow-sm font-black text-xs">F2</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-xs font-bold text-gray-500 uppercase">Vider Panier</span>
                            <kbd class="px-2 py-1 bg-white border border-gray-200 rounded shadow-sm font-black text-xs">F4</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-xs font-bold text-gray-500 uppercase">Rechercher</span>
                            <kbd class="px-2 py-1 bg-white border border-gray-200 rounded shadow-sm font-black text-xs">CTRL + K</kbd>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-xs font-bold text-gray-500 uppercase">Fermer</span>
                            <kbd class="px-2 py-1 bg-white border border-gray-200 rounded shadow-sm font-black text-xs">ESC</kbd>
                        </div>
                    </div>

                    <button @click="showHelpModal = false" class="w-full mt-8 bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-black transition-all">
                        COMPRIS
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                products: @json($products),
                filteredProducts: [],
                search: '',
                selectedCategory: null,
                customerId: '',
                cart: [],
                subtotal: 0,
                total: 0,
                discountAmount: 0,
                discountType: 'fixed',
                discountValue: 0,
                showCheckoutModal: false,
                showDiscountModal: false,
                paymentMethod: 'cash',
                submitting: false,
                showHelpModal: false,
                currency: "{{ \App\Models\Setting::get('currency', 'DT') }}",

                init() {
                    this.filteredProducts = this.products;

                    // Raccourcis clavier
                    window.addEventListener('keydown', (e) => {
                        // F1: Aide
                        if (e.key === 'F1') {
                            e.preventDefault();
                            this.showHelpModal = !this.showHelpModal;
                        }
                        // F2: Paiement
                        if (e.key === 'F2') {
                            e.preventDefault();
                            if (this.cart.length > 0) this.showCheckoutModal = true;
                        }
                        // F4: Vider le panier
                        if (e.key === 'F4') {
                            e.preventDefault();
                            this.clearCart();
                        }
                        // CTRL + K ou CTRL + F: Recherche
                        if ((e.ctrlKey && e.key === 'k') || (e.ctrlKey && e.key === 'f')) {
                            e.preventDefault();
                            document.querySelector('input[x-model="search"]').focus();
                        }
                        // ESC: Fermer les modals
                        if (e.key === 'Escape') {
                            this.showCheckoutModal = false;
                            this.showDiscountModal = false;
                            this.showHelpModal = false;
                        }
                    });
                },

                filterProducts() {
                    this.filteredProducts = this.products.filter(p => {
                        const matchesSearch = p.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                             (p.barcode && p.barcode.includes(this.search));
                        const matchesCategory = this.selectedCategory === null || p.category_id === this.selectedCategory;
                        return matchesSearch && matchesCategory;
                    });
                },

                addToCart(product) {
                    const existingIndex = this.cart.findIndex(item => item.id === product.id);
                    if (existingIndex > -1) {
                        if (this.cart[existingIndex].quantity < product.stock_quantity) {
                            this.cart[existingIndex].quantity++;
                        } else {
                            alert('Stock maximum atteint pour ce produit !');
                        }
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.selling_price),
                            quantity: 1,
                            unit: product.unit,
                            image_path: product.image_path,
                            stock_quantity: product.stock_quantity
                        });
                    }
                    this.calculateTotals();
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.calculateTotals();
                },

                incrementQty(index) {
                    const step = ['kg', 'g', 'l', 'ml'].includes(this.cart[index].unit) ? 0.1 : 1;
                    if (this.cart[index].quantity + step <= this.cart[index].stock_quantity) {
                        this.cart[index].quantity = parseFloat((parseFloat(this.cart[index].quantity) + step).toFixed(3));
                        this.calculateTotals();
                    }
                },

                decrementQty(index) {
                    const step = ['kg', 'g', 'l', 'ml'].includes(this.cart[index].unit) ? 0.1 : 1;
                    if (this.cart[index].quantity - step > 0) {
                        this.cart[index].quantity = parseFloat((parseFloat(this.cart[index].quantity) - step).toFixed(3));
                    } else {
                        this.removeFromCart(index);
                    }
                    this.calculateTotals();
                },

                calculateTotals() {
                    this.subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    
                    if (this.discountType === 'percentage') {
                        this.discountValue = this.subtotal * (this.discountAmount / 100);
                    } else {
                        this.discountValue = parseFloat(this.discountAmount) || 0;
                    }

                    this.total = Math.max(0, this.subtotal - this.discountValue);
                },

                clearCart() {
                    if(confirm('Voulez-vous vraiment vider le panier ?')) {
                        this.cart = [];
                        this.discountAmount = 0;
                        this.calculateTotals();
                    }
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('fr-TN', {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3
                    }).format(value) + ' ' + this.currency;
                },

                printOrder(orderId) {
                    const url = "{{ route('orders.print', ':id') }}".replace(':id', orderId);
                    window.open(url, '_blank', 'width=400,height=600');
                },

                async submitOrder() {
                    this.submitting = true;
                    try {
                        const response = await fetch("{{ route('orders.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                cart: this.cart,
                                payment_method: this.paymentMethod,
                                total_amount: this.total,
                                discount_amount: this.discountValue,
                                discount_type: this.discountType,
                                customer_id: this.customerId
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            if(confirm(result.message + "\n\nSouhaitez-vous imprimer le ticket ?")) {
                                this.printOrder(result.order_id);
                            }
                            window.location.reload(); 
                        } else {
                            alert(result.message);
                        }
                    } catch (error) {
                        alert("Erreur lors de l'enregistrement de la commande.");
                    } finally {
                        this.submitting = false;
                        this.showCheckoutModal = false;
                    }
                }
            }
        }
    </script></script>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
</x-app-layout>
