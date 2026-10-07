<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Assessment</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f6fa;
        }

        .page-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .table th {
            font-size: 13px;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .badge-status {
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 20px;
        }
    </style>
</head>

<body>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">Assessment</h3>

            <p class="text-muted mb-0">
                Daftar assessment yang perlu diproses.
            </p>
        </div>

        <a href="{{ url('/dashboard') }}"
           class="btn btn-outline-secondary">
            ← Dashboard
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- TABLE --}}
    <div class="card page-card">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <strong>Daftar Assessment</strong>

                <span class="badge bg-primary">
                    {{ $assessments->count() }} Data
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($assessments->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>No</th>
                                <th>Dosen</th>
                                <th>Jenis</th>
                                <th>Kegiatan</th>
                                <th>SKS</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>

                        </thead>


                        <tbody>

                            @foreach($assessments as $assessment)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>
                                        {{ $assessment->rencana->id_dosen ?? '-' }}
                                    </td>


                                    <td>
                                        {{ strtoupper($assessment->jenis ?? '-') }}
                                    </td>


                                    <td>
                                        <strong>
                                            {{ $assessment->rencana->nama_kegiatan ?? '-' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            {{ $assessment->rencana->jenis_rencana ?? '-' }}
                                        </small>
                                    </td>


                                    <td>
                                        {{ $assessment->rencana->sks_terhitung ?? 0 }}
                                    </td>


                                    <td>

                                        @if($assessment->status === 'pending')

                                            <span class="badge bg-warning text-dark badge-status">
                                                PENDING
                                            </span>

                                        @elseif($assessment->status === 'approved')

                                            <span class="badge bg-success badge-status">
                                                APPROVED
                                            </span>

                                        @elseif($assessment->status === 'rejected')

                                            <span class="badge bg-danger badge-status">
                                                REJECTED
                                            </span>

                                        @else

                                            <span class="badge bg-secondary badge-status">
                                                {{ strtoupper($assessment->status ?? '-') }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if(auth()->user()->role === 'asesor')

                                            <a
                                                href="{{ route('assessment.edit', $assessment->id) }}"
                                                class="btn btn-sm btn-primary"
                                            >
                                                Review
                                            </a>

                                        @else

                                            <span class="text-muted">
                                                Monitoring
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3" style="font-size:40px;">
                        ✓
                    </div>

                    <h5>Tidak ada assessment</h5>

                    <p class="text-muted mb-0">
                        Belum ada assessment yang perlu diproses.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

</body>
</html>