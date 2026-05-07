<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ \App\Models\Setting::get('company_name', 'Gestion POS') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .bg-whitesmoke { background-color: #f5f5f5; }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        </style>
    </head>
    <body class="antialiased bg-whitesmoke text-gray-900 font-sans">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen selection:bg-indigo-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-bold text-gray-600 hover:text-indigo-600 transition">Tableau de bord</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-600 hover:text-indigo-600 transition">Connexion</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-bold text-gray-600 hover:text-indigo-600 transition">Inscription</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">
                <div class="flex justify-center mb-12">
                    @php $logo = \App\Models\Setting::get('logo'); @endphp
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" class="h-24 w-auto drop-shadow-2xl">
                    @else
                        <div class="w-24 h-24 bg-indigo-600 rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                            <i class="fas fa-shopping-basket text-4xl"></i>
                        </div>
                    @endif
                </div>

                <h1 class="text-5xl font-black text-gray-800 mb-4 tracking-tighter">
                    {{ \App\Models\Setting::get('company_name', 'Gestion Épicerie POS') }}
                </h1>
                <p class="text-xl text-gray-500 mb-12 max-w-2xl mx-auto font-medium">
                    Simplifiez votre gestion quotidienne, suivez vos stocks en temps réel et offrez la meilleure expérience à vos clients.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    @auth
                        <a href="{{ route('pos.index') }}" class="w-full sm:w-auto px-12 py-5 bg-indigo-600 text-white rounded-3xl font-black text-lg hover:bg-indigo-700 transition-all shadow-2xl shadow-indigo-100 flex items-center justify-center gap-3">
                            <i class="fas fa-cash-register"></i>
                            Accéder à la Caisse
                        </a>
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-12 py-5 bg-white text-gray-800 rounded-3xl font-black text-lg hover:bg-gray-50 transition-all border border-gray-100 shadow-xl flex items-center justify-center gap-3">
                            <i class="fas fa-chart-pie"></i>
                            Tableau de Bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-12 py-5 bg-indigo-600 text-white rounded-3xl font-black text-lg hover:bg-indigo-700 transition-all shadow-2xl shadow-indigo-100 flex items-center justify-center gap-3">
                            <i class="fas fa-sign-in-alt"></i>
                            Commencer la session
                        </a>
                    @endauth
                </div>

                <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Ventes Rapides</h3>
                        <p class="text-sm text-gray-400">Interface optimisée pour le tactile et la rapidité de saisie.</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Sécurité Totale</h3>
                        <p class="text-sm text-gray-400">Audit de stock, historique des ventes et gestion des rôles.</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-4 text-xl">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Analyses Précises</h3>
                        <p class="text-sm text-gray-400">Graphiques en temps réel et suivi de la rentabilité mensuelle.</p>
                    </div>
                </div>

                <div class="mt-24 text-gray-400 text-xs font-bold uppercase tracking-widest">
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'Gestion POS') }} - Tous droits réservés
                </div>
            </div>
        </div>
    </body>
</html>
