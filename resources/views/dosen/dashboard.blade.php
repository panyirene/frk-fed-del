<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Dosen - Manajemen Kinerja Tridharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header Portal Dosen -->
        <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-sm flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-white/25 rounded-full">Portal Dosen / Pelaksana</span>
                <h1 class="text-2xl font-bold mt-1">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-blue-100">Kelola Form Rencana Kerja (FRK) dan Evaluasi Diri (FED) kinerja Tridharma Anda semester ini.</p>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2.5 rounded-xl transition">Keluar</button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistik SKS Dosen -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Target SKS Anda (FRK)</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalTargetSks ?? 0 }} <span class="text-sm font-normal text-slate-500">SKS</span></h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Total Realisasi SKS Anda (FED)</p>
                <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $totalRealisasiSks ?? 0 }} <span class="text-sm font-normal text-slate-500">SKS</span></h3>
            </div>
        </div>

        <!-- Aksi & Tombol Cetak / Tambah -->
        <div class="flex justify-between items-center bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Kegiatan Tridharma Anda</h2>
                <p class="text-xs text-slate-400 mt-0.5">Semester Genap 2025/2026</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-1.5">
                    <span>🖨️</span> <span>Cetak / PDF</span>
                </button>
                <a href="/rencana/create" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm">
                    + Tambah Rencana Kerja
                </a>
            </div>
        </div>

        <!-- Tabel Daftar Kegiatan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-x-auto p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="p-3">#</th>
                        <th class="p-3">Bidang & Nama Kegiatan</th>
                        <th class="p-3">Beban SKS</th>
                        <th class="p-3">Bukti Fisik (FED)</th>
                        <th class="p-3">Status & Catatan Asesor</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($rencanaList as $index => $item)
                        <tr>
                            <td class="p-3 align-top font-medium">{{ $index + 1 }}</td>
                            <td class="p-3 align-top">
                                <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">{{ $item->jenis_rencana }} - {{ $item->sub_rencana }}</span>
                                <div class="font-semibold text-slate-900 mt-1">{{ $item->nama_kegiatan }}</div>
                            </td>
                            <td class="p-3 align-top">
                                <div class="font-medium text-slate-900">Target: {{ $item->sks_terhitung }} SKS</div>
                                @if($item->sks_realisasi !== null)
                                    <div class="text-xs text-emerald-600 font-medium mt-0.5">Realisasi: {{ $item->sks_realisasi }} SKS</div>
                                @endif
                            </td>
                            <td class="p-3 align-top">
                                @if($item->lampiran_fed)
                                    <a href="{{ $item->lampiran_fed }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline text-xs font-medium bg-blue-50 px-2.5 py-1 rounded-lg inline-block">Lihat Dokumen</a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum diunggah</span>
                                @endif
                            </td>
                            <td class="p-3 align-top space-y-1">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full inline-block
                                    {{ $item->status_frk == 'approved' ? 'bg-emerald-50 text-emerald-600' : ($item->status_frk == 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                    FRK: {{ ucfirst($item->status_frk) }}
                                </span>
                                @if($item->komentar_asesor)
                                    <div class="text-xs text-rose-600 bg-rose-50 p-2 rounded-xl border border-rose-100 mt-1">
                                        <span class="font-bold">Catatan Revisi:</span> {{ $item->komentar_asesor }}
                                    </div>
                                @endif
                            </td>
                            <!-- Kolom Aksi dengan Tombol Titik Tiga -->
                            <td class="p-3 align-top text-right relative overflow-visible">
                                <button onclick="toggleDropdown(event, {{ $item->id_rencana }})" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition focus:outline-none">
                                    <svg class="w-5 h-5 inline pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                <!-- Menu Dropdown -->
                                <div id="dropdown-{{ $item->id_rencana }}" class="hidden absolute right-6 top-12 w-44 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-left">
                                    <a href="/rencana/{{ $item->id_rencana }}/edit" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                        📝 Edit FRK
                                    </a>
                                    <a href="/rencana/{{ $item->id_rencana }}/fed" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                        ✏️ Edit FED & Bukti
                                    </a>
                                    <form action="/rencana/{{ $item->id_rencana }}/delete" method="POST" onsubmit="return confirm('Hapus rencana kegiatan ini?')">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 transition">
                                            🗑️ Hapus Kegiatan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400 italic">
                                Belum ada rencana kegiatan yang dibuat. Silakan klik tombol "+ Tambah Rencana Kerja".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script Dropdown Titik Tiga -->
    <script>
        function toggleDropdown(event, id) {
            event.stopPropagation();
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el.id !== 'dropdown-' + id) {
                    el.classList.add('hidden');
                }
            });
            const dropdown = document.getElementById('dropdown-' + id);
            dropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                el.classList.add('hidden');
            });
        });
    </script>
</body>
</html>