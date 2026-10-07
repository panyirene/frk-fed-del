<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Dosen - FRK & FED</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

<div class="min-h-screen">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <header class="bg-blue-700 text-white shadow">
        <div class="max-w-7xl mx-auto px-6 py-5">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                <div>
                    <h1 class="text-2xl font-bold">
                        Portal Dosen
                    </h1>

                    <p class="text-blue-100 text-sm mt-1">
                        Pengelolaan FRK & FED
                    </p>
                </div>

                <div class="text-sm">
                    <div class="font-semibold">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="text-blue-100">
                        {{ auth()->user()->email }}
                    </div>
                </div>

            </div>

        </div>
    </header>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}
    <main class="max-w-7xl mx-auto px-6 py-8">

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if(session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif


        {{-- =====================================================
             PAGE TITLE + ACTION
        ====================================================== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Dashboard
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Pantau rencana kerja, evaluasi diri, dan tindak lanjut Anda.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">

                {{-- KELOLA FRK --}}
                <a
                    href="{{ url('/rencana') }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-sm font-medium"
                >
                    Kelola FRK
                </a>

                {{-- TINDAK LANJUT --}}
                <a
                    href="{{ route('tindak-lanjut.index') }}"
                    class="px-4 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600 text-sm font-medium"
                >
                    Tindak Lanjut
                </a>

                {{-- TAMBAH RENCANA --}}
                <a
                    href="{{ url('/rencana/create') }}"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium"
                >
                    + Tambah Rencana Kerja
                </a>

            </div>

        </div>


        {{-- =====================================================
             STAT CARDS
        ====================================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            {{-- TOTAL KEGIATAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Total Kegiatan
                </p>

                <p class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $rencanaList->count() }}
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    Seluruh rencana kerja
                </p>

            </div>


            {{-- FRK DISETUJUI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    FRK Disetujui
                </p>

                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $rencanaList->where('status_frk', 'approved')->count() }}
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    Rencana kerja telah disetujui
                </p>

            </div>


            {{-- FED TERISI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    FED Terisi
                </p>

                <p class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $rencanaList->whereNotNull('sks_realisasi')->count() }}
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    Kegiatan sudah memiliki realisasi
                </p>

            </div>


            {{-- TINDAK LANJUT --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">

                <p class="text-sm text-gray-500">
                    Tindak Lanjut
                </p>

                <p class="text-3xl font-bold text-orange-500 mt-2">
                    {{ $stats['followup'] ?? 0 }}
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    Perlu ditindaklanjuti
                </p>

            </div>

        </div>


        {{-- =====================================================
             WORKFLOW
        ====================================================== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">

            <div class="mb-5">

                <h3 class="text-lg font-bold text-gray-800">
                    Alur Pengelolaan Kinerja
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Pantau tahapan pengajuan dan penyelesaian kegiatan.
                </p>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- STEP 1 --}}
                <a
                    href="{{ url('/rencana') }}"
                    class="border rounded-xl p-5 hover:border-blue-400 hover:bg-blue-50 transition"
                >

                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                            1
                        </div>

                        <span class="text-xs text-blue-600 font-medium">
                            FRK
                        </span>

                    </div>

                    <h4 class="font-semibold mt-4">
                        Rencana Kerja
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Susun dan ajukan rencana kegiatan.
                    </p>

                </a>


                {{-- STEP 2 --}}
                <a
                    href="{{ url('/rencana') }}"
                    class="border rounded-xl p-5 hover:border-green-400 hover:bg-green-50 transition"
                >

                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">
                            2
                        </div>

                        <span class="text-xs text-green-600 font-medium">
                            FED
                        </span>

                    </div>

                    <h4 class="font-semibold mt-4">
                        Evaluasi Diri
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Isi realisasi dan bukti kegiatan.
                    </p>

                </a>


                {{-- STEP 3 --}}
                <div class="border rounded-xl p-5">

                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                            3
                        </div>

                        <span class="text-xs text-purple-600 font-medium">
                            REVIEW
                        </span>

                    </div>

                    <h4 class="font-semibold mt-4">
                        Assessment
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Menunggu proses review asesor.
                    </p>

                </div>


                {{-- STEP 4 --}}
                <a
                    href="{{ route('tindak-lanjut.index') }}"
                    class="border rounded-xl p-5 hover:border-orange-400 hover:bg-orange-50 transition"
                >

                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                            4
                        </div>

                        <span class="text-xs text-orange-600 font-medium">
                            FOLLOW UP
                        </span>

                    </div>

                    <h4 class="font-semibold mt-4">
                        Tindak Lanjut
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Perbaiki temuan dan kirim bukti.
                    </p>

                </a>

            </div>

        </div>


        {{-- =====================================================
             HITUNG PROGRESS
        ====================================================== --}}
        @php

            $totalKegiatan = $rencanaList->count();

            $frkApproved = $rencanaList
                ->where('status_frk', 'approved')
                ->count();

            $fedCompleted = $rencanaList
                ->whereNotNull('sks_realisasi')
                ->count();

            $followUpActive = $stats['followup'] ?? 0;

            $frkProgress = $totalKegiatan > 0
                ? round(($frkApproved / $totalKegiatan) * 100)
                : 0;

            $fedProgress = $totalKegiatan > 0
                ? round(($fedCompleted / $totalKegiatan) * 100)
                : 0;

        @endphp


        {{-- =====================================================
             PROGRESS
        ====================================================== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">

            <h3 class="text-lg font-bold text-gray-800">
                Progress
            </h3>

            <div class="space-y-6 mt-6">

                {{-- FRK --}}
                <div>

                    <div class="flex justify-between text-sm mb-2">

                        <span class="font-medium">
                            FRK Disetujui
                        </span>

                        <span class="text-gray-500">
                            {{ $frkApproved }} / {{ $totalKegiatan }}
                        </span>

                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5">

                        <div
                            class="bg-blue-600 h-2.5 rounded-full"
                            style="width: {{ $frkProgress }}%"
                        ></div>

                    </div>

                </div>


                {{-- FED --}}
                <div>

                    <div class="flex justify-between text-sm mb-2">

                        <span class="font-medium">
                            FED Terisi
                        </span>

                        <span class="text-gray-500">
                            {{ $fedCompleted }} / {{ $totalKegiatan }}
                        </span>

                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5">

                        <div
                            class="bg-green-500 h-2.5 rounded-full"
                            style="width: {{ $fedProgress }}%"
                        ></div>

                    </div>

                </div>


                {{-- TINDAK LANJUT --}}
                <div>

                    <div class="flex justify-between text-sm mb-2">

                        <span class="font-medium">
                            Tindak Lanjut Aktif
                        </span>

                        <span class="text-gray-500">
                            {{ $followUpActive }}
                        </span>

                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5">

                        <div
                            class="{{ $followUpActive > 0 ? 'bg-orange-500' : 'bg-green-500' }} h-2.5 rounded-full"
                            style="width: 100%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             SUMMARY SKS
        ====================================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">

            {{-- TARGET --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

                <p class="text-sm text-gray-500">
                    Total Target SKS
                </p>

                <p class="text-3xl font-bold text-blue-700 mt-2">
                    {{ $totalTargetSks }}
                </p>

            </div>


            {{-- REALISASI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

                <p class="text-sm text-gray-500">
                    Total Realisasi SKS
                </p>

                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $totalRealisasiSks }}
                </p>

            </div>

        </div>


        {{-- =====================================================
             DAFTAR RENCANA KERJA
        ====================================================== --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-bold text-gray-800">
                    Daftar Rencana Kerja
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Daftar kegiatan yang Anda kelola.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                No
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Kegiatan
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Kategori
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                Target
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                Realisasi
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                FRK
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                FED
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($rencanaList as $index => $rencana)

                            <tr class="hover:bg-gray-50">

                                {{-- NO --}}
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $index + 1 }}
                                </td>


                                {{-- KEGIATAN --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-800">
                                        {{ $rencana->nama_kegiatan }}
                                    </div>

                                    @if($rencana->sub_rencana)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $rencana->sub_rencana }}
                                        </div>
                                    @endif

                                </td>


                                {{-- KATEGORI --}}
                                <td class="px-6 py-4">

                                    <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">
                                        {{ $rencana->jenis_rencana }}
                                    </span>

                                </td>


                                {{-- TARGET --}}
                                <td class="px-6 py-4 text-center">
                                    {{ $rencana->sks_terhitung }}
                                </td>


                                {{-- REALISASI --}}
                                <td class="px-6 py-4 text-center">
                                    {{ $rencana->sks_realisasi ?? '-' }}
                                </td>


                                {{-- STATUS FRK --}}
                                <td class="px-6 py-4 text-center">

                                    @if($rencana->status_frk === 'approved')

                                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                            Disetujui
                                        </span>

                                    @elseif($rencana->status_frk === 'rejected')

                                        <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                            Revisi
                                        </span>

                                    @else

                                        <span class="px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS FED --}}
                                <td class="px-6 py-4 text-center">

                                    @if($rencana->sks_realisasi !== null)

                                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                            Terisi
                                        </span>

                                    @else

                                        <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                                            Belum
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center">

                                    <div class="relative inline-block text-left">

                                        <details>

                                            <summary class="cursor-pointer list-none px-3 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-sm">
                                                Aksi
                                            </summary>

                                            <div class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-20">

                                                {{-- EDIT FRK --}}
                                                <a
                                                    href="{{ url('/rencana/' . $rencana->id_rencana . '/edit') }}"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                                >
                                                    Edit FRK
                                                </a>


                                                {{-- EDIT FED --}}
                                                <a
                                                    href="{{ url('/rencana/' . $rencana->id_rencana . '/fed') }}"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                                >
                                                    Edit FED
                                                </a>


                                                {{-- HAPUS --}}
                                                <form
                                                    action="{{ url('/rencana/' . $rencana->id_rencana) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus rencana kerja ini?')"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                    >
                                                        Hapus
                                                    </button>

                                                </form>

                                            </div>

                                        </details>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="px-6 py-12 text-center text-gray-500"
                                >

                                    <div class="text-lg font-medium">
                                        Belum ada rencana kerja.
                                    </div>

                                    <p class="text-sm mt-1">
                                        Silakan tambahkan rencana kerja terlebih dahulu.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>
</html>