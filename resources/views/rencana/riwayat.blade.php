<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Kegiatan Tridharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-800">📜 Riwayat Arsip Kegiatan Tridharma</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar seluruh rekam jejak pelaksanaan kinerja semester lalu.</p>
            </div>
            <a href="/dashboard" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium px-4 py-2 rounded-xl transition">Kembali ke Dashboard</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase">
                        <th class="p-4">#</th>
                        <th class="p-4">Tahun Ajaran</th>
                        <th class="p-4">Bidang & Kegiatan</th>
                        <th class="p-4">Target / Realisasi</th>
                        <th class="p-4">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($riwayatList as $index => $item)
                        <tr>
                            <td class="p-4">{{ $index + 1 }}</td>
                            <td class="p-4 font-medium text-slate-600">{{ $item->tahun_ajaran ?? '2025/2026 Genap' }}</td>
                            <td class="p-4">
                                <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">{{ $item->jenis_rencana }}</span>
                                <div class="font-semibold text-slate-900 mt-1">{{ $item->nama_kegiatan }}</div>
                            </td>
                            <td class="p-4">Target: {{ $item->sks_terhitung }} SKS <br> <span class="text-xs text-emerald-600">Realisasi: {{ $item->sks_realisasi ?? 0 }} SKS</span></td>
                            <td class="p-4">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $item->status_frk == 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ ucfirst($item->status_frk) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400 italic">Belum ada riwayat kegiatan tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>