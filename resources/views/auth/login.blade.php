<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FRK & FED IT Del</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <div class="text-center">
            <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full">Institut Teknologi Del</span>
            <h1 class="text-2xl font-bold text-slate-800 mt-2">Masuk Sistem</h1>
            <p class="text-sm text-slate-500 mt-1">Form Rencana Kerja & Evaluasi Diri</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="dosen@del.ac.id" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full rounded-xl border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl transition shadow-sm text-sm">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>