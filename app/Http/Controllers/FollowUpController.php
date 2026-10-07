<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    /**
     * Menampilkan Tindak Lanjut sesuai role.
     */
    public function index()
    {
        $user = auth()->user();

        $query = FollowUp::with([
            'rencana',
            'assessment',
            'dosen',
            'assessor',
        ]);

        /*
         * DOSEN
         * Hanya melihat Tindak Lanjut miliknya sendiri.
         */
        if ($user->role === 'dosen') {

            $query->where('dosen_id', $user->id);

        /*
         * ASESOR
         * Hanya melihat Tindak Lanjut yang
         * ditugaskan kepadanya.
         */
        } elseif ($user->role === 'asesor') {

            $query->where('assessor_id', $user->id);

        /*
         * ADMIN
         * Dapat melihat seluruh Tindak Lanjut.
         */
        } elseif ($user->role === 'admin') {

            // Tidak perlu filter.

        } else {

            abort(
                403,
                'Anda tidak memiliki akses ke Tindak Lanjut.'
            );
        }

        $followUps = $query
            ->latest()
            ->get();

        return view(
            'tindak-lanjut.index',
            compact('followUps')
        );
    }


    /**
     * DOSEN mengirim hasil perbaikan.
     */
    public function submit(Request $request, $id)
    {
        /*
         * Pastikan hanya Dosen yang dapat
         * menggunakan endpoint ini.
         */
        if (auth()->user()->role !== 'dosen') {
            abort(
                403,
                'Hanya dosen yang dapat mengirim perbaikan.'
            );
        }

        $request->validate([
            'tanggapan_dosen' => [
                'required',
                'string',
                'max:5000',
            ],

            'bukti_perbaikan' => [
                'nullable',
                'url',
                'max:2000',
            ],
        ], [
            'tanggapan_dosen.required' =>
                'Tanggapan/perbaikan wajib diisi.',

            'bukti_perbaikan.url' =>
                'Link bukti perbaikan harus berupa URL yang valid.',
        ]);

        /*
         * Cari berdasarkan ID + pemilik.
         *
         * Dengan cara ini Dosen tidak dapat
         * mengirim perbaikan milik Dosen lain.
         */
        $followUp = FollowUp::where('id', $id)
            ->where('dosen_id', auth()->id())
            ->firstOrFail();

        /*
         * Hanya OPEN atau IN PROGRESS
         * yang dapat dikirim.
         */
        if (!in_array($followUp->status, [
            'open',
            'in_progress',
        ])) {

            return back()->with(
                'error',
                'Tindak lanjut ini tidak dapat dikirim pada status sekarang.'
            );
        }

        $followUp->update([
            'tanggapan_dosen' =>
                $request->tanggapan_dosen,

            'bukti_perbaikan' =>
                $request->bukti_perbaikan,

            'status' =>
                'submitted',

            'submitted_at' =>
                now(),

            'verified_at' =>
                null,
        ]);

        return back()->with(
            'success',
            'Perbaikan berhasil dikirim dan menunggu verifikasi asesor.'
        );
    }


    /**
     * ASESOR memverifikasi Tindak Lanjut.
     */
    public function verify(Request $request, $id)
    {
        /*
         * Hanya asesor yang boleh melakukan
         * verifikasi.
         */
        if (auth()->user()->role !== 'asesor') {
            abort(
                403,
                'Hanya asesor yang dapat memverifikasi tindak lanjut.'
            );
        }

        $request->validate([
            'status' => [
                'required',
                'in:verified,revision',
            ],
        ]);

        /*
         * Cari berdasarkan ID + assessor yang ditugaskan.
         *
         * Asesor lain tidak dapat memverifikasi
         * tindak lanjut ini.
         */
        $followUp = FollowUp::where('id', $id)
            ->where('assessor_id', auth()->id())
            ->firstOrFail();

        /*
         * Hanya SUBMITTED yang dapat diverifikasi.
         */
        if ($followUp->status !== 'submitted') {

            return back()->with(
                'error',
                'Tindak lanjut belum siap untuk diverifikasi.'
            );
        }


        /*
         * ============================
         * VERIFIED
         * ============================
         */

        if ($request->status === 'verified') {

            $followUp->update([
                'status' => 'verified',
                'verified_at' => now(),
            ]);

            return back()->with(
                'success',
                'Tindak lanjut berhasil diverifikasi.'
            );
        }


        /*
         * ============================
         * REVISION
         * ============================
         *
         * Dikembalikan ke Dosen.
         */
        $followUp->update([
            'status' => 'in_progress',
            'submitted_at' => null,
            'verified_at' => null,
        ]);

        return back()->with(
            'success',
            'Tindak lanjut dikembalikan kepada dosen untuk diperbaiki kembali.'
        );
    }


    /**
     * ADMIN / ASESOR menutup Tindak Lanjut.
     */
    public function close($id)
    {
        $user = auth()->user();

        /*
         * Admin dapat menutup semua Tindak Lanjut.
         *
         * Asesor hanya dapat menutup Tindak Lanjut
         * yang ditugaskan kepadanya.
         */
        if ($user->role === 'admin') {

            $followUp = FollowUp::findOrFail($id);

        } elseif ($user->role === 'asesor') {

            $followUp = FollowUp::where('id', $id)
                ->where('assessor_id', $user->id)
                ->firstOrFail();

        } else {

            abort(
                403,
                'Anda tidak memiliki akses untuk menutup tindak lanjut.'
            );
        }

        /*
         * Hanya VERIFIED yang dapat ditutup.
         */
        if ($followUp->status !== 'verified') {

            return back()->with(
                'error',
                'Tindak lanjut hanya dapat ditutup setelah diverifikasi.'
            );
        }

        $followUp->update([
            'status' => 'closed',
        ]);

        return back()->with(
            'success',
            'Tindak lanjut berhasil ditutup.'
        );
    }
}