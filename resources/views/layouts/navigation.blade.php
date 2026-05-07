<nav x-data="{ open: false }" class="bg-white shadow-sm mb-4 border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')" class="text-indigo-600 hover:text-indigo-700 font-bold transition flex items-center gap-2 px-4 bg-indigo-50 rounded-xl border-none">
                        <i class="fas fa-cash-register"></i>
                        Vendre
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-600 hover:text-indigo-600 font-semibold transition flex items-center gap-2">
                        <i class="fas fa-chart-line text-xs"></i>
                        Tableau de bord
                    </x-nav-link>
                    <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.*')" class="text-gray-600 hover:text-indigo-600 font-semibold transition flex items-center gap-2">
                        <i class="fas fa-file-invoice text-xs"></i>
                        Factures & Ventes
                    </x-nav-link>
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="text-gray-600 hover:text-indigo-600 font-semibold transition flex items-center gap-2">
                        <i class="fas fa-users text-xs"></i>
                        Clients
                    </x-nav-link>
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="text-gray-600 hover:text-indigo-600 font-semibold transition flex items-center gap-2">
                        <i class="fas fa-truck text-xs"></i>
                        Fournisseurs
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-gray-600 hover:text-indigo-600 font-semibold transition flex items-center gap-2">
                        <i class="fas fa-boxes text-xs"></i>
                        Produits
                        @if($lowStockCount > 0)
                            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[8px] font-black text-white shadow-lg shadow-rose-200 animate-pulse">
                                {{ $lowStockCount }}
                            </span>
                        @endif
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-gray-600 hover:text-indigo-600 font-semibold transition flex items-center gap-2">
                        <i class="fas fa-tags text-xs"></i>
                        Catégories
                    </x-nav-link>

                    @role('admin')
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-semibold text-gray-500 hover:text-indigo-600 transition duration-150 ease-in-out">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-xs"></i>
                                        <span>Administration</span>
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('closings.index')">Clôture Caisse</x-dropdown-link>
                                <x-dropdown-link :href="route('stock.index')">Journal Stock</x-dropdown-link>
                                <x-dropdown-link :href="route('stock.restock')" class="bg-amber-50 text-amber-700 font-bold">Assistant Réappro.</x-dropdown-link>
                                <x-dropdown-link :href="route('expenses.index')">Dépenses</x-dropdown-link>
                                <x-dropdown-link :href="route('users.index')">Utilisateurs</x-dropdown-link>
                                <x-dropdown-link :href="route('settings.index')">Paramètres</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-indigo-600 focus:outline-none transition ease-in-out duration-150 shadow-sm border-gray-100">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')">
                <i class="fas fa-cash-register mr-2"></i> Vendre
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @role('admin')
            <x-responsive-nav-link :href="route('stock.index')" :active="request()->routeIs('stock.*')">
                Journal Stock
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('closings.index')" :active="request()->routeIs('closings.*')">
                Clôture Caisse
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                Utilisateurs
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                Dépenses
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')">
                Paramètres
            </x-responsive-nav-link>
            @endrole
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                Clients
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                Fournisseurs
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.*')">
                Ventes
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                Catégories
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                Produits
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                Dépenses
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
