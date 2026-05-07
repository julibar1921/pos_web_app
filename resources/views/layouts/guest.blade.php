<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <link rel="icon" type="image/png" href="{{ Storage::url(\App\Models\Setting::get('logo')) }}">
        
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="antialiased text-gray-900 overflow-hidden">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-rose-500 relative">
            
            <!-- Cercles décoratifs -->
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-900/10 rounded-full blur-3xl"></div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 glass shadow-2xl rounded-[2rem] relative z-10 mx-4">
                <div class="flex justify-center mb-8">
                    @php $logo = \App\Models\Setting::get('logo'); @endphp
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" class="w-20 h-20 object-contain drop-shadow-xl">
                    @else
                        <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-indigo-600 text-3xl shadow-lg">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                    @endif
                </div>

                {{ $slot }}
            </div>
            
            <div class="mt-8 text-white/60 text-sm font-medium relative z-10">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'Gestion Épicerie POS') }} - Tous droits réservés
            </div>
        </div>
    </body>
</html>
