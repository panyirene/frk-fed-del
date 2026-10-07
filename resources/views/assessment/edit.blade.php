<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Review Assessment</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f6fa;
        }

        .review-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .label-title {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .value-text {
            font-size: 15px;
            color: #212529;
        }

        .status-badge {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 20px;
        }
    </style>
</head>

<body>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">Review Assessment</h3>

            <p class="text-muted mb-0">
                Review rencana kerja dosen sebelum disetujui atau diminta revisi.
            </p>
        </div>

        <a href="{{ route('assessment.index') }}"
           class="btn btn-outline-secondary">
            ← Kembali
        </a>

    </div>


    {{-- ALERT --}}
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


    {{-- DATA RENCANA --}}
    <div class="card review-card mb-4">

        <div class="card-header bg-white py-3">
            <strong>Informasi Rencana Kerja</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="label-title">
                        Jenis Rencana
                    </div>

                    <div class="value-text">
                        {{ $assessment->rencana->jenis_rencana ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="label-title">
                        Sub Rencana
                    </div>

                    <div class="value-text">
                        {{ $assessment->rencana->sub_rencana ?? '-' }}
                    </div>

                </div>


                <div class="col-md-12">

                    <div class="label-title">
                        Nama Kegiatan
                    </div>

                    <div class="value-text fw-semibold">
                        {{ $assessment->rencana->nama_kegiatan ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="label-title">
                        SKS Terhitung
                    </div>

                    <div class="value-text">
                        {{ $assessment->rencana->sks_terhitung ?? 0 }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="label-title">
                        Status FRK
                    </div>

                    <div>
                        <span class="badge bg-warning text-dark status-badge">
                            {{ strtoupper($assessment->rencana->status_frk ?? 'pending') }}
                        </span>
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="label-title">
                        Jenis Assessment
                    </div>

                    <div class="value-text">
                        {{ strtoupper($assessment->jenis ?? '-') }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- FORM REVIEW --}}
    <div class="card review-card">

        <div class="card-header bg-white py-3">
            <strong>Hasil Review Asesor</strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route('assessment.review', $assessment->id) }}"
                method="POST"
            >

                @csrf


                {{-- KOMENTAR --}}
                <div class="mb-4">

                    <label
                        for="komentar"
                        class="form-label fw-semibold"
                    >
                        Komentar / Catatan Asesor
                    </label>

                    <textarea
                        name="komentar"
                        id="komentar"
                        rows="6"
                        class="form-control @error('komentar') is-invalid @enderror"
                        placeholder="Masukkan komentar atau catatan hasil review..."
                    >{{ old('komentar') }}</textarea>

                    @error('komentar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Komentar wajib diisi apabila rencana kerja meminta revisi.
                    </div>

                </div>


               <div class="d-flex justify-content-end gap-2">
                    <a
                        href="{{ route('assessment.index') }}"
                        class="btn btn-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        name="status"
                        value="rejected"
                        class="btn btn-danger"
                    >
                        Minta Revisi
                    </button>

                    <button
                        type="submit"
                        name="status"
                        value="approved"
                        class="btn btn-success"
                    >
                        Setujui
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>