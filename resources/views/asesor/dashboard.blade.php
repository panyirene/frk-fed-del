<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Portal Asesor - Manajemen Kinerja Tridharma</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 min-h-screen p-6">

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-emerald-600 text-white p-6 rounded-2xl shadow-sm flex justify-between items-center">

        <div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-white/20 rounded-full">
                Portal Asesor / Reviewer
            </span>

            <h1 class="text-2xl font-bold mt-2">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>

            <p class="text-sm text-emerald-100 mt-1">
                Kelola assessment dan verifikasi tindak lanjut kinerja dosen.
            </p>
        </div>

        <form action="/logout" method="POST">
            @csrf

            <button
                type="submit"
                class="bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2 rounded-xl transition">
                Keluar
            </button>
        </form>

    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm shadow-sm">
            {{ session('success') }}
        </div>
    @endif


    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm shadow-sm">
            {{ session('error') }}
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- STATISTIK --}}
    {{-- ========================================================= --}}

    @php

        $totalUsulan = isset($stats['total'])
            ? $stats['total']
            : 0;

        $pendingCount = isset($stats['pending'])
            ? $stats['pending']
            : 0;

        $approvedCount = isset($stats['approved'])
            ? $stats['approved']
            : 0;

        $rejectedCount = isset($stats['rejected'])
            ? $stats['rejected']
            : 0;

        $followUpCount = isset($stats['followup'])
            ? $stats['followup']
            : 0;

    @endphp


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- TOTAL --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">

            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                Total Kegiatan
            </p>

            <h3 class="text-2xl font-bold text-slate-800 mt-1">
                {{ $totalUsulan }}
            </h3>

            <p class="text-xs text-slate-400 mt-1">
                Data rencana kerja
            </p>

        </div>


        {{-- PENDING --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-amber-100">

            <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider">
                Menunggu Assessment
            </p>

            <h3 class="text-2xl font-bold text-amber-600 mt-1">
                {{ $pendingCount }}
            </h3>

            <a
                href="{{ route('assessment.index') }}"
                class="inline-block mt-3 text-xs font-semibold text-amber-600 hover:text-amber-800">
                Review sekarang →
            </a>

        </div>


        {{-- APPROVED --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100">

            <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">
                Disetujui
            </p>

            <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                {{ $approvedCount }}
            </h3>

            <p class="text-xs text-slate-400 mt-1">
                Assessment selesai
            </p>

        </div>


        {{-- REVISI --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-rose-100">

            <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider">
                Revisi
            </p>

            <h3 class="text-2xl font-bold text-rose-600 mt-1">
                {{ $rejectedCount }}
            </h3>

            <p class="text-xs text-slate-400 mt-1">
                Memerlukan perbaikan
            </p>

        </div>


        {{-- FOLLOW UP --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-blue-100">

            <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">
                Tindak Lanjut
            </p>

            <h3 class="text-2xl font-bold text-blue-600 mt-1">
                {{ $followUpCount }}
            </h3>

            <a
                href="{{ route('tindak-lanjut.index') }}"
                class="inline-block mt-3 text-xs font-semibold text-blue-600 hover:text-blue-800">
                Verifikasi →
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MENU UTAMA --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- ASSESSMENT --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-xl">
                📋
            </div>

            <h2 class="text-lg font-bold text-slate-800 mt-4">
                Assessment FRK / FED
            </h2>

            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Review rencana kerja dan evaluasi diri dosen yang
                masuk ke dalam daftar assessment Anda.
            </p>

            <a
                href="{{ route('assessment.index') }}"
                class="inline-flex items-center justify-center mt-5 w-full bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold py-2.5 rounded-xl transition">

                Buka Assessment

            </a>

        </div>


        {{-- TINDAK LANJUT --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-xl">
                🔎
            </div>

            <h2 class="text-lg font-bold text-slate-800 mt-4">
                Verifikasi Tindak Lanjut
            </h2>

            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Periksa tanggapan dan bukti perbaikan yang dikirim dosen.
                Verifikasi jika sudah sesuai atau kembalikan untuk revisi.
            </p>

            <a
                href="{{ route('tindak-lanjut.index') }}"
                class="inline-flex items-center justify-center mt-5 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-xl transition">

                Buka Tindak Lanjut

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ALUR KERJA --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

        <h2 class="text-lg font-bold text-slate-800">
            Alur Kerja Asesor
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Tahapan yang dilakukan asesor dalam proses FRK, FED dan tindak lanjut.
        </p>


        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-6">

            {{-- STEP 1 --}}
            <div class="bg-slate-50 rounded-xl p-4 text-center">

                <div class="text-xl">
                    📄
                </div>

                <p class="text-xs font-semibold text-slate-700 mt-2">
                    FRK / FED
                </p>

                <p class="text-xs text-slate-400 mt-1">
                    Dosen mengirim data
                </p>

            </div>


            {{-- STEP 2 --}}
            <div class="bg-amber-50 rounded-xl p-4 text-center">

                <div class="text-xl">
                    📋
                </div>

                <p class="text-xs font-semibold text-amber-700 mt-2">
                    Assessment
                </p>

                <p class="text-xs text-slate-400 mt-1">
                    Asesor melakukan review
                </p>

            </div>


            {{-- STEP 3 --}}
            <div class="bg-rose-50 rounded-xl p-4 text-center">

                <div class="text-xl">
                    🔧
                </div>

                <p class="text-xs font-semibold text-rose-700 mt-2">
                    Revisi
                </p>

                <p class="text-xs text-slate-400 mt-1">
                    Jika terdapat temuan
                </p>

            </div>


            {{-- STEP 4 --}}
            <div class="bg-blue-50 rounded-xl p-4 text-center">

                <div class="text-xl">
                    📎
                </div>

                <p class="text-xs font-semibold text-blue-700 mt-2">
                    Bukti Perbaikan
                </p>

                <p class="text-xs text-slate-400 mt-1">
                    Dosen mengirim bukti
                </p>

            </div>


            {{-- STEP 5 --}}
            <div class="bg-emerald-50 rounded-xl p-4 text-center">

                <div class="text-xl">
                    ✅
                </div>

                <p class="text-xs font-semibold text-emerald-700 mt-2">
                    Verified
                </p>

                <p class="text-xs text-slate-400 mt-1">
                    Asesor memverifikasi
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CATATAN TUGAS --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-lg font-bold text-slate-800">
                    Tugas Asesor
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Fokus pada pekerjaan yang membutuhkan tindakan Anda.
                </p>

            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">

            <div class="border border-amber-100 bg-amber-50 rounded-xl p-4">

                <p class="text-sm font-semibold text-amber-800">
                    Assessment Menunggu
                </p>

                <p class="text-2xl font-bold text-amber-600 mt-1">
                    {{ $pendingCount }}
                </p>

                <a
                    href="{{ route('assessment.index') }}"
                    class="text-xs font-semibold text-amber-700 hover:text-amber-900">

                    Buka daftar assessment →

                </a>

            </div>


            <div class="border border-blue-100 bg-blue-50 rounded-xl p-4">

                <p class="text-sm font-semibold text-blue-800">
                    Tindak Lanjut Menunggu
                </p>

                <p class="text-2xl font-bold text-blue-600 mt-1">
                    {{ $followUpCount }}
                </p>

                <a
                    href="{{ route('tindak-lanjut.index') }}"
                    class="text-xs font-semibold text-blue-700 hover:text-blue-900">

                    Buka daftar tindak lanjut →

                </a>

            </div>

        </div>

    </div>


</div>

</body>
</html>