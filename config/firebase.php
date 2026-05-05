<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Path ke file service account JSON Firebase, atau JSON string langsung.
    | Simpan path file di environment variable FIREBASE_CREDENTIALS.
    |
    | Contoh .env:
    | FIREBASE_CREDENTIALS=/path/to/firebase-service-account.json
    |
    */
    'credentials' => env('FIREBASE_CREDENTIALS'),
];
