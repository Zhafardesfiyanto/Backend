<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('audit_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pelaku
        $table->string('action');      // Contoh: 'DELETE_USER', 'RESET_EXAM'
        $table->string('target_type'); // Nama Tabel (users, exams, dll)
        $table->unsignedBigInteger('target_id')->nullable(); // ID data yang kena dampak
        $table->text('description');   // Detail: "Menghapus akun budi@gmail.com"
        $table->string('ip_address')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
