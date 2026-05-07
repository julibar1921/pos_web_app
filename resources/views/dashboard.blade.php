<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-500 rounded-lg text-white">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <span>{{ __('Tableau de Bord') }}</span>
        </div>
    </x-slot>

    <div class="py-6 space-y-8">
        
        <!-- Cartes Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Ventes Aujourd'hui -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Ventes (Auj)</div>
                    <div class="text-2xl font-black text-gray-800">{{ number_format($salesToday, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                    <div class="mt-2 flex items-center text-[10px] font-bold text-emerald-500">
                        <i class="fas fa-shopping-basket mr-1"></i> {{ $ordersCountToday }} commandes
                    </div>
                </div>
            </div>

            <!-- Dépenses Aujourd'hui -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dépenses (Auj)</div>
                    <div class="text-2xl font-black text-rose-500">{{ number_format($expensesToday, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                    <div class="mt-2 flex items-center text-[10px] font-bold text-rose-400">
                        <i class="fas fa-arrow-down mr-1"></i> Sortie de caisse
                    </div>
                </div>
            </div>

            <!-- Profit Net Aujourd'hui -->
            <div class="bg-indigo-600 rounded-3xl p-6 shadow-lg shadow-indigo-100 relative overflow-hidden group hover:bg-indigo-700 transition-all">
                <div class="relative z-10 text-white">
                    <div class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider mb-1">Profit Net (Mois)</div>
                    <div class="text-2xl font-black">{{ number_format($salesThisMonth - $expensesThisMonth, 2) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                    <div class="mt-2 flex items-center text-[10px] font-bold text-indigo-100">
                        <i class="fas fa-wallet mr-1"></i> Performance {{ now()->translatedFormat('F') }}
                    </div>
                </div>
            </div>

            <!-- Ventes du Mois -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Mois</div>
                    <div class="text-2xl font-black text-gray-800">{{ number_format($salesThisMonth, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                    <div class="mt-2 flex items-center text-[10px] font-bold text-indigo-500">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ now()->translatedFormat('F') }}
                    </div>
                </div>
            </div>

            <!-- Valeur du Stock -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Valeur du Stock</div>
                    <div class="text-2xl font-black text-amber-600">{{ number_format($totalStockValue, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                    <div class="mt-2 flex items-center text-[10px] font-bold text-amber-400">
                        <i class="fas fa-boxes mr-1"></i> Inventaire estimé
                    </div>
                </div>
            </div>

            <!-- Dette Totale Clients -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="relative z-10 text-rose-600">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dettes Clients</div>
                    <div class="text-2xl font-black">{{ number_format(\App\Models\Customer::sum('balance'), 2) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                    <div class="mt-2 flex items-center text-[10px] font-bold text-rose-400">
                        <i class="fas fa-hand-holding-usd mr-1"></i> Total à recouvrer
                    </div>
                </div>
            </div>

            <!-- Dépenses Mensuelles -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="relative z-10">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dépenses (Mois)</div>
                    <div class="text-2xl font-black text-gray-800">{{ number_format(\App\Models\Expense::whereMonth('created_at', now()->month)->sum('amount'), 2) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                    <div class="mt-2 flex items-center text-[10px] font-bold text-gray-400">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> {{ now()->translatedFormat('F') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Performance des Ventes (7 derniers jours)</h3>
                <div class="h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Répartition par Catégorie</h3>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Section: Dernières Ventes -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-history text-indigo-500"></i>
                        Ventes Récentes
                    </h3>
                    <a href="{{ route('orders.history') }}" class="text-xs text-indigo-600 font-bold hover:underline">Tout voir</a>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">#{{ $order->id }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-indigo-600">{{ number_format($order->total_amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $order->payment_method }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg uppercase">Complété</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section: Alertes Stock -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-amber-500"></i>
                        Alertes de Stock Critique
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($lowStockProducts as $product)
                    <div class="flex items-center justify-between p-4 bg-rose-50 rounded-2xl border border-rose-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-rose-500 shadow-sm">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-rose-900">{{ $product->name }}</div>
                                <div class="text-[10px] text-rose-400 uppercase tracking-wider">Restant: {{ $product->stock_quantity }} unités</div>
                            </div>
                        </div>
                        <a href="{{ route('products.edit', $product) }}" class="p-2 bg-white text-rose-500 rounded-lg shadow-sm hover:bg-rose-500 hover:text-white transition-all">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    @empty
                    <div class="py-12 text-center text-gray-400">
                        <i class="fas fa-check-circle text-emerald-500 text-4xl mb-4"></i>
                        <p class="font-bold">Tout est en ordre !</p>
                        <p class="text-xs">Aucun produit en rupture de stock.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Section: Top Produits -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-medal text-amber-500"></i>
                    Produits les plus vendus
                </h3>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                    @foreach($topProducts as $top)
                    <div class="text-center group">
                        <div class="w-20 h-20 mx-auto rounded-3xl bg-gray-50 flex items-center justify-center mb-3 relative group-hover:scale-110 transition-transform shadow-sm overflow-hidden">
                            @if($top->product->image_path)
                                <img src="{{ Storage::url($top->product->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-box text-2xl text-gray-200"></i>
                            @endif
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center font-black text-xs shadow-lg ring-4 ring-white">
                                {{ $loop->iteration }}
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-800 truncate px-2">{{ $top->product->name }}</div>
                        <div class="text-[10px] text-gray-400">{{ $top->total_qty }} vendus</div>
                    </div>
                    @endforeach
                </div>
        </div>

        <!-- Section: Derniers Remboursements -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-hand-holding-usd text-emerald-500"></i>
                    Paiements Clients Récents
                </h3>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-50">
                        @foreach(\App\Models\Repayment::with('customer')->latest()->take(5)->get() as $repayment)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $repayment->customer->name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $repayment->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-black text-emerald-600">+ {{ number_format($repayment->amount, 3) }} {{ \App\Models\Setting::get('currency', 'DT') }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $repayment->payment_method }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des Ventes
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Ventes',
                        data: {!! json_encode($chartSales) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6', drawBorder: false }, ticks: { font: { weight: 'bold' } } },
                        x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                    }
                }
            });

            // Graphique Catégories
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($categoryDistribution->pluck('name')) !!},
                    datasets: [{
                        data: {!! json_encode($categoryDistribution->pluck('total')) !!},
                        backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold', size: 11 } } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
