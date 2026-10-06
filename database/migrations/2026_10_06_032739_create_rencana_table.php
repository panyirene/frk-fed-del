<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana', function (Blueprint $table) {
            $table->id('id_rencana');
            $table->unsignedBigInteger('id_dosen')->default(1);
            $table->string('jenis_rencana'); // Pendidikan, Penelitian, Pengabdian, Penunjang
            $table->string('sub_rencana');
            $table->text('nama_kegiatan');
            $table->integer('sks_terhitung');
            $table->integer('sks_realisasi')->nullable();
            
            // Asesor & Status
            $table->string('asesor1_frk')->nullable();
            $table->string('asesor2_frk')->nullable();
            $table->enum('status_frk', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('komentar_asesor')->nullable();
            
            // FED
            $table->string('lampiran_fed')->nullable();
            $table->enum('status_fed', ['pending', 'approved', 'rejected'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana');
    }
};