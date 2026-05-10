<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-lg"
                     style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
                    <i class="fas fa-chart-bar text-sm"></i>
                </div>
                <span>Rapport des Ventes</span>
            </div>
            @if(request()->hasAny(['user_id','date_from','date_to','payment_method']))
            <a href="{{ route('reports.sales') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 border border-gray-200 transition-all">
                <i class="fas fa-times text-xs"></i> Réinitialiser
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-4 space-y-6">

        {{-- ===== FILTER FORM ===== --}}
        <form method="GET" action="{{ route('reports.sales') }}"
              class="rounded-2xl p-6"
              style="background: linear-gradient(135deg,#0f172a,#1e1b4b); box-shadow: 0 8px 32px rgba(99,102,241,0.2);">

            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-2">
                    <i class="fas fa-sliders-h text-indigo-400 text-sm"></i>
                    <h3 class="text-xs font-black text-indigo-300 uppercase tracking-widest">Filtres du rapport</h3>
                </div>

                {{-- ⚡ Quick date shortcuts --}}
                <div class="flex flex-wrap gap-2" id="quick-btns">
                    @php
                        $today     = now()->format('Y-m-d');
                        $yesterday = now()->subDay()->format('Y-m-d');
                        $weekStart = now()->startOfWeek()->format('Y-m-d');
                        $monthStart= now()->startOfMonth()->format('Y-m-d');
                    @endphp

                    <a href="{{ route('reports.sales', array_merge(request()->except(['date_from','date_to']), ['date_from'=>$today,'date_to'=>$today])) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black transition-all active:scale-95
                           {{ (request('date_from')==$today && request('date_to')==$today) ? 'text-white' : 'text-white/60 hover:text-white' }}"
                       style="{{ (request('date_from')==$today && request('date_to')==$today)
                           ? 'background:rgba(99,102,241,0.5);border:1px solid rgba(165,180,252,0.4);'
                           : 'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);' }}">
                        <i class="fas fa-sun text-amber-400"></i> Aujourd'hui
                    </a>

                    <a href="{{ route('reports.sales', array_merge(request()->except(['date_from','date_to']), ['date_from'=>$yesterday,'date_to'=>$yesterday])) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black transition-all active:scale-95
                           {{ (request('date_from')==$yesterday && request('date_to')==$yesterday) ? 'text-white' : 'text-white/60 hover:text-white' }}"
                       style="{{ (request('date_from')==$yesterday && request('date_to')==$yesterday)
                           ? 'background:rgba(99,102,241,0.5);border:1px solid rgba(165,180,252,0.4);'
                           : 'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);' }}">
                        <i class="fas fa-moon text-blue-300"></i> Hier
                    </a>

                    <a href="{{ route('reports.sales', array_merge(request()->except(['date_from','date_to']), ['date_from'=>$weekStart,'date_to'=>$today])) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black transition-all active:scale-95
                           {{ (request('date_from')==$weekStart && request('date_to')==$today) ? 'text-white' : 'text-white/60 hover:text-white' }}"
                       style="{{ (request('date_from')==$weekStart && request('date_to')==$today)
                           ? 'background:rgba(99,102,241,0.5);border:1px solid rgba(165,180,252,0.4);'
                           : 'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);' }}">
                        <i class="fas fa-calendar-week text-emerald-400"></i> Cette semaine
                    </a>

                    <a href="{{ route('reports.sales', array_merge(request()->except(['date_from','date_to']), ['date_from'=>$monthStart,'date_to'=>$today])) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black transition-all active:scale-95
                           {{ (request('date_from')==$monthStart && request('date_to')==$today) ? 'text-white' : 'text-white/60 hover:text-white' }}"
                       style="{{ (request('date_from')==$monthStart && request('date_to')==$today)
                           ? 'background:rgba(99,102,241,0.5);border:1px solid rgba(165,180,252,0.4);'
                           : 'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);' }}">
                        <i class="fas fa-calendar text-rose-400"></i> Ce mois
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Utilisateur --}}
                <div class="lg:col-span-1">
                    <label class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5 block">Utilisateur</label>
                    <div class="relative">
                        <select name="user_id"
                            class="w-full rounded-xl text-sm font-semibold pl-9 py-2.5 transition-all"
                            style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); color: white;">
                            <option value="" style="background:#1e1b4b;">Tous les vendeurs</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" style="background:#1e1b4b;"
                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-user absolute left-3 top-3 text-indigo-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                {{-- Mode paiement --}}
                <div>
                    <label class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5 block">Paiement</label>
                    <div class="relative">
                        <select name="payment_method"
                            class="w-full rounded-xl text-sm font-semibold pl-9 py-2.5 transition-all"
                            style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); color: white;">
                            <option value="" style="background:#1e1b4b;">Tous</option>
                            <option value="cash"   style="background:#1e1b4b;" {{ request('payment_method')=='cash'   ? 'selected':'' }}>Espèces</option>
                            <option value="card"   style="background:#1e1b4b;" {{ request('payment_method')=='card'   ? 'selected':'' }}>Carte</option>
                            <option value="credit" style="background:#1e1b4b;" {{ request('payment_method')=='credit' ? 'selected':'' }}>Crédit</option>
                        </select>
                        <i class="fas fa-wallet absolute left-3 top-3 text-indigo-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                {{-- Date du --}}
                <div>
                    <label class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5 block">Date du</label>
                    <div class="relative">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-xl text-sm font-semibold pl-9 py-2.5 transition-all"
                            style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); color: white; color-scheme: dark;">
                        <i class="fas fa-calendar absolute left-3 top-3 text-indigo-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                {{-- Date au --}}
                <div>
                    <label class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5 block">Au</label>
                    <div class="relative">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-xl text-sm font-semibold pl-9 py-2.5 transition-all"
                            style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); color: white; color-scheme: dark;">
                        <i class="fas fa-calendar-check absolute left-3 top-3 text-indigo-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex flex-col justify-end">
                    <button type="submit"
                        class="w-full font-black py-2.5 rounded-xl text-sm text-white flex items-center justify-center gap-2 transition-all active:scale-95"
                        style="background: linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow: 0 4px 16px rgba(99,102,241,0.4);">
                        <i class="fas fa-search"></i> Générer le rapport
                    </button>
                </div>
            </div>
        </form>

        {{-- ===== KPI CARDS ===== --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Total CA --}}
            <div class="rounded-2xl p-5 text-white"
                 style="background: linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow: 0 8px 24px rgba(99,102,241,0.3);">
                <div class="text-[10px] font-black uppercase tracking-widest text-white/60 mb-1">Chiffre d'affaires</div>
                <div class="text-2xl font-black">{{ number_format($grandTotal, 3) }}</div>
                <div class="text-xs text-white/60 mt-1">{{ \App\Models\Setting::get('currency','DT') }}</div>
            </div>

            {{-- Nb ventes --}}
            <div class="bg-white rounded-2xl p-5"
                 style="border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15,23,42,0.07);">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Nombre de ventes</div>
                <div class="text-2xl font-black text-gray-800">{{ $orderCount }}</div>
                <div class="text-xs text-gray-400 mt-1">transactions</div>
            </div>

            {{-- Panier moyen --}}
            <div class="bg-white rounded-2xl p-5"
                 style="border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15,23,42,0.07);">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Panier moyen</div>
                <div class="text-2xl font-black text-emerald-600">{{ number_format($averageBasket, 3) }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ \App\Models\Setting::get('currency','DT') }}</div>
            </div>

            {{-- Remises --}}
            <div class="bg-white rounded-2xl p-5"
                 style="border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15,23,42,0.07);">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total remises</div>
                <div class="text-2xl font-black text-rose-500">{{ number_format($totalDiscount, 3) }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ \App\Models\Setting::get('currency','DT') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ===== PAR VENDEUR ===== --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                 style="border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15,23,42,0.07);">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3"
                     style="background: linear-gradient(to right, #f8fafc, white);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs"
                         style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="font-black text-gray-800">Ventes par Vendeur</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($perUser as $i => $row)
                    <div class="px-6 py-4 flex items-center gap-4">
                        {{-- Rank badge --}}
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black shrink-0
                            {{ $i === 0 ? 'text-amber-600' : ($i === 1 ? 'text-gray-400' : ($i === 2 ? 'text-amber-800' : 'text-gray-300')) }}"
                            style="{{ $i === 0 ? 'background:#fef9c3; border:1.5px solid #fde047;' : 'background:#f8fafc; border:1.5px solid #e2e8f0;' }}">
                            #{{ $i + 1 }}
                        </div>
                        {{-- Avatar --}}
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black text-white shrink-0"
                             style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
                            {{ strtoupper(substr($row['name'], 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-800 text-sm">{{ $row['name'] }}</div>
                            <div class="text-[10px] text-gray-400">{{ $row['count'] }} vente(s) · moy. {{ number_format($row['average'], 3) }} {{ \App\Models\Setting::get('currency','DT') }}</div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-black text-indigo-600">{{ number_format($row['total'], 3) }}</div>
                            <div class="text-[10px] text-gray-400">{{ \App\Models\Setting::get('currency','DT') }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-gray-300">
                        <i class="fas fa-chart-bar text-3xl mb-2 block"></i>
                        <p class="text-sm font-bold">Aucune donnée</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ===== PAR JOUR ===== --}}
            <div class="bg-white rounded-2xl overflow-hidden"
                 style="border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15,23,42,0.07);">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3"
                     style="background: linear-gradient(to right, #f8fafc, white);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs"
                         style="background: linear-gradient(135deg,#06b6d4,#0ea5e9);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="font-black text-gray-800">Ventes par Jour</h3>
                </div>
                @if($perDay->count())
                <div class="p-6 space-y-3">
                    @php $maxDay = $perDay->max('total') ?: 1; @endphp
                    @foreach($perDay as $day)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-gray-600">{{ $day['date'] }}</span>
                            <span class="text-xs font-black text-indigo-600">{{ number_format($day['total'], 3) }} {{ \App\Models\Setting::get('currency','DT') }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full rounded-full transition-all"
                                 style="width: {{ round(($day['total'] / $maxDay) * 100) }}%; background: linear-gradient(90deg,#6366f1,#8b5cf6);"></div>
                        </div>
                        <div class="text-[9px] text-gray-400 mt-0.5">{{ $day['count'] }} vente(s)</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-8 text-center text-gray-300">
                    <i class="fas fa-calendar text-3xl mb-2 block"></i>
                    <p class="text-sm font-bold">Aucune donnée</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ===== LISTE DES VENTES ===== --}}
        <div class="bg-white rounded-2xl overflow-hidden"
             style="border: 1.5px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15,23,42,0.07);">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between"
                 style="background: linear-gradient(to right, #f8fafc, white);">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs"
                         style="background: linear-gradient(135deg,#10b981,#059669);">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3 class="font-black text-gray-800">Détail des transactions</h3>
                </div>
                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">{{ $orderCount }} résultat(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead style="background:#f8fafc; border-bottom: 1.5px solid #e2e8f0;">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">N°</th>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Vendeur</th>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Client</th>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Paiement</th>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Remise</th>
                            <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        @php
                            $pm = ['cash'=>['Espèces','bg-emerald-50 text-emerald-600'],
                                   'card'=>['Carte','bg-blue-50 text-blue-600'],
                                   'credit'=>['Crédit','bg-rose-50 text-rose-600']];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 text-xs font-black text-gray-400">#{{ $order->id }}</td>
                            <td class="px-6 py-3">
                                <div class="text-xs font-semibold text-gray-700">{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black text-white shrink-0"
                                         style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
                                        {{ strtoupper(substr($order->user->name,0,1)) }}
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-xs text-gray-500">{{ $order->customer?->name ?? '—' }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ ($pm[$order->payment_method] ?? ['?','bg-gray-50 text-gray-400'])[1] }}">
                                    {{ ($pm[$order->payment_method] ?? ['?'])[0] }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-xs font-semibold {{ $order->discount_amount > 0 ? 'text-rose-500' : 'text-gray-300' }}">
                                {{ $order->discount_amount > 0 ? '-'.number_format($order->discount_amount,3) : '—' }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="font-black text-indigo-600 text-sm">{{ number_format($order->total_amount,3) }}</span>
                                <span class="text-[10px] text-gray-400 ml-1">{{ \App\Models\Setting::get('currency','DT') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i class="fas fa-search text-4xl text-gray-200 mb-3 block"></i>
                                <p class="font-bold text-gray-400">Aucune vente trouvée pour ces critères</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($orderCount > 0)
                    <tfoot style="background: linear-gradient(to right,#f8fafc,white); border-top: 2px solid #e2e8f0;">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest">Total général</td>
                            <td class="px-6 py-4 text-xs font-black text-rose-500">-{{ number_format($totalDiscount,3) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-lg font-black text-indigo-700">{{ number_format($grandTotal,3) }}</span>
                                <span class="text-xs text-gray-400 ml-1">{{ \App\Models\Setting::get('currency','DT') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
