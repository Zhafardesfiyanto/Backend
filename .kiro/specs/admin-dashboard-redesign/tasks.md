# Implementation Plan: Admin Dashboard Redesign

## Overview

Implementasi redesign menyeluruh halaman admin Q-Les dari tampilan Bootstrap lama menjadi desain "AdminPro" modern menggunakan Tailwind CSS. Mencakup: Blade layout baru dengan sidebar, halaman Dashboard (stats + charts), halaman Manajemen Pengguna (lokal + Firebase), dan halaman Customer Service (support tickets CRUD). Stack: Laravel 12, Blade, Tailwind CSS via Vite, Chart.js via CDN, Firebase Admin SDK PHP (`kreait/firebase-php`), SQLite.

## Tasks

- [x] 1. Siapkan database: migration baru untuk kolom `support_tickets`
  - Buat migration baru `add_columns_to_support_tickets_table` — jangan ubah migration lama
  - Tambahkan kolom: `user_id` (foreignId, nullable, constrained, nullOnDelete), `subject` (string), `message` (text), `status` (enum: open/in_progress/closed, default: open), `admin_reply` (text, nullable)
  - Jalankan `php artisan migrate`
  - _Requirements: 6.1, 6.4, 6.5_

- [x] 2. Update model `SupportTicket` dan tambahkan relasi
  - [x] 2.1 Update `app/Models/SupportTicket.php`
    - Tambahkan `$fillable`: `['user_id', 'subject', 'message', 'status', 'admin_reply']`
    - Tambahkan method `user()`: `belongsTo(User::class)`
    - _Requirements: 6.2, 6.3_

  - [ ]* 2.2 Tulis property test untuk cascade nullify (Property 13)
    - **Property 13: Cascade nullify pada penghapusan user**
    - Buat user dengan beberapa support tickets, hapus user, verifikasi semua `user_id` pada tickets menjadi `null`
    - **Validates: Requirements 6.5**

- [x] 3. Buat Blade layout utama admin (`layouts/admin.blade.php`)
  - Buat file `resources/views/layouts/admin.blade.php`
  - Struktur: `<html>` → `<head>` (Tailwind via `@vite`, Chart.js CDN, Font Awesome CDN) → `<body class="bg-gray-100">` → flex container (sidebar + main area)
  - Sidebar (`w-60 bg-[#1e2a3a] flex-shrink-0 h-screen`): logo Q-LES HQ, menu navigasi (Dashboard, Pengguna, Pesanan, Laporan, Pengaturan, Pesan, Notifikasi, Bantuan, Keluar/Logout form)
  - Active state: gunakan `request()->routeIs(...)` — item aktif mendapat class `bg-blue-600 text-white`, non-aktif `text-gray-300 hover:bg-gray-700 hover:text-white`
  - Header (`bg-white shadow-sm h-16`): judul halaman kiri, nama admin + avatar kanan (`auth()->user()->name`)
  - Main content: `<main class="flex-1 overflow-y-auto p-6">@yield('content')</main>`
  - `@stack('scripts')` sebelum `</body>`
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

  - [ ]* 3.1 Tulis property test untuk active state sidebar (Property 3)
    - **Property 3: Active state sidebar sesuai dengan route aktif**
    - Untuk setiap route admin yang valid, verifikasi hanya satu item sidebar yang memiliki class aktif
    - **Validates: Requirements 1.3**

  - [ ]* 3.2 Tulis property test untuk header menampilkan nama admin (Property 4)
    - **Property 4: Header selalu menampilkan nama admin yang sedang login**
    - Untuk berbagai user super_admin, verifikasi nama user muncul di header
    - **Validates: Requirements 1.4**

- [x] 4. Buat komponen Blade reusable untuk sidebar dan stats card
  - Buat `resources/views/components/admin/sidebar-item.blade.php` — props: `$route`, `$icon`, `$label`; render `<a>` dengan active state logic
  - Buat `resources/views/components/admin/stats-card.blade.php` — props: `$label`, `$value`, `$icon`, `$color`; render kartu statistik Tailwind
  - _Requirements: 1.1, 2.1_

- [x] 5. Refactor `SuperAdminController@index` untuk data dashboard baru
  - Update method `index()` di `app/Http/Controllers/Admin/SuperAdminController.php`
  - Hapus logika HTTP call ke API eksternal yang lama
  - Tambahkan: `$totalUsers = User::count()`, `$totalSubmissions = Submission::count()`, `$totalRevenue = 0`, `$totalProducts = 0`
  - Tambahkan: `$recentSubmissions = Submission::with('student')->latest()->take(10)->get()`
  - Tambahkan: `$newUsers = User::latest()->take(5)->get()`
  - Tambahkan private method `getRevenueChartData()`: query `Submission::count()` per bulan untuk 6 bulan terakhir, return array `['labels' => [...], 'values' => [...]]`
  - Tambahkan private method `getCategoryChartData()`: query `User::where('role', ...)` per role, return array `['labels' => [...], 'values' => [...]]`
  - Return `view('hq-admin.dashboard', compact(...))` dengan semua variabel baru
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7_

  - [ ]* 5.1 Tulis property test untuk orders table limit (Property 5)
    - **Property 5: Orders table dibatasi maksimal 10 entri terbaru**
    - Untuk berbagai jumlah N submissions, verifikasi `min(N, 10)` entri dikembalikan, diurutkan terbaru
    - **Validates: Requirements 2.4**

  - [ ]* 5.2 Tulis property test untuk new users list limit (Property 6)
    - **Property 6: New Users list dibatasi maksimal 5 entri terbaru**
    - Untuk berbagai jumlah N users, verifikasi `min(N, 5)` entri dikembalikan, diurutkan terbaru
    - **Validates: Requirements 2.5**

- [x] 6. Buat halaman Dashboard baru (`hq-admin/dashboard.blade.php`)
  - Ganti seluruh isi `resources/views/hq-admin/dashboard.blade.php` — extend `layouts.admin`
  - Section `content`: 4 stats cards (Total Pengguna, Total Pesanan, Total Pendapatan, Produk Terjual) menggunakan komponen `<x-admin.stats-card>`
  - Revenue line chart: `<canvas id="revenueChart">` — inisialisasi Chart.js di `@push('scripts')` dengan data dari `$revenueData`
  - Category donut chart: `<canvas id="categoryChart">` — inisialisasi Chart.js di `@push('scripts')` dengan data dari `$categoryData`
  - Orders table: loop `$recentSubmissions` — kolom ID, Nama Pengguna (`student->name ?? 'N/A'`), Status, Tanggal; `@empty` → "Belum ada data submission."
  - New Users list: loop `$newUsers` — tampilkan nama + email; `@empty` → "Belum ada pengguna terdaftar."
  - Semua stats card default ke `0` jika nilai null
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6_

- [x] 7. Checkpoint — Pastikan semua tests pass
  - Pastikan semua tests pass, tanya user jika ada pertanyaan.

- [x] 8. Refactor `SuperAdminController@users` untuk pencarian, paginasi, dan Firebase
  - Update method `users(Request $request)` di `SuperAdminController`
  - Tambahkan query parameter `search`: filter `where('name', 'like', "%{$search}%")->orWhere('email', 'like', ...)`
  - Paginasi 20 per halaman dengan `->paginate(20)->withQueryString()`
  - Tambahkan private method `getFirebaseUsers()`: install `kreait/firebase-php` via composer, gunakan `Factory` + `config('firebase.credentials')`, list users max 1000, return array `[['uid', 'email', 'emailVerified'], ...]`
  - Tangkap semua exception di `getFirebaseUsers()` — return `['error' => '...', 'data' => []]`
  - Baca kredensial dari `config('firebase.credentials')` atau env variable, bukan hardcode
  - Return `view('hq-admin.users', compact('users', 'firebaseUsers', 'search'))`
  - _Requirements: 3.1, 3.2, 3.3, 3.6, 3.7, 3.8, 3.9, 4.1, 4.2, 4.3, 4.4, 4.5_

  - [ ]* 8.1 Tulis property test untuk pencarian user (Property 7)
    - **Property 7: Pencarian user hanya mengembalikan hasil yang relevan**
    - Gunakan PHPUnit data provider dengan 100+ kombinasi query dan data user
    - Verifikasi semua hasil mengandung query (case-insensitive) di nama atau email
    - **Validates: Requirements 3.2**

  - [ ]* 8.2 Tulis property test untuk Firebase error handling (Property 9)
    - **Property 9: Firebase error handling tidak menyebabkan crash**
    - Mock Firebase SDK untuk melempar berbagai jenis exception
    - Verifikasi return selalu berupa array dengan key `error` (string) dan `data` (array kosong)
    - **Validates: Requirements 3.8, 4.3**

  - [ ]* 8.3 Tulis property test untuk struktur data Firebase (Property 10)
    - **Property 10: Firebase data selalu memiliki field uid, email, emailVerified**
    - Mock Firebase SDK dengan response valid, verifikasi setiap item memiliki tepat 3 field tersebut
    - **Validates: Requirements 4.2, 3.7**

- [x] 9. Buat halaman Manajemen Pengguna (`hq-admin/users.blade.php`)
  - Buat file baru `resources/views/hq-admin/users.blade.php` — extend `layouts.admin`
  - Form pencarian: `<input name="search">` dengan value `{{ $search }}`, submit GET ke `route('admin.users')`
  - Tabel pengguna lokal: kolom Nama, Email, Role, Tanggal Daftar; loop `$users`; tombol hapus dengan konfirmasi JS (`confirm(...)`) yang submit form DELETE ke route `admin.users.destroy`
  - Paginasi: `{{ $users->links() }}`
  - Bagian Firebase: cek `isset($firebaseUsers['error'])` → tampilkan alert merah; jika tidak ada error, tampilkan tabel kolom Firebase UID, Email, Status Verifikasi
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.6, 3.7, 3.8_

- [x] 10. Tambahkan route dan method `destroyUser` untuk hapus pengguna
  - Pastikan method `destroyUser($id)` di `SuperAdminController` sudah ada (sudah ada, verifikasi saja)
  - Tambahkan route `DELETE /hq-admin/users/{id}` di `routes/web.php` dalam grup middleware `['auth', 'superadmin']`, name: `admin.users.destroy`
  - Pastikan `destroyUser` memanggil `AuditLog::record(...)` dengan action `DELETE_USER` sebelum `$user->delete()`
  - _Requirements: 3.4, 3.5_

  - [ ]* 10.1 Tulis property test untuk audit log saat hapus user (Property 8)
    - **Property 8: Penghapusan user selalu menghasilkan AuditLog entry**
    - Untuk berbagai user yang dihapus, verifikasi tepat satu entry AuditLog dengan `action = 'DELETE_USER'` dan `target_id` yang sesuai
    - **Validates: Requirements 3.5**

- [x] 11. Refactor `SuperAdminController@serviceCenter` untuk support tickets
  - Update method `serviceCenter(Request $request)` di `SuperAdminController`
  - Query: `SupportTicket::with('user')->when($status, fn($q) => $q->where('status', $status))->latest()->paginate(15)->withQueryString()`
  - Tambahkan method `updateTicket(Request $request, SupportTicket $ticket)`:
    - Validasi: `status` required|in:open,in_progress,closed; `admin_reply` nullable|string|max:2000
    - `$ticket->update($validated)`
    - `AuditLog::record(auth()->id(), 'UPDATE_TICKET', 'support_tickets', $ticket->id, "...")`
    - Return `back()->with('success', 'Tiket berhasil diperbarui.')`
  - Tambahkan route `PATCH /hq-admin/service-center/{ticket}` di `routes/web.php`, name: `admin.service.update`
  - _Requirements: 5.1, 5.2, 5.3, 5.5, 5.6, 7.4, 7.5_

  - [ ]* 11.1 Tulis property test untuk filter status tiket (Property 11)
    - **Property 11: Filter status tiket hanya mengembalikan tiket dengan status yang diminta**
    - Untuk setiap nilai filter valid (open, in_progress, closed), verifikasi semua tiket yang dikembalikan memiliki status yang sama
    - **Validates: Requirements 5.3**

  - [ ]* 11.2 Tulis property test untuk audit log saat update tiket (Property 12)
    - **Property 12: Update tiket selalu menghasilkan AuditLog entry**
    - Untuk berbagai tiket yang diupdate, verifikasi tepat satu entry AuditLog dengan `action = 'UPDATE_TICKET'` dan `target_id` yang sesuai
    - **Validates: Requirements 5.6**

  - [ ]* 11.3 Tulis property test untuk validasi input update tiket (Property 14)
    - **Property 14: Input validasi menolak semua input tidak valid**
    - Untuk berbagai nilai status tidak valid dan admin_reply > 2000 karakter, verifikasi response HTTP 422
    - **Validates: Requirements 7.4, 7.5**

- [x] 12. Buat halaman Customer Service baru (`hq-admin/service-center.blade.php`)
  - Ganti seluruh isi `resources/views/hq-admin/service-center.blade.php` — extend `layouts.admin`
  - Filter status: 4 tombol/link (Semua, Open, In Progress, Closed) yang append `?status=...` ke URL; tombol aktif di-highlight
  - Tabel tiket: kolom ID, Nama Pengguna (`ticket->user->name ?? 'Anonim'`), Subjek, Status (badge warna berbeda per status), Tanggal Dibuat
  - Klik tiket: tampilkan detail tiket (isi pesan) — bisa menggunakan Alpine.js toggle atau section terpisah di bawah tabel
  - Form reply/update status: `<form method="POST" action="{{ route('admin.service.update', $ticket) }}">` dengan `@method('PATCH')`, select status, textarea admin_reply, tombol submit
  - `@empty` → "Tidak ada tiket saat ini."
  - Tampilkan flash message `session('success')` jika ada
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7_

- [x] 13. Perbaiki konfigurasi route di `routes/web.php`
  - Hapus duplikasi route yang ada (saat ini ada dua grup dengan route yang sama)
  - Pastikan semua route admin berada dalam satu grup `middleware(['auth', 'superadmin'])`
  - Tambahkan route baru: `DELETE /hq-admin/users/{id}` (name: `admin.users.destroy`) dan `PATCH /hq-admin/service-center/{ticket}` (name: `admin.service.update`)
  - Tambahkan route `GET /hq-admin/service-center` (name: `admin.service`) jika belum ada dengan nama tersebut
  - _Requirements: 7.1, 7.2, 7.3_

  - [ ]* 13.1 Tulis property test untuk akses tanpa autentikasi (Property 1)
    - **Property 1: Akses tanpa autentikasi selalu redirect ke login**
    - Untuk setiap route admin, verifikasi unauthenticated request mendapat redirect 302 ke `/login`
    - **Validates: Requirements 1.6, 7.2**

  - [ ]* 13.2 Tulis property test untuk akses role non-super_admin (Property 2)
    - **Property 2: Akses dengan role non-super_admin selalu menghasilkan 403**
    - Gunakan PHPUnit data provider dengan kombinasi role (student, teacher, dll.) × route admin
    - **Validates: Requirements 1.7, 7.3**

- [x] 14. Install `kreait/firebase-php` dan buat konfigurasi Firebase
  - Jalankan `composer require kreait/firebase-php:^7.0`
  - Buat file `config/firebase.php` dengan key `credentials` yang membaca dari `env('FIREBASE_CREDENTIALS')`
  - Tambahkan `FIREBASE_CREDENTIALS=` ke `.env.example` (tanpa nilai sensitif)
  - _Requirements: 4.1, 4.4_

- [x] 15. Checkpoint akhir — Pastikan semua tests pass
  - Jalankan `php artisan test --testsuite=AdminDashboard`
  - Pastikan semua tests pass, tanya user jika ada pertanyaan.

## Notes

- Tasks bertanda `*` bersifat opsional dan dapat dilewati untuk MVP yang lebih cepat
- Setiap task mereferensikan requirements spesifik untuk traceability
- Checkpoint memastikan validasi inkremental
- Property tests menggunakan PHPUnit data providers (minimum 100 iterasi per property)
- Unit tests memvalidasi contoh spesifik dan edge cases
- `kreait/firebase-php` harus diinstall sebelum mengerjakan task 8 dan 9
- Migration baru (task 1) harus dijalankan sebelum mengerjakan task 11 dan 12
- Layout baru (task 3) harus selesai sebelum mengerjakan task 6, 9, dan 12
