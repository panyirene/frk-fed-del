<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rencana;
use App\Models\Assessment;
use Illuminate\Support\Facades\DB;

class RencanaController extends Controller
{
    /**
     * Menampilkan daftar rencana kerja.
     *
     * Dosen hanya dapat melihat rencana miliknya sendiri.
     * Asesor/Admin dapat melihat seluruh rencana.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $kategori = $request->query('tab', 'Pendidikan');

        $query = Rencana::where('jenis_rencana', $kategori);

        // Dosen hanya boleh melihat data miliknya sendiri
        if ($user->role === 'dosen') {
            $query->where('id_dosen', $user->id);
        }

        $rencanaList = $query->get();

        // Statistik juga mengikuti hak akses
        $statistikQuery = Rencana::where(
            'jenis_rencana',
            $kategori
        );

        if ($user->role === 'dosen') {
            $statistikQuery->where('id_dosen', $user->id);
        }

        $totalTargetSks = $statistikQuery->sum('sks_terhitung');
        $totalRealisasiSks = $statistikQuery->sum('sks_realisasi');

        return view(
            'rencana.index',
            compact(
                'rencanaList',
                'kategori',
                'totalTargetSks',
                'totalRealisasiSks'
            )
        );
    }

    /**
     * Form tambah rencana.
     */
    public function create()
    {
        // Hanya dosen yang boleh membuat rencana
        if (auth()->user()->role !== 'dosen') {
            abort(403, 'Hanya dosen yang dapat membuat rencana kerja.');
        }

        return view('rencana.create');
    }

    /**
     * Menyimpan rencana baru.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'dosen') {
            abort(403, 'Hanya dosen yang dapat membuat rencana kerja.');
        }

        $request->validate([
            'jenis_rencana' => 'required|string',
            'sub_rencana' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'sks_terhitung' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {

            // Buat FRK
            $rencana = Rencana::create([
                'id_dosen' => auth()->id(),
                'jenis_rencana' => $request->jenis_rencana,
                'sub_rencana' => $request->sub_rencana,
                'nama_kegiatan' => $request->nama_kegiatan,
                'sks_terhitung' => $request->sks_terhitung,

                'status_frk' => 'pending',
                'status_fed' => 'pending',

                // Asesor FRK sementara
                'asesor1_frk' => 3,
                'asesor2_frk' => null,
            ]);

            // Buat assessment untuk Asesor 1
            Assessment::create([
                'rencana_id' => $rencana->id_rencana,
                'assessor_id' => 3,
                'jenis' => 'FRK',
                'status' => 'pending',
                'komentar' => null,
                'reviewed_at' => null,
            ]);
        });

        return redirect(
            '/rencana?tab=' . urlencode($request->jenis_rencana)
        )->with(
            'success',
            'Rencana kerja berhasil ditambahkan dan dikirim ke asesor.'
        );
    }

    /**
     * Update status FRK oleh asesor.
     *
     * Catatan:
     * Proses assessment utama sebaiknya melalui AssessmentController.
     * Method ini tetap dipertahankan agar route lama tidak rusak.
     */
    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'asesor') {
            abort(403, 'Hanya asesor yang dapat memperbarui status review.');
        }

        $request->validate([
            'status_frk' => 'required|in:pending,approved,rejected',
            'komentar_asesor' => 'nullable|string|max:5000',
        ]);

        $rencana = Rencana::findOrFail($id);

        if (
            $request->status_frk === 'rejected' &&
            empty($request->komentar_asesor)
        ) {
            return back()->with(
                'error',
                'Komentar wajib diisi jika rencana kerja ditolak/revisi.'
            );
        }

        $rencana->update([
            'status_frk' => $request->status_frk,
            'komentar_asesor' => $request->komentar_asesor,
        ]);

        return back()->with(
            'success',
            'Status review asesor berhasil diperbarui!'
        );
    }

    /**
     * Menampilkan form edit FRK.
     *
     * Dosen hanya dapat mengedit rencana miliknya sendiri.
     */
    public function edit($id)
    {
        $rencana = Rencana::where('id_rencana', $id)
            ->where('id_dosen', auth()->id())
            ->firstOrFail();

        // Jangan izinkan edit jika sudah approved
        if ($rencana->status_frk === 'approved') {
            return back()->with(
                'error',
                'Rencana kerja yang sudah disetujui tidak dapat diedit.'
            );
        }

        return view('rencana.edit', compact('rencana'));
    }

    /**
     * Memproses update data FRK.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_rencana' => 'required|string',
            'sub_rencana' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'sks_terhitung' => 'required|integer|min:1',
        ]);

        $rencana = Rencana::where('id_rencana', $id)
            ->where('id_dosen', auth()->id())
            ->firstOrFail();

        // Tidak boleh mengubah FRK yang sudah approved
        if ($rencana->status_frk === 'approved') {
            return back()->with(
                'error',
                'Rencana kerja yang sudah disetujui tidak dapat diubah.'
            );
        }

        $rencana->update([
            'jenis_rencana' => $request->jenis_rencana,
            'sub_rencana' => $request->sub_rencana,
            'nama_kegiatan' => $request->nama_kegiatan,
            'sks_terhitung' => $request->sks_terhitung,
        ]);

        return redirect('/dashboard')->with(
            'success',
            'Rencana kerja berhasil diperbarui!'
        );
    }

    /**
     * Menampilkan form FED.
     */
    public function editFed($id)
    {
        $rencana = Rencana::where('id_rencana', $id)
            ->where('id_dosen', auth()->id())
            ->firstOrFail();

        return view('rencana.fed', compact('rencana'));
    }

    /**
     * Memperbarui FED.
     */
    public function updateFed(Request $request, $id)
    {
        $request->validate([
            'sks_realisasi' => 'required|integer|min:0',
            'lampiran_fed' => 'nullable|url|max:2000',
        ]);

        $rencana = Rencana::where('id_rencana', $id)
            ->where('id_dosen', auth()->id())
            ->firstOrFail();

        $rencana->update([
            'sks_realisasi' => $request->sks_realisasi,
            'lampiran_fed' => $request->lampiran_fed,
            'status_fed' => 'pending',
        ]);

        return redirect('/dashboard')->with(
            'success',
            'Evaluasi Diri (FED) berhasil disimpan!'
        );
    }

    /**
     * Menghapus rencana.
     */
    public function destroy($id)
    {
        $rencana = Rencana::where('id_rencana', $id)
            ->where('id_dosen', auth()->id())
            ->firstOrFail();

        // Jangan izinkan menghapus rencana yang sudah diproses
        if (
            in_array(
                $rencana->status_frk,
                ['approved']
            )
        ) {
            return back()->with(
                'error',
                'Rencana kerja yang sudah disetujui tidak dapat dihapus.'
            );
        }

        $kategori = $rencana->jenis_rencana;

        $rencana->delete();

        return redirect(
            '/rencana?tab=' . urlencode($kategori)
        )->with(
            'success',
            'Rencana kerja berhasil dihapus!'
        );
    }

    /**
     * Menarik kembali rencana yang sudah diajukan.
     *
     * Hanya dosen pemilik rencana yang dapat melakukan ini.
     */
    public function unsubmit($id)
    {
        $rencana = Rencana::where('id_rencana', $id)
            ->where('id_dosen', auth()->id())
            ->firstOrFail();

        // Hanya rencana yang ditolak/revisi yang boleh ditarik kembali
        if ($rencana->status_frk !== 'rejected') {
            return back()->with(
                'error',
                'Rencana hanya dapat ditarik kembali setelah mendapat permintaan revisi.'
            );
        }

        $rencana->update([
            'status_frk' => 'pending',
            'status_fed' => 'pending',
        ]);

        return back()->with(
            'success',
            'Status kegiatan berhasil ditarik kembali ke posisi pending.'
        );
    }

    /**
     * Menampilkan riwayat rencana milik dosen yang login.
     */
    public function riwayat()
    {
        $riwayatList = Rencana::where(
            'id_dosen',
            auth()->id()
        )->latest()->get();

        return view(
            'rencana.riwayat',
            compact('riwayatList')
        );
    }
}