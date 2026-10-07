<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    /**
     * Daftar assessment yang perlu direview.
     *
     * Asesor:
     * - hanya melihat assessment yang ditugaskan kepadanya
     * - hanya yang masih pending
     *
     * Admin:
     * - dapat melihat semua assessment yang masih pending
     */
    public function index()
    {
        $user = auth()->user();

        $query = Assessment::with([
            'rencana',
            'assessor',
        ]);

        if ($user->role === 'asesor') {

            $query->where('assessor_id', $user->id)
                ->where('status', 'pending');

        } elseif ($user->role === 'admin') {

            $query->where('status', 'pending');

        } else {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman assessment.'
            );
        }

        $assessments = $query
            ->latest()
            ->get();

        return view(
            'assessment.index',
            compact('assessments')
        );
    }

    /**
     * Form review assessment.
     */
    public function edit($id)
    {
        $user = auth()->user();

        /*
         * Hanya asesor dan admin
         * yang boleh membuka halaman review.
         */
        if (!in_array($user->role, ['asesor', 'admin'])) {
            abort(
                403,
                'Anda tidak memiliki akses untuk review assessment.'
            );
        }

        $assessment = Assessment::with([
            'rencana',
            'assessor',
        ])->findOrFail($id);

        /*
         * Assessment yang sudah selesai
         * tidak boleh direview ulang.
         */
        if ($assessment->status !== 'pending') {
            return redirect()
                ->route('assessment.index')
                ->with(
                    'error',
                    'Assessment ini sudah selesai diproses dan tidak dapat direview kembali.'
                );
        }

        /*
         * Asesor hanya boleh membuka
         * assessment yang ditugaskan kepadanya.
         */
        if (
            $user->role === 'asesor' &&
            (int) $assessment->assessor_id !== (int) $user->id
        ) {
            abort(
                403,
                'Assessment ini bukan tanggung jawab Anda.'
            );
        }

        return view(
            'assessment.edit',
            compact('assessment')
        );
    }

    /**
     * Menyimpan hasil review assessment.
     *
     * APPROVED = disetujui
     * REJECTED = direvisi
     *
     * Jika REJECTED:
     * - status FRK menjadi rejected
     * - komentar asesor disimpan
     * - Tindak Lanjut dibuat otomatis
     */
    public function review(Request $request, $id)
    {
        $user = auth()->user();

        /*
         * Hanya asesor dan admin
         * yang boleh melakukan review.
         */
        if (!in_array($user->role, ['asesor', 'admin'])) {
            abort(
                403,
                'Anda tidak memiliki akses untuk melakukan review.'
            );
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',

            'komentar' => [
                'required_if:status,rejected',
                'nullable',
                'string',
                'max:5000',
            ],
        ], [
            'komentar.required_if' =>
                'Komentar wajib diisi jika meminta revisi.',
        ]);

        $assessment = Assessment::with('rencana')
            ->findOrFail($id);

        /*
         * Assessment hanya boleh diproses sekali.
         */
        if ($assessment->status !== 'pending') {
            return redirect()
                ->route('assessment.index')
                ->with(
                    'error',
                    'Assessment ini sudah diproses sebelumnya.'
                );
        }

        /*
         * Asesor hanya boleh memproses
         * assessment miliknya sendiri.
         */
        if (
            $user->role === 'asesor' &&
            (int) $assessment->assessor_id !== (int) $user->id
        ) {
            abort(
                403,
                'Assessment ini bukan tanggung jawab Anda.'
            );
        }

        /*
         * Pastikan assessment memiliki rencana.
         */
        if (!$assessment->rencana) {
            return back()->with(
                'error',
                'Data rencana kerja untuk assessment ini tidak ditemukan.'
            );
        }

        DB::transaction(function () use (
            $request,
            $assessment
        ) {

            /*
             * ============================
             * UPDATE ASSESSMENT
             * ============================
             */
            $assessment->update([
                'status' => $request->status,
                'komentar' => $request->komentar,
                'reviewed_at' => now(),
            ]);

            $rencana = $assessment->rencana;

            /*
             * ============================
             * APPROVED
             * ============================
             */
            if ($request->status === 'approved') {

                $rencana->update([
                    'status_frk' => 'approved',
                    'komentar_asesor' => $request->komentar,
                ]);

                return;
            }

            /*
             * ============================
             * REJECTED
             * ============================
             */
            $rencana->update([
                'status_frk' => 'rejected',
                'komentar_asesor' => $request->komentar,
            ]);

            /*
             * Cek apakah Tindak Lanjut
             * untuk assessment ini sudah ada.
             */
            $followUp = FollowUp::where(
                'assessment_id',
                $assessment->id
            )->first();

            /*
             * Jika belum ada,
             * buat Tindak Lanjut otomatis.
             */
            if (!$followUp) {

                FollowUp::create([

                    'kode' =>
                        $this->generateFollowUpCode(),

                    'rencana_id' =>
                        $rencana->id_rencana,

                    'assessment_id' =>
                        $assessment->id,

                    'dosen_id' =>
                        $rencana->id_dosen,

                    'assessor_id' =>
                        $assessment->assessor_id,

                    'judul' =>
                        'Perbaikan: ' .
                        ($rencana->nama_kegiatan ?? 'Rencana Kerja'),

                    'temuan' =>
                        $request->komentar,

                    'rekomendasi' =>
                        'Lakukan perbaikan sesuai catatan asesor dan kirim kembali bukti perbaikan.',

                    'prioritas' =>
                        'high',

                    'status' =>
                        'open',

                    'tanggapan_dosen' =>
                        null,

                    'bukti_perbaikan' =>
                        null,

                    'deadline' =>
                        now()->addDays(7)->toDateString(),

                    'submitted_at' =>
                        null,

                    'verified_at' =>
                        null,
                ]);
            }
        });

        return redirect()
            ->route('assessment.index')
            ->with(
                'success',
                $request->status === 'approved'
                    ? 'Rencana kerja berhasil disetujui.'
                    : 'Rencana kerja direvisi dan Tindak Lanjut otomatis dibuat.'
            );
    }

    /**
     * Generate kode unik Tindak Lanjut.
     */
    private function generateFollowUpCode(): string
    {
        do {

            $code =
                'TL-' .
                now()->format('YmdHis') .
                '-' .
                Str::upper(Str::random(4));

        } while (
            FollowUp::where('kode', $code)->exists()
        );

        return $code;
    }
}