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
        Schema::table('verifikasi_dokumen', function (Blueprint $table) {
            $table->string('signature')->nullable()->after('status');
            $table->string('signed_by')->nullable()->after('signature');
            $table->timestamp('signed_at')->nullable()->after('signed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_dokumen', function (Blueprint $table) {
            $table->dropColumn(['signature', 'signed_by', 'signed_at']);
        });
    }
};
