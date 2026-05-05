<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use Illuminate\Support\Str; // Untuk membuat kode acak

class ClassController extends Controller
{
    // Fungsi untuk Guru membuat kelas baru
    public function createClass(Request $request)
    {
        $request->validate([
            'teacher_id'  => 'required|exists:users,id', // Pastikan ID guru ada di database
            'class_name'  => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        // Membuat kode kelas acak 8 karakter (Huruf kapital dan angka)
        $classCode = strtoupper(Str::random(8));

        // Simpan ke database
        $newClass = Classes::create([
            'teacher_id'  => $request->teacher_id,
            'class_name'  => $request->class_name,
            'class_code'  => $classCode,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dibuat',
            'data'    => $newClass
        ], 201);
    }

    // Fungsi untuk Siswa bergabung ke kelas
    public function joinClass(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'class_code' => 'required|string'
        ]);

        // Cari kelas berdasarkan kode yang diketik siswa
        $class = Classes::where('class_code', $request->class_code)->first();

        // Jika kodenya salah / kelas tidak ditemukan
        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kelas tidak valid atau kelas tidak ditemukan'
            ], 404);
        }

        // Cek apakah siswa sudah pernah join kelas ini sebelumnya
        if ($class->members()->where('student_id', $request->student_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah bergabung di kelas ini'
            ], 400);
        }

        // Masukkan siswa ke dalam kelas (menyimpan ke tabel class_members)
        $class->members()->attach($request->student_id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung ke kelas ' . $class->class_name,
            'data'    => $class
        ], 200);
    }
}