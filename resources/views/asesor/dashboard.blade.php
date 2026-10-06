<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portal Asesor - Manajemen Kinerja Tridharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header Portal Asesor -->
        <div class="bg-emerald-600 text-white p-6 rounded-2xl shadow-sm flex justify-between items-center">
            <div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-white/20 rounded-full">Portal Asesor / Reviewer</span>
                <h1 class="text-2xl font-bold mt-1">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-emerald-100">Lakukan peninjauan, verifikasi, dan validasi usulan Rencana Kerja (FRK) serta Evaluasi Diri (FED) dosen.</p>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2 rounded-xl transition">Keluar</button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Kotak Peringatan (Alert Box) Validasi Alasan -->
        <div id="alert-box" class="hidden bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <span>❌</span>
                <span id="alert-text"></span>
            </div>
            <button type="button" onclick="document.getElementById('alert-box').classList.add('hidden')" class="text-rose-500 hover:text-rose-700 font-bold text-xs">Tutup</button>
        </div>

        <!-- Statistik Ringkasan Asesor -->
        @php
            $totalUsulan = isset($rencanaList) ? count($rencanaList) : 0;
            $pendingCount = isset($rencanaList) ? $rencanaList->where('status_frk', 'pending')->count() : 0;
            $approvedCount = isset($rencanaList) ? $rencanaList->where('status_frk', 'approved')->count() : 0;
            $rejectedCount = isset($rencanaList) ? $rencanaList->where('status_frk', 'rejected')->count() : 0;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Usulan Masuk</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalUsulan }} <span class="text-xs font-normal text-slate-500">Kegiatan</span></h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Menunggu Review</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingCount }} <span class="text-xs font-normal text-slate-500">Kegiatan</span></h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Disetujui (Approved)</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $approvedCount }} <span class="text-xs font-normal text-slate-500">Kegiatan</span></h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider">Perlu Revisi (Rejected)</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1">{{ $rejectedCount }} <span class="text-xs font-normal text-slate-500">Kegiatan</span></h3>
            </div>
        </div>

        <!-- Tabel Daftar Peninjauan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800">Daftar Usulan Kinerja Dosen untuk Ditinjau</h2>
                <span class="text-xs text-slate-400 italic">Periode Aktif: Semester Genap 2025/2026</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase">
                            <th class="p-3">Dosen / Pelaksana</th>
                            <th class="p-3">Kegiatan Tridharma</th>
                            <th class="p-3">Beban SKS</th>
                            <th class="p-3">Bukti Fisik (FED)</th>
                            <th class="p-3">Status Saat Ini</th>
                            <th class="p-3">Aksi & Keputusan Asesor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse($rencanaList as $item)
                            <tr>
                                <td class="p-3 align-top">
                                    <div class="font-bold text-slate-900">ID Dosen: {{ $item->id_dosen }}</div>
                                    <span class="text-xs text-slate-400">Program Studi / Unit</span>
                                </td>
                                <td class="p-3 align-top">
                                    <div class="font-semibold text-slate-900">{{ $item->nama_kegiatan }}</div>
                                    <span class="text-xs font-medium px-2 py-0.5 bg-slate-100 text-slate-600 rounded mt-1 inline-block">
                                        {{ $item->jenis_rencana }} - {{ $item->sub_rencana }}
                                    </span>
                                </td>
                                <td class="p-3 align-top">
                                    <div class="text-xs font-medium text-slate-800">Target: {{ $item->sks_terhitung }} SKS</div>
                                    @if($item->sks_realisasi !== null)
                                        <div class="text-xs text-emerald-600 font-medium mt-0.5">Realisasi: {{ $item->sks_realisasi }} SKS</div>
                                    @endif
                                </td>
                                <td class="p-3 align-top">
                                    @if($item->lampiran_fed)
                                        <a href="{{ $item->lampiran_fed }}" target="_blank" class="inline-flex items-center space-x-1 text-blue-600 hover:text-blue-800 underline text-xs font-medium bg-blue-50 px-2.5 py-1 rounded-lg">
                                            <span>🔗</span> <span>Lihat Dokumen</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic bg-slate-50 px-2 py-1 rounded">Belum diunggah</span>
                                    @endif
                                </td>
                                <td class="p-3 align-top">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full inline-block
                                        {{ $item->status_frk == 'approved' ? 'bg-emerald-50 text-emerald-600' : ($item->status_frk == 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                        FRK: {{ ucfirst($item->status_frk) }}
                                    </span>
                                </td>
                                <td class="p-3 align-top">
                                    <form action="/rencana/{{ $item->id_rencana }}/status" method="POST" class="space-y-2 max-w-xs" onsubmit="return cekAlasan(this)">
                                        @csrf
                                        <select name="status_frk" class="text-xs rounded-xl border-slate-300 border px-3 py-2 bg-white w-full font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                            <option value="pending" {{ $item->status_frk == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="approved" {{ $item->status_frk == 'approved' ? 'selected' : '' }}>✅ Approved (Setuju)</option>
                                            <option value="rejected" {{ $item->status_frk == 'rejected' ? 'selected' : '' }}>❌ Rejected (Revisi)</option>
                                        </select>
                                        
                                        <input type="text" name="komentar_asesor" value="{{ $item->komentar_asesor }}" placeholder="Tulis catatan jika ditolak..." class="text-xs rounded-xl border-slate-300 border px-3 py-2 w-full focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                        
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium py-2 px-3 rounded-xl w-full transition shadow-sm">
                                            Simpan Penilaian
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-slate-400 italic">
                                    Belum ada usulan kinerja dari dosen yang perlu ditinjau.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script Validasi Penolakan Asesor -->
    <script>
        function cekAlasan(form) {
            const status = form.querySelector('select[name="status_frk"]').value;
            const komentarInput = form.querySelector('input[name="komentar_asesor"]');
            const komentar = komentarInput.value.trim();
            
            const alertBox = document.getElementById('alert-box');
            const alertText = document.getElementById('alert-text');

            if (status === 'rejected' && komentar === '') {
                // Tampilkan pesan error di dalam kotak alert modern
                alertText.textContent = 'Asesor wajib memberikan alasan atau catatan revisi apabila menolak rencana kegiatan dosen!';
                alertBox.classList.remove('hidden');
                
                // Beri efek border merah pada input komentar dan fokuskan
                komentarInput.classList.add('border-rose-500', 'ring-1', 'ring-rose-500');
                komentarInput.focus();
                
                // Scroll ke atas agar alert terlihat
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                return false; // Membatalkan pengiriman form
            }
            
            return true;
        }
    </script>
</body>
</html>