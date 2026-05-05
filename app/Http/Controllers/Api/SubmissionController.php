<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;

class SubmissionController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'exam_id'    => 'required|exists:exams,id',
            'student_id' => 'required|exists:users,id',
            'answers'    => 'required|array', // Berisi list jawaban siswa
        ]);

        $submission = Submission::create([
            'exam_id'    => $request->exam_id,
            'student_id' => $request->student_id,
            'answers'    => json_encode($request->answers),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil dikirim!',
            'data'    => $submission
        ], 201);
    }

    // ... di dalam class SubmissionController ...

// 1. Fungsi Guru Melihat Semua Jawaban di Satu Ujian
public function index($examId)
{
    $submissions = Submission::with('student')
        ->where('exam_id', $examId)
        ->get();

    return response()->json(['success' => true, 'data' => $submissions]);
}

// 2. Fungsi Guru Memberi Nilai Akhir
public function grade(Request $request, $id)
{
    $request->validate([
        'total_score' => 'required|integer|min:0|max:100'
    ]);

    $submission = Submission::findOrFail($id);
    $submission->update([
        'total_score' => $request->total_score
    ]);

    return response()->json([
        'success' => true, 
        'message' => 'Nilai berhasil disimpan!',
        'data'    => $submission
    ]);
}
}