<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rencana;

class RencanaController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('tab', 'Pendidikan');
        
        $rencanaList = Rencana::where('jenis_rencana', $kategori)->get();

        $totalTargetSks = Rencana::sum('sks_terhitung');
        $totalRealisasiSks = Rencana::sum('sks_realisasi');

        return view('rencana.index', compact('rencanaList', 'kategori', 'totalTargetSks', 'totalRealisasiSks'));
    }

    public function create()
    {
        return view('rencana.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_rencana' => 'required|string',
            'sub_rencana' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'sks_terhitung' => 'required|integer|min:1',
        ]);

        Rencana::create([
            'id_dosen' => auth()->id() ?? 1,
            'jenis_rencana' => $request->jenis_rencana,
            'sub_rencana' => $request->sub_rencana,
            'nama_kegiatan' => $request->nama_kegiatan,
            'sks_terhitung' => $request->sks_terhitung,
            'status_frk' => 'pending',
            'status_fed' => 'pending',
        ]);

        return redirect('/rencana?tab=' . urlencode($request->jenis_rencana))
                    ->with('success', 'Rencana kerja berhasil ditambahkan!');
    }

    public function updateStatus(Request $request, $id)
    {
        $rencana = Rencana::findOrFail($id);

        if ($request->status_frk == 'rejected' && empty($request->komentar_asesor)) {
            return back()->with('error', 'Komentar wajib diisi jika rencana kerja ditolak/revisi.');
        }

        $rencana->update([
            'status_frk' => $request->status_frk,
            'komentar_asesor' => $request->komentar_asesor,
        ]);

        return back()->with('success', 'Status review asesor berhasil diperbarui!');
    }

    // Menampilkan form edit FRK (seluruh atribut kegiatan)
    public function edit($id)
    {
        $rencana = Rencana::findOrFail($id);
        return view('rencana.edit', compact('rencana'));
    }

    // Memproses update data FRK
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_rencana' => 'required|string',
            'sub_rencana' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'sks_terhitung' => 'required|integer|min:1',
        ]);

        $rencana = Rencana::findOrFail($id);
        $rencana->update([
            'jenis_rencana' => $request->jenis_rencana,
            'sub_rencana' => $request->sub_rencana,
            'nama_kegiatan' => $request->nama_kegiatan,
            'sks_terhitung' => $request->sks_terhitung,
        ]);

        return redirect('/dashboard')->with('success', 'Rencana kerja berhasil diperbarui!');
    }

    // Menampilkan form edit Evaluasi Diri (FED)
    public function editFed($id)
    {
        $rencana = Rencana::findOrFail($id);
        return view('rencana.fed', compact('rencana'));
    }

    // Memperbarui FED (dengan lampiran yang dibuat opsional)
    public function updateFed(Request $request, $id)
    {
        $request->validate([
            'sks_realisasi' => 'required|integer|min:0',
            'lampiran_fed' => 'nullable|url', // Opsional (nullable) dan berformat URL
        ]);

        $rencana = Rencana::findOrFail($id);
        $rencana->update([
            'sks_realisasi' => $request->sks_realisasi,
            'lampiran_fed' => $request->lampiran_fed,
            'status_fed' => 'pending',
        ]);

        return redirect('/dashboard')->with('success', 'Evaluasi Diri (FED) berhasil disimpan!');
    }
    
    public function destroy($id)
    {
        $rencana = Rencana::findOrFail($id);
        $kategori = $rencana->jenis_rencana;
        $rencana->delete();

        return redirect('/rencana?tab=' . urlencode($kategori))
                    ->with('success', 'Rencana kerja berhasil dihapus!');
    }

    public function unsubmit($id)
    {
        $rencana = Rencana::findOrFail($id);
        $rencana->update([
            'status_frk' => 'pending',
            'status_fed' => 'pending'
        ]);

        return back()->with('success', 'Status kegiatan berhasil ditarik kembali ke posisi pending (akses edit dibuka untuk dosen).');
    }

    public function riwayat()
    {
        // Menampilkan seluruh riwayat kegiatan milik dosen yang sedang login
        $riwayatList = Rencana::where('id_dosen', auth()->id() ?? 1)->get();
        return view('rencana.riwayat', compact('riwayatList'));
    }
}