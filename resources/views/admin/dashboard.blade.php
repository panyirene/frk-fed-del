<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - FRK & FED</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #1f2937;
        }

        .container {
            width: 92%;
            max-width: 1400px;
            margin: 30px auto;
        }

        .header {
            background: white;
            padding: 24px 28px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 25px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .section-title {
            font-size: 18px;
            margin: 28px 0 14px;
            font-weight: 700;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
        }

        .stat-description {
            margin-top: 6px;
            font-size: 12px;
            color: #9ca3af;
        }

        .workflow {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .workflow-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .workflow-card h3 {
            margin: 0 0 8px;
            font-size: 17px;
        }

        .workflow-card p {
            margin: 0 0 15px;
            color: #6b7280;
            font-size: 13px;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-primary {
            background: #47186c;
            color: white;
        }

        .btn-secondary {
            background: #eef2ff;
            color: #3730a3;
        }

        .btn-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .period-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 14px;
            align-items: end;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 11px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        .summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .summary-label {
            color: #6b7280;
            font-size: 13px;
        }

        .summary-value {
            margin-top: 7px;
            font-size: 25px;
            font-weight: 700;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background: #f8fafc;
            padding: 13px 12px;
            text-align: left;
            font-size: 12px;
            color: #475569;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 13px 12px;
            border-bottom: 1px solid #eef0f3;
            font-size: 13px;
            vertical-align: top;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #dcfce7;
            color: #166534;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-fed {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-empty {
            background: #f3f4f6;
            color: #6b7280;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #6b7280;
        }

        @media (max-width: 1000px) {
            .stats,
            .workflow {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 650px) {
            .container {
                width: 94%;
            }

            .stats,
            .workflow,
            .summary,
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <h1>Dashboard Admin - FRK & FED</h1>
        <p>Monitoring dan pengelolaan proses FRK, FED, Assessment, dan Tindak Lanjut.</p>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif


    {{-- PERIODE --}}
    <div class="section-title">
        Periode Akademik
    </div>

    <div class="period-card">

        <form method="POST" action="/admin/periode/update">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Tahun Ajaran</label>

                    <input
                        type="text"
                        name="tahun_ajaran"
                        class="form-control"
                        value="{{ session('active_tahun_ajaran', '2025/2026 Genap') }}"
                        placeholder="Contoh: 2026/2027 Ganjil"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Tanggal Awal Pengisian</label>

                    <input
                        type="date"
                        name="tanggal_awal_pengisian"
                        class="form-control"
                        value="{{ session('tgl_awal') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Tanggal Akhir Pengisian</label>

                    <input
                        type="date"
                        name="tanggal_akhir_pengisian"
                        class="form-control"
                        value="{{ session('tgl_akhir') }}"
                        required
                    >
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">
                        Simpan Periode
                    </button>
                </div>

            </div>

        </form>

    </div>


    {{-- STATISTIK --}}
    <div class="section-title">
        Monitoring Sistem
    </div>

    <div class="stats">

        <div class="stat-card">
            <div class="stat-title">Total Kegiatan</div>

            <div class="stat-value">
                {{ $stats['total_kegiatan'] ?? $rencanaList->count() }}
            </div>

            <div class="stat-description">
                Seluruh rencana kerja dosen
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">FRK Pending</div>

            <div class="stat-value">
                {{ $stats['frk_pending'] ?? $rencanaList->where('status_frk', 'pending')->count() }}
            </div>

            <div class="stat-description">
                Menunggu proses assessment
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">FRK Disetujui</div>

            <div class="stat-value">
                {{ $stats['frk_approved'] ?? $rencanaList->where('status_frk', 'approved')->count() }}
            </div>

            <div class="stat-description">
                Rencana kerja telah disetujui
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">FRK Revisi</div>

            <div class="stat-value">
                {{ $stats['frk_rejected'] ?? $rencanaList->where('status_frk', 'rejected')->count() }}
            </div>

            <div class="stat-description">
                Membutuhkan perbaikan
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">FED Pending</div>

            <div class="stat-value">
                {{ $stats['fed_pending'] ?? $rencanaList->where('status_fed', 'pending')->count() }}
            </div>

            <div class="stat-description">
                Belum selesai diproses
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">FED Disetujui</div>

            <div class="stat-value">
                {{ $stats['fed_approved'] ?? $rencanaList->where('status_fed', 'approved')->count() }}
            </div>

            <div class="stat-description">
                FED telah disetujui
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">Tindak Lanjut Aktif</div>

            <div class="stat-value">
                {{ $stats['followup_open'] ?? 0 }}
            </div>

            <div class="stat-description">
                Masih dalam proses
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-title">Tindak Lanjut Terlambat</div>

            <div class="stat-value">
                {{ $stats['followup_overdue'] ?? 0 }}
            </div>

            <div class="stat-description">
                Melewati batas waktu
            </div>
        </div>

    </div>


    {{-- MENU WORKFLOW --}}
    <div class="section-title">
        Menu Proses
    </div>

    <div class="workflow">

        <div class="workflow-card">
            <h3>FRK</h3>

            <p>
                Monitoring seluruh rencana kerja dosen.
            </p>

            <a href="/rencana" class="btn btn-primary">
                Lihat FRK
            </a>
        </div>


        <div class="workflow-card">
            <h3>Assessment</h3>

            <p>
                Review rencana kerja yang menunggu penilaian.
            </p>

            <a href="{{ route('assessment.index') }}" class="btn btn-primary">
                Buka Assessment
            </a>
        </div>


        <div class="workflow-card">
            <h3>Tindak Lanjut</h3>

            <p>
                Monitoring perbaikan dan verifikasi tindak lanjut.
            </p>

            <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-primary">
                Buka Tindak Lanjut
            </a>
        </div>


        <div class="workflow-card">
            <h3>FED</h3>

            <p>
                Monitoring realisasi dan evaluasi diri dosen.
            </p>

            <a href="/rencana" class="btn btn-secondary">
                Lihat Data FED
            </a>
        </div>

    </div>


    {{-- REKAP SKS --}}
    <div class="section-title">
        Rekapitulasi SKS
    </div>

    <div class="summary">

        <div class="summary-card">
            <div class="summary-label">
                Total Target SKS
            </div>

            <div class="summary-value">
                {{ $totalTargetSks ?? 0 }} SKS
            </div>
        </div>


        <div class="summary-card">
            <div class="summary-label">
                Total Realisasi SKS
            </div>

            <div class="summary-value">
                {{ $totalRealisasiSks ?? 0 }} SKS
            </div>
        </div>

    </div>


    {{-- MONITORING DATA --}}
    <div class="section-title">
        Monitoring Rencana Kerja Dosen
    </div>

    <div class="table-card">

        @if($rencanaList->count() > 0)

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Dosen</th>
                            <th>Kegiatan</th>
                            <th>Kategori</th>
                            <th>Target</th>
                            <th>Realisasi</th>
                            <th>FRK</th>
                            <th>FED</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($rencanaList as $index => $rencana)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $rencana->id_dosen }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $rencana->nama_kegiatan }}
                                    </strong>

                                    @if($rencana->sub_rencana)
                                        <br>
                                        <small style="color:#6b7280;">
                                            {{ $rencana->sub_rencana }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $rencana->jenis_rencana }}
                                </td>

                                <td>
                                    {{ $rencana->sks_terhitung ?? 0 }} SKS
                                </td>

                                <td>
                                    {{ $rencana->sks_realisasi ?? 0 }} SKS
                                </td>

                                <td>

                                    @if($rencana->status_frk === 'approved')

                                        <span class="badge badge-approved">
                                            Disetujui
                                        </span>

                                    @elseif($rencana->status_frk === 'rejected')

                                        <span class="badge badge-rejected">
                                            Revisi
                                        </span>

                                    @else

                                        <span class="badge badge-pending">
                                            Pending
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($rencana->status_fed === 'approved')

                                        <span class="badge badge-approved">
                                            Disetujui
                                        </span>

                                    @elseif($rencana->sks_realisasi !== null)

                                        <span class="badge badge-fed">
                                            Terisi
                                        </span>

                                    @else

                                        <span class="badge badge-empty">
                                            Belum
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty">
                Belum ada data rencana kerja dosen.
            </div>

        @endif

    </div>

</div>

</body>
</html>