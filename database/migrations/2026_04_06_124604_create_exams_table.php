<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('exams', function (Blueprint $table) {
        $table->id();
        $table->foreignId('folder_id')->constrained('folders')->onDelete('cascade');
        $table->string('title');
        $table->boolean('is_exam_mode')->default(false);
        $table->boolean('require_gesture_tracking')->default(false); // Rekap gestur wajib
        $table->integer('duration_minutes')->nullable(); // Waktu ujian
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
