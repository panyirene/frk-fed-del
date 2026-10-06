<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rencana', function (Blueprint $table) {
            if (!Schema::hasColumn('rencana', 'jumlah_kelas')) {
                $table->integer('jumlah_kelas')->nullable();
            }
            if (!Schema::hasColumn('rencana', 'sks_matakuliah')) {
                $table->integer('sks_matakuliah')->nullable();
            }
            if (!Schema::hasColumn('rencana', 'posisi')) {
                $table->string('posisi')->nullable();
            }
            if (!Schema::hasColumn('rencana', 'tahun_ajaran')) {
                $table->string('tahun_ajaran')->default('2025/2026 Genap');
            }
        });

        // Tabel Periode FRK/FED oleh Admin
        if (!Schema::hasTable('periode_frk_fed')) {
            Schema::create('periode_frk_fed', function (Blueprint $table) {
                $table->id('id_periode');
                $table->string('tahun_ajaran');
                $table->date('tanggal_awal_pengisian');
                $table->date('tanggal_akhir_pengisian');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('rencana', function (Blueprint $table) {
            $table->dropColumn(['jumlah_kelas', 'sks_matakuliah', 'posisi', 'tahun_ajaran']);
        });
        Schema::dropIfExists('periode_frk_fed');
    }
};