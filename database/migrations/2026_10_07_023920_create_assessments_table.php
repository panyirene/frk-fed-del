<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rencana_id');
            $table->unsignedBigInteger('assessor_id');

            // frk / fed
            $table->string('jenis', 20);

            // approved / rejected
            $table->string('status', 20);

            $table->text('komentar')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->foreign('rencana_id')
                ->references('id_rencana')
                ->on('rencana')
                ->cascadeOnDelete();

            $table->foreign('assessor_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->index(['rencana_id', 'jenis']);
            $table->index(['assessor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};