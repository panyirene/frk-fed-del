<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Periode FRK & FED
        if (!Schema::hasTable('periode_frk_fed')) {
            Schema::create('periode_frk_fed', function (Blueprint $table) {
                $table->id('id_periode');
                $table->string('tipe_periode'); // Ganjil / Genap
                $table->date('tanggal_awal_pengisian');
                $table->date('tanggal_akhir_pengisian');
                $table->string('tahun_ajaran');
                $table->timestamps();
            });
        }

        // 2. Tambahan kolom detail pada tabel rencana untuk kategori Tridharma
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
            // Dukungan Multi-Asesor sesuai spesifikasi dokumen
            if (!Schema::hasColumn('rencana', 'asesor2_frk')) {
                $table->string('asesor2_frk')->nullable();
            }
            if (!Schema::hasColumn('rencana', 'asesor2_fed')) {
                $table->string('asesor2_fed')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_frk_fed');
        Schema::table('rencana', function (Blueprint $table) {
            $table->dropColumn(['jumlah_kelas', 'sks_matakuliah', 'posisi', 'tahun_ajaran', 'asesor2_frk', 'asesor2_fed']);
        });
    }
};