<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - DagangFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F8FAF7] text-slate-900">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center mb-5">
                    <x-lucide-lock-keyhole class="w-6 h-6" />
                </div>

                <h1 class="text-2xl font-bold">Lupa password?</h1>

                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Masukkan email akun DagangFlow kamu. Kami akan mengirim link untuk membuat password baru.
                </p>

                @if (session('status'))
                    <div class="mt-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-semibold">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-slate-700">Email akun</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        >
                    </div>

                    <button class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                        Kirim Link Reset
                    </button>
                </form>

                <a href="/login" class="inline-flex mt-5 text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>