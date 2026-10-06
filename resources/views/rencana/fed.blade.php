<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Evaluasi Diri (FED)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 flex items-center justify-center">
    <div class="max-w-xl w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <h1 class="text-xl font-bold text-slate-800">Form Evaluasi Diri (FED)</h1>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-sm space-y-1">
            <div class="text-xs font-semibold text-blue-600 uppercase">{{ $rencana->jenis_rencana }}</div>
            <div class="font-bold text-slate-900">{{ $rencana->nama_kegiatan }}</div>
            <div class="text-slate-600 text-xs">Target SKS: {{ $rencana->sks_terhitung }} SKS</div>
        </div>

        <form action="/rencana/{{ $rencana->id_rencana }}/fed" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Realisasi SKS (Angka Bulat)</label>
                <input type="number" step="1" name="sks_realisasi" value="{{ $rencana->sks_realisasi ?? $rencana->sks_terhitung }}" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tautan Bukti / URL Dokumen (Opsional)</label>
                <input type="url" name="lampiran_fed" value="{{ $rencana->lampiran_fed }}" placeholder="https://drive.google.com/..." class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="/rencana" class="px-4 py-2 text-sm font-medium text-slate-600">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white text-sm font-medium px-5 py-2 rounded-xl">Simpan FED</button>
            </div>
        </form>
    </div>
</body>
</html>