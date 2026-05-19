@extends('layouts.dashboard', [
    'title' => 'Pengeluaran - DagangFlow',
    'pageTitle' => 'Pengeluaran',
    'subtitle' => 'Catat biaya usaha seperti bahan baku, packaging, iklan, dan operasional'
])

@section('actions')
    <button class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Export Pengeluaran
    </button>

    <button onclick="document.getElementById('quick-add-expense').scrollIntoView({ behavior: 'smooth' })"
        class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        + Tambah Pengeluaran
    </button>
@endsection

@section('content')
    <div class="space-y-8">

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif
        
        <!-- Period Filter -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-5">
                <div>
                    <h3 class="text-lg font-bold">Filter Pengeluaran</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Data ditampilkan dari
                        {{ \Carbon\Carbon::parse($selectedPeriod['start_date'])->format('d M Y') }}
                        sampai
                        {{ \Carbon\Carbon::parse($selectedPeriod['end_date'])->format('d M Y') }}
                    </p>
                </div>
        
                <form action="/expenses" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-500">Tanggal mulai</label>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ $selectedPeriod['start_date'] }}"
                            class="mt-2 px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>
        
                    <div>
                        <label class="text-xs font-semibold text-slate-500">Tanggal akhir</label>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ $selectedPeriod['end_date'] }}"
                            class="mt-2 px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>
        
                    <button class="px-5 py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                        Terapkan Filter
                    </button>
        
                    <a href="/expenses" class="px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold hover:bg-slate-50 text-center">
                        Reset
                    </a>
                </form>
            </div>
        
            <div class="flex flex-wrap items-center gap-3 mt-5">
                <a href="/expenses?start_date={{ now()->toDateString() }}&end_date={{ now()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    Hari Ini
                </a>
        
                <a href="/expenses?start_date={{ now()->subDays(6)->toDateString() }}&end_date={{ now()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    7 Hari
                </a>
        
                <a href="/expenses?start_date={{ now()->startOfMonth()->toDateString() }}&end_date={{ now()->endOfMonth()->toDateString() }}"
                    class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm font-semibold hover:bg-emerald-100">
                    Bulan Ini
                </a>
        
                <a href="/expenses?start_date={{ now()->subMonth()->startOfMonth()->toDateString() }}&end_date={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
                    Bulan Lalu
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Pengeluaran Periode Ini</p>
                <h3 class="text-3xl font-bold mt-3 text-red-600">
                    Rp{{ number_format($monthExpenses, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">
                    Dari {{ $expenses->count() }} catatan biaya
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Biaya Bahan Baku</p>
                <h3 class="text-3xl font-bold mt-3">
                    Rp{{ number_format($rawMaterialExpenses, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Total kategori bahan baku</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Biaya Platform</p>
                <h3 class="text-3xl font-bold mt-3 text-amber-600">
                    Rp{{ number_format($platformExpenses, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Marketplace & delivery</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Rata-rata Harian</p>
                <h3 class="text-3xl font-bold mt-3">
                    Rp{{ number_format($dailyAverage, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Rata-rata periode ini</p>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Expenses Table -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold">Daftar Pengeluaran</h3>
                        <p class="text-sm text-slate-500">Data pengeluaran tersimpan di database</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="text-left px-6 py-4 font-medium">Tanggal</th>
                                <th class="text-left px-6 py-4 font-medium">Kategori</th>
                                <th class="text-left px-6 py-4 font-medium">Catatan</th>
                                <th class="text-left px-6 py-4 font-medium">Nominal</th>
                                <th class="text-left px-6 py-4 font-medium">Metode</th>
                                <th class="text-right px-6 py-4 font-medium">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') : $expense->created_at->format('d M Y') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($expense->category === 'Bahan Baku')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Bahan Baku</span>
                                        @elseif($expense->category === 'Packaging')
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Packaging</span>
                                        @elseif($expense->category === 'Iklan')
                                            <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">Iklan</span>
                                        @elseif($expense->category === 'Platform')
                                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">Platform</span>
                                        @elseif($expense->category === 'Operasional')
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Operasional</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">{{ $expense->category }}</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-900">
                                            {{ $expense->note ?: '-' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Dicatat manual
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 font-bold text-red-600">
                                        Rp{{ number_format($expense->amount, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $expense->payment_method ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <button
                                                type="button"
                                                onclick="openEditExpenseModal(
                                                    '{{ $expense->id }}',
                                                    '{{ $expense->category }}',
                                                    '{{ $expense->amount }}',
                                                    '{{ $expense->payment_method ?? '' }}',
                                                    '{{ $expense->expense_date }}',
                                                    `{{ addslashes($expense->note ?? '') }}`
                                                )"
                                                class="text-sm font-semibold text-emerald-600 hover:text-emerald-700"
                                            >
                                                Edit
                                            </button>
                                    
                                            <form action="/expenses/{{ $expense->id }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                    
                                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="max-w-sm mx-auto">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-2xl">
                                                💸
                                            </div>
                                            <h3 class="font-bold text-slate-900 mt-4">Belum ada pengeluaran</h3>
                                            <p class="text-sm text-slate-500 mt-2">
                                                Tambahkan catatan pengeluaran pertama melalui form di sebelah kanan.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="space-y-6">

                <!-- Quick Add Expense -->
                <div id="quick-add-expense" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Tambah Pengeluaran</h3>
                    <p class="text-sm text-slate-500 mt-1">Pengeluaran akan tersimpan ke database</p>

                    <form action="/expenses" method="POST" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label class="text-sm font-medium text-slate-700">Kategori</label>
                            <select
                                name="category"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                                <option value="Bahan Baku">Bahan Baku</option>
                                <option value="Packaging">Packaging</option>
                                <option value="Iklan">Iklan</option>
                                <option value="Platform">Platform</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Transport">Transport</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Nominal</label>
                            <input
                                type="text"
                                inputmode="numeric"
                                name="amount"
                                value="{{ old('amount') }}"
                                placeholder="Contoh: 150.000"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Metode pembayaran</label>
                            <select
                                name="payment_method"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                                <option value="">Pilih metode</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="Auto">Auto</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Tanggal pengeluaran</label>
                            <input
                                type="date"
                                name="expense_date"
                                value="{{ now()->toDateString() }}"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Catatan</label>
                            <textarea
                                name="note"
                                rows="3"
                                placeholder="Contoh: Beli bahan baku untuk stok minggu ini"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 resize-none"
                            >{{ old('note') }}</textarea>
                        </div>

                        <button class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                            Simpan Pengeluaran
                        </button>
                    </form>
                </div>

                <!-- Category Breakdown -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Breakdown Kategori</h3>
                    <p class="text-sm text-slate-500 mb-5">Pengeluaran terbesar bulan ini</p>

                    @php
                        $categoryBreakdown = $expenses
                            ->filter(fn ($expense) => $expense->created_at->isSameMonth(now()))
                            ->groupBy('category')
                            ->map(fn ($items) => $items->sum('amount'))
                            ->sortDesc();

                        $totalBreakdown = $categoryBreakdown->sum();
                    @endphp

                    <div class="space-y-5">
                        @forelse($categoryBreakdown as $category => $amount)
                            @php
                                $percent = $totalBreakdown > 0 ? round(($amount / $totalBreakdown) * 100) : 0;
                            @endphp

                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span>{{ $category }}</span>
                                    <span class="font-semibold">Rp{{ number_format($amount, 0, ',', '.') }}</span>
                                </div>

                                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-400 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>

                                <p class="text-xs text-slate-500 mt-1">{{ $percent }}% dari pengeluaran</p>
                            </div>
                        @empty
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="font-semibold text-slate-700">Belum ada data kategori</p>
                                <p class="text-sm text-slate-500 mt-1">Breakdown muncul setelah ada pengeluaran.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Insight -->
                <div class="rounded-2xl p-6 bg-[#0F172A] text-white shadow-sm">
                    <p class="text-sm text-emerald-300 font-semibold">Insight</p>

                    @if($categoryBreakdown->count() > 0)
                        @php
                            $topCategory = $categoryBreakdown->keys()->first();
                            $topAmount = $categoryBreakdown->first();
                        @endphp

                        <h3 class="text-xl font-bold mt-2">
                            {{ $topCategory }} paling besar bulan ini.
                        </h3>

                        <p class="text-sm text-slate-300 mt-3 leading-relaxed">
                            Pengeluaran terbesar kamu ada di kategori {{ $topCategory }} dengan total Rp{{ number_format($topAmount, 0, ',', '.') }}.
                            Cek apakah biaya ini masih seimbang dengan omzet.
                        </p>
                    @else
                        <h3 class="text-xl font-bold mt-2">
                            Belum ada insight pengeluaran.
                        </h3>

                        <p class="text-sm text-slate-300 mt-3 leading-relaxed">
                            Mulai catat pengeluaran agar DagangFlow bisa membantu membaca kategori biaya terbesar.
                        </p>
                    @endif
                </div>

            </div>
        </div>
        
        <!-- Edit Expense Modal -->
        <div id="editExpenseModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 px-4 py-6 overflow-y-auto">
            <div class="min-h-full flex items-start justify-center">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl my-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Edit Pengeluaran</h3>
                            <p class="text-sm text-slate-500">Perbarui data pengeluaran</p>
                        </div>
        
                        <button type="button" onclick="closeEditExpenseModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200">
                            ✕
                        </button>
                    </div>
        
                    <form id="editExpenseForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
        
                        <div>
                            <label class="text-sm font-medium text-slate-700">Kategori</label>
                            <select
                                name="category"
                                id="edit_category"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                                <option value="Bahan Baku">Bahan Baku</option>
                                <option value="Packaging">Packaging</option>
                                <option value="Iklan">Iklan</option>
                                <option value="Platform">Platform</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Transport">Transport</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
        
                        <div>
                            <label class="text-sm font-medium text-slate-700">Nominal</label>
                            <input
                                type="text"
                                inputmode="numeric"
                                name="amount"
                                id="edit_amount"
                                placeholder="Contoh: 150.000"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rupiah-input"
                                required
                            >
                        </div>
        
                        <div>
                            <label class="text-sm font-medium text-slate-700">Metode pembayaran</label>
                            <select
                                name="payment_method"
                                id="edit_payment_method"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                                <option value="">Pilih metode</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="Auto">Auto</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
        
                        <div>
                            <label class="text-sm font-medium text-slate-700">Tanggal pengeluaran</label>
                            <input
                                type="date"
                                name="expense_date"
                                id="edit_expense_date"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>
        
                        <div>
                            <label class="text-sm font-medium text-slate-700">Catatan</label>
                            <textarea
                                name="note"
                                id="edit_note"
                                rows="3"
                                placeholder="Contoh: Beli bahan baku untuk stok minggu ini"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 resize-none"
                            ></textarea>
                        </div>
        
                        <button type="submit" class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
    function formatRibuanExpense(value) {
        const number = String(value || '').replace(/\D/g, '');

        if (!number) {
            return '';
        }

        return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function openEditExpenseModal(id, category, amount, paymentMethod, expenseDate, note) {
        const modal = document.getElementById('editExpenseModal');
        const form = document.getElementById('editExpenseForm');

        form.action = `/expenses/${id}`;

        document.getElementById('edit_category').value = category;
        document.getElementById('edit_amount').value = formatRibuanExpense(amount);
        document.getElementById('edit_payment_method').value = paymentMethod || '';
        document.getElementById('edit_expense_date').value = expenseDate || '';
        document.getElementById('edit_note').value = note || '';

        modal.classList.remove('hidden');
        modal.classList.add('block');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditExpenseModal() {
        const modal = document.getElementById('editExpenseModal');

        modal.classList.add('hidden');
        modal.classList.remove('block');
        document.body.classList.remove('overflow-hidden');
    }
    </script>
@endsection