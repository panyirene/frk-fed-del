<?php

namespace Database\Seeders;

use App\Models\FollowUp;
use App\Models\Rencana;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FollowUpDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan user Johannes yang pada database kamu sudah terdeteksi
        // sebagai user ID 2.
        $dosen = User::findOrFail(2);

        // Ambil satu user dengan role asesor.
        $assessor = User::where('role', 'asesor')->first();

        if (!$assessor) {
            $this->command->error('Tidak ditemukan user dengan role "asesor".');
            $this->command->error('Cek nilai role pada tabel users terlebih dahulu.');
            return;
        }

        // Ambil satu rencana milik dosen tersebut.
        $rencana = Rencana::where('id_dosen', $dosen->id)
            ->orderBy('id_rencana')
            ->first();

        if (!$rencana) {
            $this->command->error('Tidak ditemukan rencana milik user ID ' . $dosen->id . '.');
            return;
        }

        // Cegah data demo dibuat dua kali.
        $existing = FollowUp::where('rencana_id', $rencana->id_rencana)
            ->where('dosen_id', $dosen->id)
            ->where('judul', 'Perbaikan Rencana Kerja Demo')
            ->first();

        if ($existing) {
            $this->command->info('Data tindak lanjut demo sudah ada.');
            $this->command->info('ID: ' . $existing->id);
            $this->command->info('Kode: ' . $existing->kode);
            return;
        }

        $followUp = FollowUp::create([
            'kode' => 'TL-DEMO-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
            'rencana_id' => $rencana->id_rencana,
            'assessment_id' => null,
            'dosen_id' => $dosen->id,
            'assessor_id' => $assessor->id,
            'judul' => 'Perbaikan Rencana Kerja Demo',
            'temuan' => 'Bukti pendukung kegiatan belum lengkap dan perlu dilengkapi.',
            'rekomendasi' => 'Lengkapi bukti pendukung kegiatan kemudian kirim kembali untuk diverifikasi.',
            'prioritas' => 'high',
            'status' => 'open',
            'tanggapan_dosen' => null,
            'bukti_perbaikan' => null,
            'deadline' => now()->addDays(7)->toDateString(),
            'submitted_at' => null,
            'verified_at' => null,
        ]);

        $this->command->info('========================================');
        $this->command->info('Tindak lanjut demo berhasil dibuat.');
        $this->command->info('ID       : ' . $followUp->id);
        $this->command->info('Kode     : ' . $followUp->kode);
        $this->command->info('Dosen ID : ' . $followUp->dosen_id);
        $this->command->info('Asesor ID: ' . $followUp->assessor_id);
        $this->command->info('Rencana  : ' . $followUp->rencana_id);
        $this->command->info('Status   : ' . $followUp->status);
        $this->command->info('========================================');
    }
}