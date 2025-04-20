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
        Schema::create('verifikasi_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nda_file_path')->nullable();
            $table->string('dcaf_file_path')->nullable();
            $table->string('nda_status')->default('pending'); // pending, diterima, ditolak
            $table->string('dcaf_status')->default('pending'); // pending, diterima, ditolak
            $table->text('catatan')->nullable();
            $table->timestamp('nda_masa_berlaku')->nullable();
            $table->timestamp('dcaf_masa_berlaku')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_dokumen');
    }
};
