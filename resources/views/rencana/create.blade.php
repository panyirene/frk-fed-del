<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Rencana Kerja (FRK)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 flex items-center justify-center">
    <div class="max-w-xl w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <h1 class="text-xl font-bold text-slate-800">Form Rencana Kerja (FRK)</h1>

        <form action="/rencana" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Bidang Tridharma</label>
                <select name="jenis_rencana" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm bg-white" required>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Penelitian">Penelitian</option>
                    <option value="Pengabdian">Pengabdian</option>
                    <option value="Penunjang">Penunjang</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sub Kategori</label>
                <input type="text" name="sub_rencana" placeholder="Contoh: Perkuliahan" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Kegiatan</label>
                <textarea name="nama_kegiatan" rows="3" placeholder="Detail kegiatan..." class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Target SKS (Angka Bulat)</label>
                <input type="number" step="1" name="sks_terhitung" placeholder="Contoh: 3" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm" required>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="/rencana" class="px-4 py-2 text-sm font-medium text-slate-600">Batal</a>
                <button type="submit" class="bg-blue-600 text-white text-sm font-medium px-5 py-2 rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>