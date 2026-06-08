@extends('layouts.dashboard', [
    'title' => 'Laporkan Kendala - DagangFlow',
    'pageTitle' => 'Laporkan Kendala',
    'subtitle' => 'Ceritakan kendala yang kamu alami agar tim DagangFlow bisa membantu mengeceknya'
])

@section('actions')
    <a href="/help" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Kembali ke Bantuan
    </a>
@endsection

@section('content')
    @php
        $user = auth()->user();
        $premiumPlans = ['Trial', 'Bulanan', 'Tahunan'];
        $isPremiumUser = in_array($user->plan_name, $premiumPlans);
        
        // Tentukan label prioritas
        $priorityLabel = $isPremiumUser ? 'Tinggi' : 'Normal';
        $planInfo = $isPremiumUser ? 'Paket premium kamu memiliki prioritas handling yang lebih tinggi.' : 'Paket free kamu memiliki prioritas normal. Upgrade ke paket premium untuk prioritas lebih tinggi.';
    @endphp

    <div class="space-y-8">

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Form -->
            <div class="xl:col-span-2 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <x-lucide-message-circle-warning class="w-6 h-6" />
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-slate-900">
                            Detail Kendala
                        </h3>

                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Isi laporan dengan jelas agar tim DagangFlow bisa memahami kendala yang terjadi.
                        </p>
                    </div>
                </div>

                <form action="{{ route('owner.support.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="text-sm font-bold text-slate-700">Subjek Kendala</label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            placeholder="Contoh: Export laporan tidak berhasil"
                            class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        >
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-bold text-slate-700">Kategori</label>

                            <select
                                name="category"
                                class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                                <option value="">Pilih kategori</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category }}" @selected(old('category') === $category)>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-bold text-slate-700">Prioritas</label>

                            <div class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 flex items-center justify-between">
                                <span>{{ $priorityLabel }}</span>
                                <span class="text-xs text-slate-500 ml-2">
                                    @if($isPremiumUser)
                                        <x-lucide-lock class="w-4 h-4 inline" />
                                    @endif
                                </span>
                            </div>
                            
                            <p class="text-xs text-slate-500 mt-2">
                                {{ $planInfo }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-700">Ceritakan Kendalanya</label>

                        <textarea
                            name="message"
                            rows="7"
                            placeholder="Jelaskan kendala yang kamu alami, kapan terjadi, dan halaman/fitur apa yang bermasalah."
                            class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            required
                        >{{ old('message') }}</textarea>

                        <p class="text-xs text-slate-500 mt-2">
                            Semakin jelas laporanmu, semakin mudah tim DagangFlow membantu mengeceknya.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-600 transition">
                            <x-lucide-send class="w-4 h-4" />
                            Kirim Laporan
                        </button>

                        <a href="/help" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-bold hover:bg-slate-50 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Info -->
            <div class="space-y-6">
                <div class="bg-[#0F172A] rounded-3xl p-6 text-white shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center">
                        <x-lucide-life-buoy class="w-6 h-6" />
                    </div>

                    <h3 class="text-xl font-black mt-5">
                        Apa yang perlu dilaporkan?
                    </h3>

                    <p class="text-sm text-slate-300 mt-3 leading-relaxed">
                        Laporkan kendala seperti data tidak muncul, export gagal, subscription bermasalah, atau fitur aplikasi tidak berjalan normal.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-black text-slate-900">
                        Tips laporan jelas
                    </h3>

                    <div class="space-y-4 mt-5">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-lucide-check class="w-4 h-4" />
                            </div>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                Sebutkan halaman atau fitur yang bermasalah.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-lucide-check class="w-4 h-4" />
                            </div>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                Jelaskan langkah yang kamu lakukan sebelum error muncul.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-lucide-check class="w-4 h-4" />
                            </div>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                Tambahkan detail waktu kejadian jika memungkinkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
