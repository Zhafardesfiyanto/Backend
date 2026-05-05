<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    // Supaya Laravel tidak bingung dengan kata "class"
    protected $table = 'classes'; 
    
    // Mengizinkan semua kolom diisi (Mass Assignment)
    protected $guarded = [];

    // Relasi: Kelas ini milik siapa? (Guru)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relasi: Kelas ini punya siapa saja anggotanya? (Murid)
    public function members()
    {
        // Many-to-Many relasi via tabel class_members
        return $this->belongsToMany(User::class, 'class_members', 'class_id', 'student_id');
    }

    // Relasi: Kelas ini punya folder tugas apa saja?
    public function folders()
    {
        return $this->hasMany(Folder::class, 'class_id');
    }
}