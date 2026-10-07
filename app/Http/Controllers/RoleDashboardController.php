<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rencana;
use App\Models\FollowUp;

class RoleDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // =========================================================
        // 1. ADMIN
        // =========================================================
        if ($user->role === 'admin') {

            $rencanaList = Rencana::latest('id_rencana')->get();

            $stats = [
                'total_kegiatan' => Rencana::count(),

                'frk_pending' => Rencana::where('status_frk', 'pending')
                    ->count(),

                'frk_approved' => Rencana::where('status_frk', 'approved')
                    ->count(),

                'frk_rejected' => Rencana::where('status_frk', 'rejected')
                    ->count(),

                'fed_pending' => Rencana::where('status_fed', 'pending')
                    ->count(),

                'fed_approved' => Rencana::where('status_fed', 'approved')
                    ->count(),

                'followup_open' => FollowUp::whereIn('status', [
                    'open',
                    'in_progress',
                    'submitted',
                ])->count(),

                'followup_overdue' => FollowUp::whereNotIn('status', [
                    'verified',
                    'closed',
                ])
                ->whereNotNull('deadline')
                ->whereDate('deadline', '<', today())
                ->count(),
            ];

            $totalTargetSks = Rencana::sum('sks_terhitung');
            $totalRealisasiSks = Rencana::sum('sks_realisasi');

            return view('admin.dashboard', compact(
                'rencanaList',
                'totalTargetSks',
                'totalRealisasiSks',
                'stats'
            ));
        }


        // =========================================================
        // 2. ASESOR
        // =========================================================
        if ($user->role === 'asesor') {

            /*
             * Tetap mengambil seluruh rencana untuk kebutuhan
             * informasi dashboard.
             *
             * Data assessment sendiri difilter berdasarkan
             * assessor_id di AssessmentController.
             */
            $rencanaList = Rencana::latest('id_rencana')->get();

            /*
             * Statistik FRK
             */
            $stats = [
                'total' => $rencanaList->count(),

                'pending' => $rencanaList
                    ->where('status_frk', 'pending')
                    ->count(),

                'approved' => $rencanaList
                    ->where('status_frk', 'approved')
                    ->count(),

                'rejected' => $rencanaList
                    ->where('status_frk', 'rejected')
                    ->count(),

                /*
                 * Tindak lanjut hanya yang menjadi tanggung jawab
                 * asesor yang sedang login.
                 */
                'followup' => FollowUp::where(
                    'assessor_id',
                    $user->id
                )
                ->whereIn('status', [
                    'open',
                    'in_progress',
                    'submitted',
                ])
                ->count(),
            ];

            return view('asesor.dashboard', compact(
                'rencanaList',
                'stats'
            ));
        }


        // =========================================================
        // 3. DOSEN
        // =========================================================

        /*
         * Dosen hanya boleh melihat rencana miliknya sendiri.
         */
        $rencanaList = Rencana::where(
            'id_dosen',
            $user->id
        )
        ->latest('id_rencana')
        ->get();


        /*
         * Statistik tindak lanjut dosen.
         *
         * Gunakan dosen_id karena kolom tersebut memang
         * ada pada tabel follow_ups.
         */
        $stats = [
            'total' => $rencanaList->count(),

            'frk_approved' => $rencanaList
                ->where('status_frk', 'approved')
                ->count(),

            'fed_completed' => $rencanaList
                ->whereNotNull('sks_realisasi')
                ->count(),

            'followup' => FollowUp::where(
                'dosen_id',
                $user->id
            )
            ->whereIn('status', [
                'open',
                'in_progress',
                'submitted',
            ])
            ->count(),

            'followup_closed' => FollowUp::where(
                'dosen_id',
                $user->id
            )
            ->whereIn('status', [
                'verified',
                'closed',
            ])
            ->count(),
        ];


        $totalTargetSks = $rencanaList->sum('sks_terhitung');

        $totalRealisasiSks = $rencanaList->sum('sks_realisasi');


        return view('dosen.dashboard', compact(
            'rencanaList',
            'totalTargetSks',
            'totalRealisasiSks',
            'stats'
        ));
    }


    // =============================================================
    // UPDATE PERIODE
    // =============================================================

    public function updatePeriode(Request $request)
    {
        /*
         * Hanya admin yang boleh mengubah periode.
         */
        abort_unless(
            auth()->user()->role === 'admin',
            403
        );

        $data = $request->validate([
            'tahun_ajaran' => 'required|string|max:100',

            'tanggal_awal_pengisian' => [
                'required',
                'date',
            ],

            'tanggal_akhir_pengisian' => [
                'required',
                'date',
                'after_or_equal:tanggal_awal_pengisian',
            ],
        ]);


        /*
         * Untuk sementara masih menggunakan session
         * seperti sistem aplikasi saat ini.
         */
        session()->put([
            'active_tahun_ajaran' => $data['tahun_ajaran'],
            'tgl_awal' => $data['tanggal_awal_pengisian'],
            'tgl_akhir' => $data['tanggal_akhir_pengisian'],
        ]);


        return back()->with(
            'success',
            'Periode akademik dan batas pengisian berhasil diperbarui oleh Administrator!'
        );
    }
}