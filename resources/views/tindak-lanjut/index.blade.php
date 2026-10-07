<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tindak Lanjut - FRK/FED</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }
    </style>
</head>

<body>


<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tindak Lanjut</h3>
            <p class="text-muted mb-0">
                Daftar temuan dan perbaikan kegiatan FRK/FED
            </p>
        </div>
    </div>


    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kegiatan</th>
                            <th>Temuan</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($followUps as $item)

                        <tr>

                            <td>
                                <span class="fw-semibold">
                                    {{ $item->kode }}
                                </span>
                            </td>

                            <td>
                                {{ $item->rencana->nama_kegiatan ?? '-' }}
                            </td>

                            <td style="max-width: 300px;">
                                {{ $item->temuan }}
                            </td>

                            <td>

                                @php
                                    $priorityClass = match($item->prioritas) {
                                        'urgent' => 'danger',
                                        'high' => 'warning',
                                        'medium' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge text-bg-{{ $priorityClass }}">
                                    {{ strtoupper($item->prioritas) }}
                                </span>

                            </td>

                            <td>

                                @php
                                    $statusClass = match($item->status) {
                                        'open' => 'danger',
                                        'in_progress' => 'warning',
                                        'submitted' => 'info',
                                        'under_review' => 'primary',
                                        'verified' => 'success',
                                        'closed' => 'success',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge text-bg-{{ $statusClass }}">
                                    {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                </span>

                            </td>

                            <td>
                                {{ $item->deadline?->format('d M Y') ?? '-' }}
                            </td>

                            <td>

                                @if(
                                    auth()->user()->role === 'dosen'
                                    &&
                                    in_array($item->status, ['open', 'in_progress'])
                                )

                                    <button
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalSubmit{{ $item->id }}"
                                    >
                                        Kirim Perbaikan
                                    </button>

                                @elseif(
                                    auth()->user()->role === 'asesor'
                                    &&
                                    $item->status === 'submitted'
                                )

                                    <button
                                        class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalVerify{{ $item->id }}"
                                    >
                                        Review
                                    </button>

                                @elseif(
                                    auth()->user()->role === 'admin'
                                    &&
                                    $item->status === 'verified'
                                )

                                    <form
                                        method="POST"
                                        action="{{ route('tindak-lanjut.close', $item->id) }}"
                                        class="d-inline"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-success"
                                        >
                                            Tutup
                                        </button>

                                    </form>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>


                        {{-- MODAL DOSEN --}}

                        @if(
                            auth()->user()->role === 'dosen'
                            &&
                            in_array($item->status, ['open', 'in_progress'])
                        )

                        <div
                            class="modal fade"
                            id="modalSubmit{{ $item->id }}"
                            tabindex="-1"
                        >

                            <div class="modal-dialog">

                                <form
                                    method="POST"
                                    action="{{ url('/tindak-lanjut/'.$item->id.'/submit') }}"
                                >

                                    @csrf

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Kirim Perbaikan
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>

                                        <div class="modal-body">

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Temuan
                                                </label>

                                                <div class="alert alert-warning">
                                                    {{ $item->temuan }}
                                                </div>

                                            </div>

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Tanggapan / Perbaikan
                                                </label>

                                                <textarea
                                                    name="tanggapan_dosen"
                                                    class="form-control"
                                                    rows="4"
                                                    required
                                                ></textarea>

                                            </div>

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Link Bukti Perbaikan
                                                </label>

                                                <input
                                                    type="url"
                                                    name="bukti_perbaikan"
                                                    class="form-control"
                                                    placeholder="https://..."
                                                >

                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Batal
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >
                                                Kirim Perbaikan
                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        @endif


                        {{-- MODAL ASSESSOR --}}

                        @if(
                            auth()->user()->role === 'asesor'
                            &&
                            $item->status === 'submitted'
                        )

                        <div
                            class="modal fade"
                            id="modalVerify{{ $item->id }}"
                            tabindex="-1"
                        >

                            <div class="modal-dialog">

                                <form
                                    method="POST"
                                    action="{{ url('/tindak-lanjut/'.$item->id.'/verify') }}"
                                >

                                    @csrf

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Verifikasi Tindak Lanjut
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>

                                        <div class="modal-body">

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Perbaikan Dosen
                                                </label>

                                                <div class="alert alert-info">
                                                    {{ $item->tanggapan_dosen }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">

                                            <button
                                                type="submit"
                                                name="status"
                                                value="revision"
                                                class="btn btn-warning"
                                            >
                                                Minta Perbaikan Lagi
                                            </button>

                                            <button
                                                type="submit"
                                                name="status"
                                                value="verified"
                                                class="btn btn-success"
                                            >
                                                ✓ Verifikasi
                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        @endif

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted py-5"
                            >
                                Belum ada tindak lanjut.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<!-- Bootstrap untuk komponen dan utility class yang dipakai halaman ini -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
