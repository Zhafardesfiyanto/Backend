<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Penting! Mengubah data JSON di database menjadi Array di Laravel
    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}