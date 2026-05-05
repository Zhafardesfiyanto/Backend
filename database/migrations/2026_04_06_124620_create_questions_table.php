<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
        $table->enum('type', ['multiple_choice', 'complex_multiple_choice', 'essay']);
        $table->longText('question_text');
        $table->json('options')->nullable(); // Untuk menyimpan pilihan A,B,C,D
        $table->json('correct_answers')->nullable(); // Bisa banyak jawaban benar (pilgan kompleks)
        $table->integer('score_weight')->default(10); // Bobot nilai per soal
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
