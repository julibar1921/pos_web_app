<nav x-data="{ open: false }" class="mb-4 border-b border-white/10" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%); box-shadow: 0 4px 32px rgba(99,102,241,0.18);">
    <!-- Primary Navigation Menu -->
    <div class="w-full px-4 sm:px-6">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        @php $navLogo = \App\Models\Setting::get('logo'); @endphp
                        @if($navLogo)
                            <img src="{{ Storage::url($navLogo) }}" alt="Logo" class="h-10 w-auto object-contain drop-shadow-md">
                        @else
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                <i class="fas fa-store text-white text-sm"></i>
                            </div>
                        @endif
                        <span class="font-black text-white text-sm tracking-wide hidden lg:block">POS<span style="color:#a5b4fc">Pro</span></span>
                    </a>
                </div>

                <!-- Separator -->
                <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:flex items-center">
                    <!-- Vendre - highlighted -->
                    <a href="{{ route('pos.index') }}"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 group
                        {{ request()->routeIs('pos.*')
                            ? 'text-white shadow-lg'
                            : 'text-white/60 hover:text-white hover:bg-white/10' }}"
                        style="{{ request()->routeIs('pos.*') ? 'background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 4px 15px rgba(99,102,241,0.4);' : '' }}">
                        <i class="fas fa-cash-register {{ request()->routeIs('pos.*') ? '' : 'group-hover:scale-110' }} transition-transform"></i>
                        Vendre
                    </a>

                    @role('admin')
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 group
                        {{ request()->routeIs('dashboard') ? 'text-white bg-white/15' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <i class="fas fa-chart-line text-xs group-hover:scale-110 transition-transform"></i>
                        Tableau de bord
                    </a>

                    <a href="{{ route('orders.history') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 group
                        {{ request()->routeIs('orders.*') ? 'text-white bg-white/15' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <i class="fas fa-file-invoice text-xs group-hover:scale-110 transition-transform"></i>
                        Ventes
                    </a>
                    @endrole

                    <a href="{{ route('customers.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 group
                        {{ request()->routeIs('customers.*') ? 'text-white bg-white/15' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <i class="fas fa-users text-xs group-hover:scale-110 transition-transform"></i>
                        Clients
                    </a>

                    @role('admin')
                    <a href="{{ route('suppliers.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 group
                        {{ request()->routeIs('suppliers.*') ? 'text-white bg-white/15' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <i class="fas fa-truck text-xs group-hover:scale-110 transition-transform"></i>
                        Fournisseurs
                    </a>

                    <a href="{{ route('products.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 group
                        {{ request()->routeIs('products.*') ? 'text-white bg-white/15' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <i class="fas fa-boxes text-xs group-hover:scale-110 transition-transform"></i>
                        Produits
                        @if($lowStockCount > 0)
                            <span class="flex h-4 w-4 items-center justify-center rounded-full text-[8px] font-black text-white animate-pulse"
                                style="background: linear-gradient(135deg,#ef4444,#f97316); box-shadow: 0 0 8px rgba(239,68,68,0.6);">
                                {{ $lowStockCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('categories.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 group
                        {{ request()->routeIs('categories.*') ? 'text-white bg-white/15' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                        <i class="fas fa-tags text-xs group-hover:scale-110 transition-transform"></i>
                        Catégories
                    </a>
                    @endrole

                    @role('admin')
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold text-white/60 hover:text-white hover:bg-white/10 transition-all duration-200">
                                    <i class="fas fa-shield-alt text-xs"></i>
                                    <span>Administration</span>
                                    <svg class="fill-current h-3 w-3 text-white/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('reports.sales')"><i class="fas fa-chart-bar w-4 mr-2 text-violet-400"></i>Rapport Ventes</x-dropdown-link>
                                <x-dropdown-link :href="route('orders.refunds')"><i class="fas fa-undo w-4 mr-2 text-rose-500"></i>Retours & Annulations</x-dropdown-link>
                                <x-dropdown-link :href="route('closings.index')"><i class="fas fa-cash-register w-4 mr-2 text-indigo-400"></i>Clôture Caisse</x-dropdown-link>
                                <x-dropdown-link :href="route('stock.index')"><i class="fas fa-history w-4 mr-2 text-emerald-400"></i>Journal Stock</x-dropdown-link>
                                <x-dropdown-link :href="route('stock.restock')"><i class="fas fa-truck-loading w-4 mr-2 text-amber-400"></i>Assistant Réappro.</x-dropdown-link>
                                <x-dropdown-link :href="route('expenses.index')"><i class="fas fa-receipt w-4 mr-2 text-rose-400"></i>Dépenses</x-dropdown-link>
                                <x-dropdown-link :href="route('users.index')"><i class="fas fa-user-cog w-4 mr-2 text-blue-400"></i>Utilisateurs</x-dropdown-link>
                                <x-dropdown-link :href="route('roles.index')"><i class="fas fa-user-shield w-4 mr-2 text-purple-400"></i>Rôles</x-dropdown-link>
                                <x-dropdown-link :href="route('permissions.index')"><i class="fas fa-key w-4 mr-2 text-amber-500"></i>Permissions</x-dropdown-link>
                                <x-dropdown-link :href="route('settings.index')"><i class="fas fa-cogs w-4 mr-2 text-gray-400"></i>Paramètres</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endrole
                </div>
            </div>

            <!-- Right side: User -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <!-- Online indicator -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">En ligne</span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-xl text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 border border-white/10">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black text-white"
                                style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-3 w-3 text-white/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user-circle w-4 mr-2 text-indigo-400"></i>{{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt w-4 mr-2 text-rose-400"></i>{{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/5 border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">
                <i class="fas fa-cash-register mr-2"></i> Vendre
            </x-responsive-nav-link>
            
            @role('admin')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stock.index')" :active="request()->routeIs('stock.*')">Journal Stock</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('closings.index')" :active="request()->routeIs('closings.*')">Clôture Caisse</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Utilisateurs</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">Rôles</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.*')">Permissions</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">Dépenses</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">Paramètres</x-responsive-nav-link>
            @endrole
            
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">Clients</x-responsive-nav-link>
            
            @role('admin')
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">Fournisseurs</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.*')">Ventes</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">Catégories</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Produits</x-responsive-nav-link>
            @endrole
        </div>
        <div class="pt-4 pb-3 border-t border-white/10 px-4">
            <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-white/50">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
