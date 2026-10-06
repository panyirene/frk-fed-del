<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - FRK & FED</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6">
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header Admin -->
        <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-sm flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-white/10 rounded-full">Administrator TSI</span>
                <h1 class="text-2xl font-bold mt-1">Panel Kontrol Utama, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-slate-400">Kelola periode akademik dan pantau rekapitulasi kinerja Tridharma dosen Institut Teknologi Del.</p>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2 rounded-xl transition">Keluar</button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Kotak Kontrol Admin: Pengaturan Periode Aktif -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">⚙️ Pengaturan Periode Aktif Akademik</h3>
            <form action="/admin/periode/update" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tahun Ajaran / Semester</label>
                    <input type="text" name="tahun_ajaran" value="Semester Genap 2025/2026" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Status Pengisian FRK & FED</label>
                    <select name="status_buka" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm bg-white">
                        <option value="dibuka">Dibuka (Aktif)</option>
                        <option value="ditutup">Ditutup</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-xl transition">Simpan Pengaturan</button>
                </div>
            </form>
        </div>

        <!-- Statistik Global -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase">Total Target SKS Institusi</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalTargetSks ?? 0 }} <span class="text-sm font-normal text-slate-500">SKS</span></h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase">Total Realisasi SKS Institusi</p>
                <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $totalRealisasiSks ?? 0 }} <span class="text-sm font-normal text-slate-500">SKS</span></h3>
            </div>
        </div>

        <!-- Tabel Rekap Keseluruhan & Kontrol Admin -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">⚙️ Pengaturan Periode & Jadwal Pengisian FRK/FED</h3>
            <form action="/admin/periode/update" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tahun Ajaran / Semester</label>
                    <input type="text" name="tahun_ajaran" value="{{ session('active_tahun_ajaran', 'Semester Genap 2025/2026') }}" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Mulai Pengisian</label>
                    <input type="date" name="tanggal_awal_pengisian" value="{{ session('tgl_awal', '2026-02-01') }}" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Akhir Pengisian</label>
                    <input type="date" name="tanggal_akhir_pengisian" value="{{ session('tgl_akhir', '2026-06-30') }}" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-xl transition shadow-sm">Simpan Periode</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>