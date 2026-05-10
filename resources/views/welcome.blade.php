<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Q-Les - Aplikasi Belajar Pintar</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,800&display=swap" rel="stylesheet" />
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-['Inter'] selection:bg-indigo-500 selection:text-white">
    <div class="relative min-h-screen bg-slate-900 overflow-hidden">
        <!-- Background Gradients -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-indigo-900/40 via-slate-900 to-slate-900"></div>
        <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-purple-600/30 blur-[120px] rounded-full mix-blend-screen"></div>
        <div class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-indigo-500/20 blur-[120px] rounded-full mix-blend-screen"></div>

        <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 sm:px-6 lg:px-8">
            
            <!-- Glass Card -->
            <div class="w-full max-w-4xl p-10 bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl text-center">
                
                <div class="inline-flex items-center justify-center w-20 h-20 mb-8 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/50">
                    <span class="text-4xl font-extrabold text-white">Q</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 via-white to-purple-200 mb-6">
                    Selamat Datang di Q-Les
                </h1>
                
                <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-300 mb-10 leading-relaxed">
                    Platform belajar interaktif terpadu. Hubungkan siswa dan guru tanpa batas, selesaikan masalah dengan cepat melalui layanan pelanggan cerdas kami.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group relative px-8 py-4 bg-white text-indigo-900 font-bold rounded-full overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <span class="relative z-10">Masuk ke Dashboard</span>
                                <div class="absolute inset-0 bg-indigo-50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group relative px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-full overflow-hidden shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all duration-300 hover:-translate-y-1">
                                <span class="relative z-10">Login Admin</span>
                                <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                            </a>
                        @endauth
                    @endif
                    
                    <a href="#" class="px-8 py-4 bg-transparent border border-white/30 text-white font-semibold rounded-full hover:bg-white/10 transition-colors duration-300">
                        Unduh Aplikasi (APK)
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-slate-400 text-sm">
                &copy; {{ date('Y') }} Q-Les Team. All rights reserved. Laravel v{{ Illuminate\Foundation\Application::VERSION }}
            </div>
        </div>
    </div>
</body>
</html>
