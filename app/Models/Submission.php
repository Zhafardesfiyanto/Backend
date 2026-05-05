<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Ini PENTING: Biar JSON jawaban otomatis jadi Array di PHP
    protected $casts = [
        'answers' => 'array',
    ];

    // Relasi ke User (Siswa)
    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}