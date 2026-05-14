<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Q-Les - Aplikasi Belajar Pintar</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-card {
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
    </style>
</head>
<body class="antialiased bg-[#f0f9ff] text-slate-800 selection:bg-blue-500/20">
    <div class="relative min-h-screen overflow-hidden flex flex-col items-center justify-center">
        <!-- Background Orbs -->
        <div class="blob bg-sky-300 w-[600px] h-[600px] rounded-full top-[-20%] left-[-10%]"></div>
        <div class="blob bg-blue-200 w-[500px] h-[500px] rounded-full bottom-[-10%] right-[-5%]" style="animation-delay: -5s;"></div>
        <div class="blob bg-cyan-100 w-[400px] h-[400px] rounded-full top-[30%] left-[60%]" style="animation-delay: -2s;"></div>
        
        <!-- Grid overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgwLDAsMCwwLjAxNSkiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] z-0"></div>

        <div class="relative z-10 w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            
            <!-- Glass Card -->
            <div class="glass-card rounded-[2.5rem] p-10 sm:p-16 text-center relative overflow-hidden">
                <!-- Shine effect -->
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white to-transparent opacity-80"></div>
                
                <div class="inline-flex items-center justify-center w-20 h-20 mb-8 rounded-2xl bg-gradient-to-br from-blue-400 to-sky-500 shadow-lg shadow-blue-500/25 ring-4 ring-white/50">
                    <span class="text-4xl font-black text-white">Q</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-black tracking-tight text-slate-800 mb-6">
                    Selamat Datang di Q-Les
                </h1>
                
                <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-500 mb-10 leading-relaxed font-medium">
                    Platform belajar interaktif terpadu. Hubungkan siswa dan guru tanpa batas, selesaikan masalah dengan cepat melalui layanan pelanggan cerdas kami.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" class="group relative px-8 py-4 bg-gradient-to-r from-blue-500 to-sky-400 text-white font-bold rounded-xl overflow-hidden shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300">
                                <span class="relative z-10">Masuk ke Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group relative px-8 py-4 bg-gradient-to-r from-blue-500 to-sky-400 text-white font-bold rounded-xl overflow-hidden shadow-lg shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300">
                                <span class="relative z-10">Login Admin</span>
                            </a>
                        @endauth
                    @endif
                    
                    <a href="#" class="px-8 py-4 bg-white/60 border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-colors duration-300 shadow-sm">
                        Unduh Aplikasi (APK)
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center text-slate-500 text-sm font-medium">
                &copy; {{ date('Y') }} Q-Les Team. All rights reserved. Laravel v{{ Illuminate\Foundation\Application::VERSION }}
            </div>
        </div>
    </div>
</body>
</html>
