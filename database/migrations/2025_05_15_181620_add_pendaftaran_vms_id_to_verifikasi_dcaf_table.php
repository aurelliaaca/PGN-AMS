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
        Schema::table('verifikasi_dcaf', function (Blueprint $table) {
            // Tambahkan kolom pendaftaran_vms_id setelah kolom verifikasi_nda_id
            $table->foreignId('pendaftaran_vms_id')->nullable()->after('verifikasi_nda_id')->constrained('pendaftaran_vms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_dcaf', function (Blueprint $table) {
            $table->dropForeign(['pendaftaran_vms_id']);
            $table->dropColumn('pendaftaran_vms_id');
        });
    }
};