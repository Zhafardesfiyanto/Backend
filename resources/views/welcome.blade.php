<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Q-Les - Platform Belajar Interaktif Masa Kini</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.8);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 40px -10px rgba(14, 165, 233, 0.1);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(14, 165, 233, 0.2);
            border-color: rgba(56, 189, 248, 0.4);
        }
        .blob {
            position: absolute;
            filter: blur(90px);
            z-index: 0;
            opacity: 0.5;
            animation: float 10s infinite ease-in-out alternate;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -30px) scale(1.1); }
        }
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #3b82f6, #0ea5e9);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased bg-[#f8fafc] text-slate-800 selection:bg-blue-500/20 overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-sky-400 flex items-center justify-center shadow-lg shadow-blue-500/25">
                        <span class="text-xl font-black text-white">Q</span>
                    </div>
                    <span class="font-black text-2xl tracking-tight text-slate-800">Q-Les</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#beranda" class="text-slate-600 hover:text-blue-600 font-bold transition-colors">Beranda</a>
                    <a href="#fitur" class="text-slate-600 hover:text-blue-600 font-bold transition-colors">Fitur</a>
                    <a href="#tentang" class="text-slate-600 hover:text-blue-600 font-bold transition-colors">Tentang Aplikasi</a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="px-5 py-2.5 bg-slate-100 text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition-colors shadow-sm">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-slate-600 hover:text-blue-600 font-bold transition-colors">Login Admin</a>
                        <a href="#tentang" class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-sky-400 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all">Unduh Aplikasi</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Background Decor -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="blob bg-sky-300/40 w-[600px] h-[600px] rounded-full top-[-10%] left-[-10%]"></div>
        <div class="blob bg-blue-200/40 w-[500px] h-[500px] rounded-full bottom-[10%] right-[-5%]" style="animation-delay: -5s;"></div>
        <div class="blob bg-cyan-100/50 w-[400px] h-[400px] rounded-full top-[40%] left-[50%]" style="animation-delay: -2s;"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgwLDAsMCwwLjAyKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] z-0"></div>
    </div>

    <main class="relative z-10 pt-20">
        <!-- Hero Section -->
        <section id="beranda" class="min-h-[90vh] flex flex-col items-center justify-center py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-600 font-semibold text-sm mb-8 shadow-sm">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Platform Edukasi Terdepan 2026
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-[5.5rem] font-black tracking-tight text-slate-800 mb-8 leading-[1.1]">
                    Belajar Lebih <span class="text-gradient">Cerdas</span>,<br>Bukan Lebih Keras.
                </h1>
                
                <p class="max-w-3xl mx-auto text-lg md:text-xl text-slate-500 mb-12 leading-relaxed font-medium">
                    Q-Les adalah ekosistem pendidikan modern yang menghubungkan siswa, guru, dan staf administrasi dalam satu platform terpadu. Pantau nilai, kelola kelas, dan selesaikan kendala melalui layanan pelanggan cerdas kami.
                </p>

                <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                    <a href="#fitur" class="px-8 py-4 w-full sm:w-auto bg-gradient-to-r from-blue-600 to-sky-500 text-white font-bold rounded-xl overflow-hidden shadow-xl shadow-blue-500/20 hover:-translate-y-1 transition-all duration-300 text-lg">
                        Mulai Eksplorasi Fitur
                    </a>
                    <a href="#tentang" class="px-8 py-4 w-full sm:w-auto bg-white/80 backdrop-blur-sm border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-all duration-300 shadow-sm text-lg flex items-center justify-center gap-2 group">
                        <i class="fab fa-android text-emerald-500 group-hover:scale-110 transition-transform"></i> Unduh APK (Gratis)
                    </a>
                </div>

                <!-- Abstract Dashboard Preview UI -->
                <div class="mt-20 relative max-w-5xl mx-auto">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#f8fafc] via-transparent to-transparent z-10 top-1/2"></div>
                    <div class="glass-card rounded-t-3xl border-b-0 p-2 sm:p-4 mx-auto overflow-hidden shadow-2xl">
                        <div class="bg-slate-900 rounded-t-2xl w-full h-[300px] sm:h-[500px] relative overflow-hidden flex flex-col">
                            <!-- Window header -->
                            <div class="h-10 bg-slate-800/80 flex items-center px-4 gap-2 border-b border-slate-700/50">
                                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            </div>
                            <!-- App Mockup Content -->
                            <div class="flex-1 p-6 bg-slate-50 flex gap-6">
                                <!-- Sidebar Mockup -->
                                <div class="w-1/4 h-full bg-white rounded-xl shadow-sm border border-slate-100 p-4 hidden md:flex flex-col gap-4">
                                    <div class="w-2/3 h-6 bg-slate-200 rounded mb-4"></div>
                                    <div class="w-full h-10 bg-blue-50 rounded-lg"></div>
                                    <div class="w-full h-10 bg-slate-50 hover:bg-slate-100 rounded-lg"></div>
                                    <div class="w-full h-10 bg-slate-50 hover:bg-slate-100 rounded-lg"></div>
                                    <div class="w-full h-10 bg-slate-50 hover:bg-slate-100 rounded-lg"></div>
                                </div>
                                <!-- Main Content Mockup -->
                                <div class="flex-1 h-full flex flex-col gap-6">
                                    <!-- Top Banner -->
                                    <div class="w-full h-40 bg-gradient-to-r from-blue-500 to-sky-400 rounded-2xl shadow-inner p-8 flex flex-col justify-center relative overflow-hidden">
                                        <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                                         <div class="w-1/3 h-6 bg-white/40 rounded mb-3"></div>
                                         <div class="w-1/2 h-10 bg-white/80 rounded"></div>
                                    </div>
                                    <!-- Cards Row -->
                                    <div class="flex gap-6 flex-1">
                                        <div class="w-2/3 h-full bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col gap-4">
                                            <div class="w-1/4 h-5 bg-slate-200 rounded"></div>
                                            <div class="flex-1 bg-slate-50 rounded-xl border border-slate-100"></div>
                                        </div>
                                        <div class="w-1/3 h-full bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col gap-4">
                                            <div class="w-1/2 h-5 bg-slate-200 rounded"></div>
                                            <div class="w-full h-12 bg-slate-50 rounded-lg"></div>
                                            <div class="w-full h-12 bg-slate-50 rounded-lg"></div>
                                            <div class="w-full h-12 bg-slate-50 rounded-lg"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="fitur" class="py-24 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class="text-sm font-bold text-blue-600 tracking-widest uppercase mb-3">Penjelasan Sistem</h2>
                    <h3 class="text-3xl md:text-5xl font-black text-slate-800">Kenapa Menggunakan Q-Les?</h3>
                    <p class="mt-5 text-slate-500 text-lg max-w-2xl mx-auto font-medium">Platform kami dirancang khusus untuk memenuhi standar pendidikan digital modern, memastikan keamanan, kecepatan, dan kenyamanan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-3">Analitik & Laporan Real-Time</h4>
                        <p class="text-slate-500 font-medium leading-relaxed">Guru dan Admin dapat memantau perkembangan nilai siswa, statistik kelas, hingga kehadiran secara langsung melalui dashboard interaktif.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group">
                        <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-sky-500 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-3">Manajemen Kelas Dinamis</h4>
                        <p class="text-slate-500 font-medium leading-relaxed">Kelola mata pelajaran, jadwalkan ujian, dan distribusikan materi dengan mudah menggunakan sistem drag-and-drop dan organisasi visual.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group">
                        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-3">Aplikasi Mobile Kustom</h4>
                        <p class="text-slate-500 font-medium leading-relaxed">Q-Les hadir dalam bentuk aplikasi Android (APK) yang sangat responsif, dibangun dengan Flutter untuk pengalaman belajar tanpa lag.</p>
                    </div>
                    
                    <!-- Feature 4 -->
                    <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group">
                        <div class="w-14 h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-3">Customer Service Cerdas</h4>
                        <p class="text-slate-500 font-medium leading-relaxed">Sistem tiket dukungan dan live chat terintegrasi langsung antara aplikasi siswa dan dashboard admin untuk penyelesaian masalah yang instan.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group lg:col-span-2">
                        <div class="flex flex-col md:flex-row gap-8 items-center h-full">
                            <div class="flex-1">
                                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h4 class="text-2xl font-bold text-slate-800 mb-3">Keamanan Tinggi & Cloud Sync</h4>
                                <p class="text-slate-500 font-medium leading-relaxed mb-4">
                                    Q-Les didukung oleh <strong>Laravel Sanctum</strong> untuk pengamanan API dan <strong>Firebase Cloud Firestore</strong> untuk sinkronisasi pesan seketika (real-time chat). Data Anda selalu terenkripsi dan dicadangkan secara otomatis.
                                </p>
                            </div>
                            <div class="w-full md:w-1/3 bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col gap-3">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-emerald-500"></i>
                                    <span class="text-sm font-bold text-slate-700">Firebase Auth Google</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-emerald-500"></i>
                                    <span class="text-sm font-bold text-slate-700">Real-time DB Sync</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-emerald-500"></i>
                                    <span class="text-sm font-bold text-slate-700">Role-based Access Control</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA/Download Section -->
        <section id="tentang" class="py-24 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Dark Card Contrast -->
                <div class="bg-gradient-to-br from-slate-900 via-[#0f172a] to-blue-950 rounded-[3rem] p-10 md:p-16 relative overflow-hidden shadow-2xl">
                    <!-- Glow effects inside the dark card -->
                    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/20 blur-[100px] rounded-full pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-sky-500/10 blur-[80px] rounded-full pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                        <div class="flex-1 text-center lg:text-left">
                            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-[1.2]">Tingkatkan Kualitas Kelas Anda Hari Ini.</h2>
                            <p class="text-blue-100/70 text-lg md:text-xl mb-10 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                                Ribuan guru dan siswa telah beralih ke sistem digital. Unduh aplikasi Q-Les untuk Android atau login ke Dashboard Admin untuk mengatur instansi Anda.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="#" class="px-8 py-4 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-100 transition-colors shadow-xl text-center flex items-center justify-center gap-3 group">
                                    <i class="fab fa-android text-2xl text-emerald-500 group-hover:scale-110 transition-transform"></i> 
                                    <div class="text-left">
                                        <div class="text-[10px] uppercase tracking-wider text-slate-500">Tersedia Untuk Android</div>
                                        <div class="leading-none mt-0.5">Unduh APK</div>
                                    </div>
                                </a>
                                <a href="{{ route('login') }}" class="px-8 py-4 bg-white/10 border border-white/20 text-white font-bold rounded-xl hover:bg-white/20 transition-all backdrop-blur-md text-center flex items-center justify-center">
                                    Akses Panel Admin
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex-1 w-full flex justify-center lg:justify-end">
                            <!-- Floating 3D-like Box Illustration -->
                            <div class="w-full max-w-sm aspect-square relative group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-blue-500 to-sky-400 rounded-[2.5rem] transform rotate-6 opacity-60 blur-xl group-hover:rotate-12 group-hover:scale-105 transition-all duration-500"></div>
                                <div class="absolute inset-0 bg-slate-800/50 border border-white/10 rounded-[2.5rem] backdrop-blur-xl shadow-2xl flex flex-col items-center justify-center p-8 group-hover:-translate-y-2 transition-all duration-500">
                                    <div class="w-28 h-28 bg-gradient-to-br from-blue-400 to-sky-400 rounded-full flex items-center justify-center mb-8 shadow-[0_0_40px_rgba(56,189,248,0.4)] animate-pulse" style="animation-duration: 3s;">
                                        <i class="fas fa-rocket text-5xl text-white"></i>
                                    </div>
                                    <h4 class="text-3xl font-black text-white mb-2">Q-Les App</h4>
                                    <div class="px-4 py-1.5 rounded-full bg-white/10 text-sky-300 text-sm font-bold border border-white/10">v1.2.0 Stable Release</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/60 pt-16 pb-8 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center shadow-md">
                        <span class="text-lg font-black text-white">Q</span>
                    </div>
                    <span class="font-black text-2xl text-slate-800 tracking-tight">Q-Les</span>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-500 transition-all"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white transition-all"><i class="fab fa-github text-lg"></i></a>
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 font-medium text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} Tim Pengembangan Q-Les. Hak Cipta Dilindungi. Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                </p>
                <div class="flex gap-6 text-sm font-bold text-slate-500">
                    <a href="#" class="hover:text-blue-600 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-blue-600 transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-blue-600 transition-colors">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
