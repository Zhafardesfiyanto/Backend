<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheatLog extends Model
{
    public function reportViolation(Request $request)
{
    $request->validate([
        'exam_id'        => 'required|exists:exams,id',
        'student_id'     => 'required|exists:users,id',
        'violation_type' => 'required|string',
    ]);

    $log = CheatLog::create($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Pelanggaran tercatat!',
        'data'    => $log
    ], 201);
}
}
