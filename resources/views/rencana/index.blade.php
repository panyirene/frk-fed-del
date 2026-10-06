<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi FRK & FED</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- Header & Profil User yang Login -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full uppercase">Role: {{ auth()->user()->role ?? 'Dosen' }}</span>
                <h1 class="text-2xl font-bold text-slate-800 mt-1">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-slate-500">Sistem Pengelolaan Kinerja Tridharma Institut Teknologi Del.</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Tombol Buat Rencana hanya untuk Dosen / Admin -->
                @if(auth()->user()->role == 'dosen' || auth()->user()->role == 'admin')
                    <a href="/rencana/create" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
                        + Buat Rencana (FRK)
                    </a>
                @endif
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2.5 rounded-xl transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistik SKS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Target SKS (FRK)</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalTargetSks ?? 0 }} <span class="text-sm font-normal text-slate-500">SKS</span></h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">📊</div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Realisasi SKS (FED)</p>
                    <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $totalRealisasiSks ?? 0 }} <span class="text-sm font-normal text-slate-500">SKS</span></h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl text-xl">✅</div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tab Navigasi Tridharma -->
        <div class="flex space-x-2 border-b border-slate-200 pb-2">
            @foreach(['Pendidikan', 'Penelitian', 'Pengabdian', 'Penunjang'] as $tab)
                <a href="/rencana?tab={{ $tab }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg transition {{ ($kategori ?? 'Pendidikan') == $tab ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                   {{ $tab }}
                </a>
            @endforeach
        </div>

        <!-- Tabel Rekapitulasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">#</th>
                        <th class="p-4">Kegiatan ({{ $kategori ?? 'Pendidikan' }})</th>
                        <th class="p-4">Beban SKS</th>
                        <th class="p-4">Capaian & Bukti (FED)</th>
                        <th class="p-4">Status & Ulasan Asesor</th>
                        <th class="p-4">Aksi / Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($rencanaList as $index => $item)
                        <tr>
                            <td class="p-4 font-medium">{{ $index + 1 }}</td>
                            <td class="p-4">
                                <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">{{ $item->sub_rencana }}</span>
                                <div class="font-semibold text-slate-900 mt-1">{{ $item->nama_kegiatan }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-slate-900">Target: {{ $item->sks_terhitung }} SKS</div>
                                @if($item->sks_realisasi !== null)
                                    <div class="text-xs text-emerald-600 font-medium">Realisasi: {{ $item->sks_realisasi }} SKS</div>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($item->lampiran_fed)
                                    <a href="{{ $item->lampiran_fed }}" target="_blank" class="text-blue-600 underline text-xs font-medium">Lihat Dokumen</a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum diisi</span>
                                @endif
                            </td>
                            <td class="p-4 space-y-2">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full 
                                    {{ $item->status_frk == 'approved' ? 'bg-emerald-50 text-emerald-600' : ($item->status_frk == 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                    FRK: {{ ucfirst($item->status_frk) }}
                                </span>
                                @if($item->komentar_asesor)
                                    <p class="text-xs text-rose-500 bg-rose-50 p-2 rounded border border-rose-100">Catatan: {{ $item->komentar_asesor }}</p>
                                @endif
                            </td>
                            <td class="p-4 space-y-3">
                                <!-- Tombol Edit FED & Hapus -->
                                @if(auth()->user()->role == 'dosen' || auth()->user()->role == 'admin')
                                    <div class="flex items-center space-x-2">
                                        <a href="/rencana/{{ $item->id_rencana }}/fed" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-3 py-1.5 rounded-lg transition">
                                            ✏️ Edit FED
                                        </a>
                                        <form action="/rencana/{{ $item->id_rencana }}/delete" method="POST" onsubmit="return confirm('Hapus rencana kegiatan ini?')">
                                            @csrf
                                            <button type="submit" class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-600 font-medium px-3 py-1.5 rounded-lg transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <!-- Panel Asesor -->
                                @if(auth()->user()->role == 'asesor' || auth()->user()->role == 'admin')
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Panel Asesor</span>
                                        <form action="/rencana/{{ $item->id_rencana }}/status" method="POST" class="space-y-2">
                                            @csrf
                                            <select name="status_frk" class="w-full text-xs rounded-lg border-slate-300 border px-2 py-1 bg-white">
                                                <option value="pending" {{ $item->status_frk == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $item->status_frk == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ $item->status_frk == 'rejected' ? 'selected' : '' }}>Rejected (Revisi)</option>
                                            </select>
                                            <input type="text" name="komentar_asesor" value="{{ $item->komentar_asesor }}" placeholder="Alasan jika ditolak..." class="w-full text-xs rounded-lg border-slate-300 border px-2 py-1">
                                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white text-xs py-1 rounded-lg transition">Simpan Review</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 italic">
                                Belum ada data rekapitulasi kinerja untuk kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>