<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();

            $table->string('kode')->unique();

            $table->unsignedBigInteger('rencana_id');
            $table->unsignedBigInteger('assessment_id')->nullable();

            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('assessor_id')->nullable();

            $table->string('judul');

            $table->text('temuan');

            $table->text('rekomendasi')->nullable();

            // low / medium / high / urgent
            $table->string('prioritas')->default('medium');

            // open / in_progress / submitted / under_review / verified / closed
            $table->string('status')->default('open');

            $table->text('tanggapan_dosen')->nullable();

            $table->string('bukti_perbaikan')->nullable();

            $table->date('deadline')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->foreign('rencana_id')
                ->references('id_rencana')
                ->on('rencana')
                ->cascadeOnDelete();

            $table->foreign('assessment_id')
                ->references('id')
                ->on('assessments')
                ->nullOnDelete();

            $table->foreign('dosen_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('assessor_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(['dosen_id', 'status']);
            $table->index(['assessor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};