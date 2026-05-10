# 🔐 Firebase Google Auth dengan Laravel + Dart

Panduan lengkap menyambungkan Laravel Backend dengan Firebase Google Authentication dan Flutter Dart Frontend.

## 📋 Daftar Isi

1. [Setup Laravel Backend](#setup-laravel-backend)
2. [Setup Firebase Project](#setup-firebase-project)
3. [Setup Flutter/Dart Frontend](#setup-flutterdart-frontend)
4. [API Documentation](#api-documentation)
5. [Troubleshooting](#troubleshooting)

---

## 🔧 Setup Laravel Backend

### Step 1: Install Firebase Admin SDK

```bash
composer require kreait/firebase-php
```

### Step 2: Setup Firebase Credentials

**Langkah 1:** Download file `serviceAccountKey.json` dari Firebase Console:
- Buka [Firebase Console](https://console.firebase.google.com)
- Pilih project Anda
- Settings (⚙️) → Service Accounts → Generate New Private Key
- Simpan file `serviceAccountKey.json` di folder project

**Langkah 2:** Update `.env`:

```env
FIREBASE_CREDENTIALS=/path/to/serviceAccountKey.json
```

Atau copy path lengkapnya:

```bash
# Windows
FIREBASE_CREDENTIALS=C:\xampp\htdocs\Backend\storage\firebase\serviceAccountKey.json

# Linux/Mac
FIREBASE_CREDENTIALS=/var/www/html/Backend/storage/firebase/serviceAccountKey.json
```

**Langkah 3:** Buat folder storage:

```bash
mkdir -p storage/firebase
# Copy serviceAccountKey.json ke folder ini
```

### Step 3: Update Database

Pastikan table `users` memiliki kolom `firebase_uid`:

```bash
php artisan migrate
```

Jika belum ada, buat migration baru:

```bash
php artisan make:migration add_firebase_uid_to_users_table
```

Edit file migration:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('firebase_uid')->unique()->nullable();
    $table->string('profile_picture')->nullable();
});
```

Jalankan:

```bash
php artisan migrate
```

### Step 4: Verifikasi Controller

File controller sudah ada di: `app/Http/Controllers/Auth/FirebaseAuthController.php`

Cek juga routes API di: `routes/api.php`

### Step 5: Test Laravel API

Start server Laravel:

```bash
php artisan serve
```

Test endpoint dengan Postman/cURL:

```bash
# Get daftar Google users
curl http://localhost:8000/api/auth/google-users

# Test login (butuh ID token dari Firebase)
curl -X POST http://localhost:8000/api/auth/firebase-login \
  -H "Content-Type: application/json" \
  -d '{"id_token":"YOUR_FIREBASE_ID_TOKEN"}'
```

---

## 🔥 Setup Firebase Project

### Step 1: Create Firebase Project

1. Buka [Firebase Console](https://console.firebase.google.com)
2. Click "Add project" → Isi nama project
3. Enable Google Analytics (optional)
4. Create project

### Step 2: Setup Authentication

1. Di Firebase Console, pilih **Authentication**
2. Click **Get Started**
3. Pilih **Google** sebagai sign-in method
4. Isi email support dan name project
5. Enable dan Save

### Step 3: Setup iOS (untuk Flutter iOS)

1. Di Firebase Console, pilih **Project Settings**
2. Download `GoogleService-Info.plist` (iOS)
3. Di Xcode: Tambah file ke project

### Step 4: Setup Android (untuk Flutter Android)

1. Download `google-services.json`
2. Letakkan di `android/app/`

---

## 📱 Setup Flutter/Dart Frontend

### Step 1: Setup Flutter Project

```bash
flutter create my_app
cd my_app
```

### Step 2: Tambah Dependencies

Edit `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  firebase_core: ^2.20.0
  firebase_auth: ^4.10.0
  google_sign_in: ^6.1.0
  provider: ^6.0.0
  dio: ^5.3.0

dev_dependencies:
  flutter_test:
    sdk: flutter
```

Install:

```bash
flutter pub get
```

### Step 3: Copy Dart Files

Copy 3 file Dart ke folder `lib/services/` dan `lib/screens/`:

1. **`lib/services/firebase_auth_service.dart`** - Service layer
2. **`lib/providers/firebase_auth_provider.dart`** - State management
3. **`lib/screens/login_screen.dart`** - UI screens

### Step 4: Setup main.dart

```dart
import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:provider/provider.dart';
import 'firebase_options.dart';
import 'services/firebase_auth_service.dart';
import 'providers/firebase_auth_provider.dart';
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize Firebase
  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform,
  );
  
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        Provider(
          create: (_) => FirebaseAuthService(
            baseUrl: 'http://YOUR_LARAVEL_API_URL/api',
          ),
        ),
        ChangeNotifierProvider(
          create: (context) => FirebaseAuthProvider(
            authService: context.read<FirebaseAuthService>(),
          ),
        ),
      ],
      child: MaterialApp(
        title: 'Firebase Google Auth',
        theme: ThemeData(
          primarySwatch: Colors.blue,
        ),
        debugShowCheckedModeBanner: false,
        routes: {
          '/login': (context) => const LoginScreen(),
          '/home': (context) => const HomeScreen(),
        },
        home: Consumer<FirebaseAuthProvider>(
          builder: (context, authProvider, _) {
            return authProvider.isLoggedIn 
              ? const HomeScreen() 
              : const LoginScreen();
          },
        ),
      ),
    );
  }
}
```

### Step 5: Run App

```bash
flutter run
```

---

## 📡 API Documentation

### Public Endpoints

#### 1. Login dengan Firebase ID Token

```
POST /api/auth/firebase-login
Content-Type: application/json

{
  "id_token": "eyJhbGciOiJSUzI1NiKey..."
}

Response (200):
{
  "success": true,
  "message": "User authenticated successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "firebase_uid": "abc123...",
    "profile_picture": "https://..."
  },
  "token": "1|abcXYZ..."
}
```

#### 2. Get Daftar Pengguna Google

```
GET /api/auth/google-users

Response (200):
{
  "success": true,
  "message": "Google users retrieved successfully",
  "total": 5,
  "users": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "profile_picture": "https://...",
      "created_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### Protected Endpoints (Require Authorization Header)

#### 3. Get Profile User yang Login

```
GET /api/auth/profile
Authorization: Bearer YOUR_TOKEN

Response (200):
{
  "success": true,
  "message": "Profile retrieved successfully",
  "user": { ... }
}
```

#### 4. Logout

```
POST /api/auth/logout
Authorization: Bearer YOUR_TOKEN

Response (200):
{
  "success": true,
  "message": "Logout successfully"
}
```

---

## 🎯 Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Flutter App (Dart)                        │
├─────────────────────────────────────────────────────────────┤
│  1. User click "Login dengan Google"                         │
│  2. Google Sign-in dialog                                   │
│  3. Get ID Token dari Firebase                             │
│  4. Send ID Token ke Laravel API                           │
└────────────────┬──────────────────────────────────────────┘
                 │ HTTPS
                 ▼
┌─────────────────────────────────────────────────────────────┐
│          Laravel Backend (API)                               │
├─────────────────────────────────────────────────────────────┤
│  1. Receive ID Token                                        │
│  2. Verify token dengan Firebase Admin SDK                │
│  3. Get user info dari Firebase                           │
│  4. Create/Update user di database                        │
│  5. Generate Sanctum token                                │
│  6. Response dengan user data + token                     │
└────────────────┬──────────────────────────────────────────┘
                 │ 
                 ▼
    ┌────────────────────────────┐
    │  Firebase Authentication   │
    │  (Verify Token)            │
    └────────────────────────────┘
```

---

## 🐛 Troubleshooting

### Error: "Firebase credentials not configured"

**Solusi:**
- Check `.env` file: `FIREBASE_CREDENTIALS` sudah benar?
- Check file path ke `serviceAccountKey.json` exists?
- Restart Laravel: `php artisan serve`

### Error: "Invalid ID Token"

**Solusi:**
- ID token sudah expired (valid 1 jam)
- Generate token baru dengan login ulang
- Check Firebase project ID cocok

### Error: "CORS issue dari Dart"

**Solusi:**
- Pastikan Laravel running tanpa CORS issue
- Update `config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'],
```

### Error: "User tidak muncul di list"

**Solusi:**
- Check database: apakah user ada di table `users`?
- Login ulang untuk create user baru
- Run migration jika belum ada `firebase_uid` column

### Flutter: "Google Sign-in tidak work di iOS"

**Solusi:**
- Buka Xcode: `open ios/Runner.xcworkspace`
- Tambah `GoogleService-Info.plist` ke Xcode
- Run: `flutter clean && flutter run`

### Flutter: "Dio timeout"

**Solusi:**
- Check Laravel server running?
- Update `baseUrl` di `FirebaseAuthService`
- Tingkatkan timeout di Dio options

---

## 🚀 Production Checklist

- [ ] Set `APP_DEBUG=false` di `.env` production
- [ ] Use HTTPS untuk API communication
- [ ] Hash `serviceAccountKey.json` dan jangan commit ke git
- [ ] Enable CORS dengan specific origins saja
- [ ] Setup database backups
- [ ] Monitor API rate limiting
- [ ] Setup error logging/monitoring
- [ ] Test dengan multiple accounts
- [ ] Review security headers
- [ ] Implement refresh token mechanism

---

## 📚 Referensi

- [Firebase Admin SDK PHP](https://firebase-php.readthedocs.io/)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Firebase Auth Dart](https://pub.dev/packages/firebase_auth)
- [Google Sign-In Dart](https://pub.dev/packages/google_sign_in)

---

**Dibuat:** May 5, 2026  
**Versi:** 1.0
