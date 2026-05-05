# Requirements Document

## Introduction

Fitur ini adalah redesign menyeluruh pada halaman admin dashboard aplikasi Q-Les berbasis Laravel 12 + Blade. Tujuannya adalah mengganti tampilan dashboard yang ada saat ini dengan desain "AdminPro" yang lebih modern, fungsional, dan informatif — mencakup sidebar navigasi, stats cards, grafik, tabel data, serta dua halaman baru: Manajemen Pengguna (dengan integrasi Firebase Authentication) dan Customer Service (pengelolaan support tickets).

Proyek ini menggunakan Laravel 12, Blade templating, Tailwind CSS via Vite, SQLite (XAMPP), dan middleware `IsSuperAdmin` untuk proteksi akses.

---

## Glossary

- **Dashboard**: Halaman utama admin yang menampilkan ringkasan statistik dan aktivitas sistem.
- **Admin**: Pengguna dengan role `super_admin` yang telah melewati middleware `IsSuperAdmin`.
- **Sidebar**: Panel navigasi vertikal di sisi kiri layar yang berisi menu-menu utama.
- **Stats_Card**: Komponen kartu yang menampilkan satu angka statistik utama beserta label dan ikon.
- **Revenue_Chart**: Grafik garis (line chart) yang menampilkan data pendapatan per periode waktu.
- **Category_Chart**: Grafik donat (donut chart) yang menampilkan distribusi data berdasarkan kategori.
- **Orders_Table**: Tabel yang menampilkan daftar pesanan/submission terbaru.
- **New_Users_List**: Daftar pengguna yang baru mendaftar.
- **User_Management_Page**: Halaman yang menampilkan dan mengelola semua akun pengguna.
- **Firebase_Auth**: Layanan autentikasi dari Google Firebase yang menyimpan akun pengguna mobile.
- **Firebase_Admin_SDK**: Library server-side untuk mengakses Firebase dari backend Laravel.
- **CS_Page**: Halaman Customer Service untuk mengelola support tickets dari pengguna.
- **Support_Ticket**: Entitas yang merepresentasikan pesan/permintaan bantuan dari pengguna.
- **SupportTicket_Model**: Model Eloquent `App\Models\SupportTicket` yang merepresentasikan tabel `support_tickets`.
- **SuperAdminController**: Controller `App\Http\Controllers\Admin\SuperAdminController` yang menangani semua request halaman admin.
- **Blade_Layout**: Template Blade induk yang mendefinisikan struktur HTML umum (sidebar, header) yang diwarisi halaman-halaman admin.

---

## Requirements

### Requirement 1: Layout Utama Admin (Blade Layout)

**User Story:** Sebagai Admin, saya ingin semua halaman admin memiliki struktur layout yang konsisten dengan sidebar dan header, sehingga navigasi antar halaman terasa seragam dan efisien.

#### Acceptance Criteria

1. THE Blade_Layout SHALL menyediakan sidebar gelap di sisi kiri yang selalu terlihat pada semua halaman admin.
2. THE Blade_Layout SHALL menampilkan menu navigasi di sidebar dengan item: Dashboard, Pengguna, Pesanan, Laporan, Pengaturan, Pesan, Notifikasi, Bantuan, dan Keluar.
3. WHEN Admin mengklik item menu di sidebar, THE Blade_Layout SHALL menandai item tersebut sebagai aktif secara visual (highlight berbeda dari item lain).
4. THE Blade_Layout SHALL menampilkan header di bagian atas konten utama yang memuat nama Admin yang sedang login dan ikon notifikasi.
5. THE Blade_Layout SHALL menggunakan Tailwind CSS untuk seluruh styling dan dirender melalui Vite asset pipeline.
6. IF Admin mengakses halaman admin tanpa sesi login yang valid, THEN THE Blade_Layout SHALL mengarahkan Admin ke halaman login.
7. IF Admin mengakses halaman admin dengan role selain `super_admin`, THEN THE Blade_Layout SHALL menampilkan respons HTTP 403.

---

### Requirement 2: Halaman Dashboard Utama

**User Story:** Sebagai Admin, saya ingin melihat ringkasan statistik sistem secara sekilas di halaman dashboard, sehingga saya dapat memantau kondisi platform tanpa harus membuka banyak halaman.

#### Acceptance Criteria

1. WHEN Admin membuka halaman dashboard, THE Dashboard SHALL menampilkan empat Stats_Card dengan data: Total Pengguna, Total Pesanan (Submissions), Total Pendapatan, dan Produk Terjual.
2. THE Dashboard SHALL menampilkan Revenue_Chart berupa line chart yang menggambarkan tren data per periode.
3. THE Dashboard SHALL menampilkan Category_Chart berupa donut chart yang menggambarkan distribusi data berdasarkan kategori.
4. THE Dashboard SHALL menampilkan Orders_Table berisi maksimal 10 entri submission/pesanan terbaru dengan kolom: ID, Nama Pengguna, Status, dan Tanggal.
5. THE Dashboard SHALL menampilkan New_Users_List berisi maksimal 5 pengguna yang paling baru mendaftar.
6. WHEN data untuk Stats_Card tidak tersedia atau bernilai nol, THE Dashboard SHALL menampilkan angka `0` sebagai nilai default tanpa error.
7. THE SuperAdminController SHALL menyediakan semua data yang dibutuhkan Dashboard melalui method `index()` menggunakan Eloquent queries ke model `User` dan `Submission`.

---

### Requirement 3: Halaman Manajemen Pengguna

**User Story:** Sebagai Admin, saya ingin melihat dan mengelola semua akun pengguna — baik dari database lokal maupun dari Firebase Authentication — di satu halaman terpadu, sehingga saya dapat mengawasi seluruh ekosistem pengguna platform.

#### Acceptance Criteria

1. WHEN Admin membuka halaman Manajemen Pengguna, THE User_Management_Page SHALL menampilkan daftar semua akun dari database lokal (tabel `users`) dalam bentuk tabel dengan kolom: Nama, Email, Role, dan Tanggal Daftar.
2. THE User_Management_Page SHALL mendukung pencarian pengguna berdasarkan nama atau email melalui query parameter `search` pada URL.
3. THE User_Management_Page SHALL menampilkan data pengguna dalam format paginasi dengan 20 entri per halaman.
4. WHEN Admin mengklik tombol hapus pada baris pengguna, THE User_Management_Page SHALL menampilkan konfirmasi sebelum mengirim request DELETE ke `SuperAdminController`.
5. WHEN penghapusan pengguna berhasil, THE SuperAdminController SHALL mencatat aksi tersebut ke `AuditLog` dengan action `DELETE_USER`.
6. THE User_Management_Page SHALL menampilkan bagian terpisah yang memuat daftar akun dari Firebase_Auth yang diambil melalui Firebase_Admin_SDK dari backend Laravel.
7. WHEN Firebase_Admin_SDK berhasil dipanggil, THE User_Management_Page SHALL menampilkan kolom: Firebase UID, Email, dan Status Verifikasi Email untuk setiap akun Firebase.
8. IF Firebase_Admin_SDK gagal dipanggil atau mengalami timeout, THEN THE User_Management_Page SHALL menampilkan pesan error yang informatif tanpa menyebabkan halaman crash.
9. THE SuperAdminController SHALL menyediakan method khusus untuk memanggil Firebase_Admin_SDK dan mengembalikan data akun Firebase ke view.

---

### Requirement 4: Integrasi Firebase Admin SDK

**User Story:** Sebagai Admin, saya ingin backend Laravel dapat berkomunikasi dengan Firebase Authentication, sehingga saya bisa melihat semua akun yang terdaftar di Firebase langsung dari dashboard.

#### Acceptance Criteria

1. THE SuperAdminController SHALL menggunakan Firebase_Admin_SDK (via package PHP) untuk mengambil daftar akun dari Firebase Authentication.
2. WHEN Firebase_Admin_SDK dipanggil, THE SuperAdminController SHALL mengembalikan data berupa koleksi yang memuat: `uid`, `email`, dan `emailVerified` untuk setiap akun.
3. IF koneksi ke Firebase gagal atau kredensial tidak valid, THEN THE SuperAdminController SHALL menangkap exception dan mengembalikan array kosong beserta pesan error ke view.
4. THE SuperAdminController SHALL membaca kredensial Firebase dari file konfigurasi atau environment variable, bukan dari nilai yang di-hardcode dalam kode sumber.
5. WHERE konfigurasi Firebase tersedia, THE SuperAdminController SHALL membatasi jumlah akun yang diambil dari Firebase maksimal 1000 akun per request untuk menghindari timeout.

---

### Requirement 5: Halaman Customer Service (CS)

**User Story:** Sebagai Admin, saya ingin mengelola support tickets dari pengguna di satu halaman khusus, sehingga saya dapat merespons dan menyelesaikan permintaan bantuan secara terstruktur.

#### Acceptance Criteria

1. WHEN Admin membuka halaman CS, THE CS_Page SHALL menampilkan daftar semua Support_Ticket dalam bentuk tabel dengan kolom: ID, Nama Pengguna, Subjek, Status, dan Tanggal Dibuat.
2. THE CS_Page SHALL menampilkan Support_Ticket dalam format paginasi dengan 15 entri per halaman, diurutkan dari yang terbaru.
3. THE CS_Page SHALL menyediakan filter berdasarkan status tiket (misalnya: `open`, `in_progress`, `closed`) melalui query parameter pada URL.
4. WHEN Admin mengklik tiket, THE CS_Page SHALL menampilkan detail tiket termasuk isi pesan dari pengguna.
5. THE CS_Page SHALL menyediakan form untuk Admin membalas atau mengubah status Support_Ticket.
6. WHEN Admin mengubah status tiket, THE SuperAdminController SHALL menyimpan perubahan ke database dan mencatat aksi ke `AuditLog` dengan action `UPDATE_TICKET`.
7. IF tidak ada Support_Ticket yang tersedia, THEN THE CS_Page SHALL menampilkan pesan "Tidak ada tiket saat ini." tanpa error.

---

### Requirement 6: Skema Database Support Tickets

**User Story:** Sebagai Developer, saya ingin tabel `support_tickets` memiliki kolom yang cukup untuk menyimpan data tiket dukungan, sehingga fitur CS Page dapat berfungsi dengan benar.

#### Acceptance Criteria

1. THE SupportTicket_Model SHALL memiliki kolom: `user_id` (foreign key ke `users`), `subject` (string), `message` (text), `status` (enum: `open`, `in_progress`, `closed`), `admin_reply` (text, nullable), dan `timestamps`.
2. THE SupportTicket_Model SHALL mendefinisikan relasi `belongsTo` ke model `User` melalui kolom `user_id`.
3. THE SupportTicket_Model SHALL mendefinisikan `$fillable` yang mencakup semua kolom yang dapat diisi secara massal.
4. WHEN migrasi dijalankan, THE SupportTicket_Model SHALL membuat tabel `support_tickets` dengan semua kolom yang didefinisikan pada kriteria 1.
5. IF `user_id` yang direferensikan dihapus dari tabel `users`, THEN THE SupportTicket_Model SHALL mengatur kolom `user_id` menjadi `null` (cascade set null) untuk menjaga integritas data.

---

### Requirement 7: Keamanan dan Proteksi Akses

**User Story:** Sebagai Developer, saya ingin semua halaman admin dilindungi oleh middleware autentikasi dan otorisasi, sehingga hanya Super Admin yang dapat mengakses fitur-fitur tersebut.

#### Acceptance Criteria

1. THE Blade_Layout SHALL memastikan semua route admin terdaftar dalam grup middleware `['auth', 'superadmin']` di `routes/web.php`.
2. WHEN pengguna yang tidak terautentikasi mencoba mengakses route admin, THE Blade_Layout SHALL mengarahkan pengguna ke halaman login.
3. WHEN pengguna yang terautentikasi tetapi bukan `super_admin` mencoba mengakses route admin, THE Blade_Layout SHALL mengembalikan respons HTTP 403.
4. THE SuperAdminController SHALL memvalidasi semua input dari form (pencarian, update tiket) menggunakan Laravel Form Request atau method `validate()` sebelum memproses data.
5. IF input mengandung karakter berbahaya atau melebihi batas panjang yang ditentukan, THEN THE SuperAdminController SHALL menolak request dan mengembalikan pesan validasi yang sesuai.
