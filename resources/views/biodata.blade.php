@extends('layouts.dashboard', [
    'title' => 'Biodata - DagangFlow',
    'pageTitle' => 'Biodata Akun',
    'subtitle' => 'Kelola data akun, informasi bisnis, email, dan password'
])

@section('actions')
    <a href="/dashboard" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Kembali ke Dashboard
    </a>
@endsection

@section('content')
    <div class="space-y-8">

        <div class="bg-[#0F172A] rounded-2xl p-6 text-white shadow-sm overflow-hidden relative">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="absolute right-24 bottom-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 text-sm font-semibold">
                        <x-lucide-user-round class="w-4 h-4" />
                        Pengaturan Akun
                    </div>

                    <h3 class="text-3xl font-bold mt-4">
                        Kelola biodata dan identitas bisnis
                    </h3>

                    <p class="text-sm text-slate-300 mt-3 leading-relaxed max-w-3xl">
                        Perbarui nama owner, email login, nama bisnis, kategori bisnis, dan password akun DagangFlow.
                    </p>
                </div>

                <div class="bg-white/10 border border-white/10 rounded-2xl p-5 min-w-[240px]">
                    <p class="text-sm text-emerald-300 font-semibold">Akun aktif</p>
                    <p class="font-bold mt-2 truncate">{{ auth()->user()->name ?? 'Owner' }}</p>
                    <p class="text-sm text-slate-300 mt-1 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        @if (session('profile_success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ session('profile_success') }}
            </div>
        @endif

        @if (session('password_success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ session('password_success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <x-lucide-id-card class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-lg font-bold">Informasi Akun & Bisnis</h3>
                        <p class="text-sm text-slate-500 mt-1">Data ini akan ditampilkan di dashboard dan profil akun.</p>
                    </div>
                </div>

                <form action="{{ route('biodata.profile.update') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Nama owner</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Email login</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Nama bisnis</label>
                            <input
                                type="text"
                                name="business_name"
                                value="{{ old('business_name', auth()->user()->business_name) }}"
                                placeholder="Contoh: Kopi Rumah Senja"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Kategori bisnis</label>
                            <select
                                name="business_type"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                                @php
                                    $businessType = old('business_type', auth()->user()->business_type);
                                @endphp

                                <option value="">Pilih kategori bisnis</option>
                                <option value="Kuliner / F&B" @selected($businessType === 'Kuliner / F&B')>Kuliner / F&B</option>
                                <option value="Fashion" @selected($businessType === 'Fashion')>Fashion</option>
                                <option value="Online Shop" @selected($businessType === 'Online Shop')>Online Shop</option>
                                <option value="Retail / Toko" @selected($businessType === 'Retail / Toko')>Retail / Toko</option>
                                <option value="Jasa" @selected($businessType === 'Jasa')>Jasa</option>
                                <option value="Reseller / Distributor" @selected($businessType === 'Reseller / Distributor')>Reseller / Distributor</option>
                                <option value="Lainnya" @selected($businessType === 'Lainnya')>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-sm font-semibold text-slate-700">Catatan</p>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Email digunakan untuk login. Pastikan email yang digunakan aktif dan benar.
                        </p>
                    </div>

                    <button class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                        <x-lucide-save class="w-4 h-4" />
                        Simpan Biodata
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center">
                        <x-lucide-lock-keyhole class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-lg font-bold">Ubah Password</h3>
                        <p class="text-sm text-slate-500 mt-1">Gunakan password kuat dan mudah diingat.</p>
                    </div>
                </div>

                <form action="{{ route('biodata.password.update') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-medium text-slate-700">Password saat ini</label>
                        <input
                            type="password"
                            name="current_password"
                            class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        >
                        <a href="{{ route('password.request') }}" class="inline-flex mt-2 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                            Lupa password saat ini? Reset lewat email
                        </a>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Password baru</label>
                        <input
                            type="password"
                            name="password"
                            class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        >
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Konfirmasi password baru</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        >
                    </div>

                    <button class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                        <x-lucide-key-round class="w-4 h-4" />
                        Ubah Password
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection