<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Folder; // Pastikan ini ada
use App\Models\Exam;   //  <<-- ini kayak kntl bikin gua stuck 2 jam anjing cuma kelebihan m doang loh padahal
use App\Models\CheatLog;

class ExamController extends Controller
{
    // 1. Fungsi Tambah Soal (Harus di DALAM kurung kurawal class)
    public function addQuestion(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'type' => 'required|in:multiple_choice,essay',
            'question_text' => 'required|string',
            'score_weight' => 'required|integer'
        ]);

        $question = Question::create($request->all());

        return response()->json(['success' => true, 'data' => $question], 201);
    }

    // 2. Fungsi Membuat Folder
    public function createFolder(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string'
        ]);

        $folder = Folder::create($request->all());

        return response()->json(['success' => true, 'data' => $folder], 201);
    }

    // 3. Fungsi Membuat Ujian
    public function createExam(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id',
            'title' => 'required|string',
            'is_exam_mode' => 'required|boolean',
        ]);

        $exam = Exam::create($request->all());

        return response()->json(['success' => true, 'data' => $exam], 201);
    }
    // ... di dalam class ExamController ...

    public function reportViolation(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:users,id',
            'violation_type' => 'required|string',
        ]);

        // Pastikan kamu sudah import: use App\Models\CheatLog; di bagian atas file
        $log = \App\Models\CheatLog::create([
            'exam_id' => $request->exam_id,
            'student_id' => $request->student_id,
            'violation_type' => $request->violation_type,
            'description' => $request->description ?? 'Tidak ada deskripsi'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan pelanggaran berhasil dicatat di Q-Les!',
            'data' => $log
        ], 201);
    }
    // Hapus (Request $request), ganti langsung dengan ($examId)
    public function getViolations($examId)
{
    // Kita ambil pelanggaran, kelompokkan per siswa, dan hitung jumlahnya
    $logs = CheatLog::with('student')
        ->where('exam_id', $examId)
        ->selectRaw('student_id, violation_type, description, created_at, count(*) as total_violations')
        ->groupBy('student_id') // Biar Guru nggak pusing liat nama yang sama berulang-ulang
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $logs
    ]);
}

} // <--- KURUNG KURAWAL INI HARUS PALING BAWAH (Penutup Class) 