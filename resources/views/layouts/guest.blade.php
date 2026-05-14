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
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
            .glass-panel {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.8);
                box-shadow: 0 10px 40px -10px rgba(14, 165, 233, 0.15);
            }
            .blob {
                position: absolute;
                filter: blur(90px);
                z-index: 0;
                opacity: 0.6;
                animation: float 10s infinite ease-in-out alternate;
            }
            @keyframes float {
                0% { transform: translate(0, 0) scale(1); }
                100% { transform: translate(30px, -30px) scale(1.1); }
            }
            
            /* Custom Scrollbar for better UI */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #f8fafc; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>
    <body class="antialiased bg-[#f0f9ff] text-slate-800 relative overflow-hidden min-h-screen flex items-center justify-center selection:bg-blue-500/20">
        <!-- Background Orbs -->
        <div class="blob bg-sky-300 w-[500px] h-[500px] rounded-full top-[-20%] left-[-10%]"></div>
        <div class="blob bg-blue-200 w-[400px] h-[400px] rounded-full bottom-[-10%] right-[-5%]" style="animation-delay: -5s;"></div>
        <div class="blob bg-cyan-100 w-[300px] h-[300px] rounded-full top-[30%] left-[60%]" style="animation-delay: -2s;"></div>
        
        <!-- Grid overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgwLDAsMCwwLjAxNSkiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] z-0"></div>

        <div class="relative z-10 w-full max-w-md p-6">
            <div class="flex justify-center mb-8">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-sky-500 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:scale-105 group-hover:-rotate-3 transition-all duration-300 ring-4 ring-white/50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-3xl font-black text-slate-800 tracking-tight">Q-Les</span>
                </a>
            </div>

            <div class="glass-panel rounded-3xl p-8 sm:p-10 relative overflow-hidden">
                <!-- Shine effect -->
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white to-transparent opacity-80"></div>
                
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm font-medium text-slate-500">
                &copy; {{ date('Y') }} Q-Les Platform. All rights reserved.
            </div>
        </div>
    </body>
</html>
