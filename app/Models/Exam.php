<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi: Ujian ini ada di folder mana?
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    // Relasi: Ujian ini punya banyak pertanyaan
    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id');
    }

    // Relasi: Ujian ini punya banyak jawaban/pengumpulan dari siswa
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'exam_id');
    }
}