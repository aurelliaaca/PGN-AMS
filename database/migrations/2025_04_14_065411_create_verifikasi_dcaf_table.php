<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_dcaf', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('verifikasi_nda_id')->constrained('verifikasi_nda')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->string('status')->default('pending'); // pending, diterima, ditolak
            $table->text('catatan')->nullable();
            $table->timestamp('masa_berlaku')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_dcaf');
    }
}; 