<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rencana;

class RoleDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Jika yang login adalah Admin (melihat rekap seluruh institusi)
        if ($user->role == 'admin') {
            $rencanaList = Rencana::all();
            $totalTargetSks = Rencana::sum('sks_terhitung');
            $totalRealisasiSks = Rencana::sum('sks_realisasi');
            return view('admin.dashboard', compact('rencanaList', 'totalTargetSks', 'totalRealisasiSks'));
        }
        
        // 2. Jika yang login adalah Asesor (melihat daftar usulan dosen yang perlu direview)
        if ($user->role == 'asesor') {
            // Tampilkan seluruh data rencana agar data baru dengan status 'pending' bisa terlihat oleh asesor
            $rencanaList = Rencana::all(); 
            return view('asesor.dashboard', compact('rencanaList'));
        }
        
        // 3. Jika yang login adalah Dosen (melihat FRK & FED milik sendiri)
        $rencanaList = Rencana::where('id_dosen', $user->id)->get();
        $totalTargetSks = $rencanaList->sum('sks_terhitung');
        $totalRealisasiSks = $rencanaList->sum('sks_realisasi');
        
        return view('dosen.dashboard', compact('rencanaList', 'totalTargetSks', 'totalRealisasiSks'));
    }

    public function updatePeriode(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string',
            'tanggal_awal_pengisian' => 'required|date',
            'tanggal_akhir_pengisian' => 'required|date',
        ]);

        // Simpan data periode ke session atau database
        session()->put('active_tahun_ajaran', $request->tahun_ajaran);
        session()->put('tgl_awal', $request->tanggal_awal_pengisian);
        session()->put('tgl_akhir', $request->tanggal_akhir_pengisian);

        return back()->with('success', 'Periode akademik dan batas pengisian berhasil diperbarui oleh Administrator!');
    }
}