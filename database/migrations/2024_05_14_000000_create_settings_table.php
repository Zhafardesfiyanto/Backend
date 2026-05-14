<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $row) {
            $row->id();
            $row->string('key')->unique();
            $row->text('value')->nullable();
            $row->string('type')->default('string');
            $row->timestamps();
        });

        // Seed default values
        DB::table('settings')->insert([
            ['key' => 'app_name', 'value' => 'Q-Les', 'type' => 'string'],
            ['key' => 'support_email', 'value' => 'support@q-les.com', 'type' => 'string'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'type' => 'string'],
            ['key' => 'registration_open', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
