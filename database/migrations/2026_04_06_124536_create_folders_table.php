<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('folders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
        $table->string('name'); // Contoh: "Tugas Harian", "UTS"
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
