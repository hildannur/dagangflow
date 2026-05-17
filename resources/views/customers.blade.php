@extends('layouts.dashboard', [
    'title' => 'Customer - DagangFlow',
    'pageTitle' => 'Customer',
    'subtitle' => 'Kelola data pembeli, asal channel, dan riwayat transaksi'
])

@section('actions')
    <button class="hidden sm:block px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium hover:bg-slate-50">
        Export Customer
    </button>

    <button onclick="document.getElementById('quick-add-customer').scrollIntoView({ behavior: 'smooth' })"
        class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
        + Tambah Customer
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

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Total Customer</p>
                <h3 class="text-3xl font-bold mt-3">{{ $totalCustomers }}</h3>
                <p class="text-sm text-slate-500 mt-2">Customer tercatat</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Repeat Customer</p>
                <h3 class="text-3xl font-bold mt-3 text-emerald-600">{{ $repeatCustomers }}</h3>
                <p class="text-sm text-slate-500 mt-2">Pernah beli lebih dari sekali</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Customer Baru</p>
                <h3 class="text-3xl font-bold mt-3">{{ $newCustomers }}</h3>
                <p class="text-sm text-slate-500 mt-2">Bulan ini</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-sm text-slate-500">Channel Terbanyak</p>
                <h3 class="text-3xl font-bold mt-3 text-emerald-600">
                    {{ $topChannel ?? '-' }}
                </h3>
                <p class="text-sm text-slate-500 mt-2">Sumber customer dominan</p>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Customer Table -->
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold">Daftar Customer</h3>
                        <p class="text-sm text-slate-500">Data customer tersimpan di database</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="text-left px-6 py-4 font-medium">Customer</th>
                                <th class="text-left px-6 py-4 font-medium">Kontak</th>
                                <th class="text-left px-6 py-4 font-medium">Channel</th>
                                <th class="text-left px-6 py-4 font-medium">Total Order</th>
                                <th class="text-left px-6 py-4 font-medium">Total Belanja</th>
                                <th class="text-left px-6 py-4 font-medium">Terakhir Beli</th>
                                <th class="text-right px-6 py-4 font-medium">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>

                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $customer->name }}</p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $customer->note ?: 'Tidak ada catatan' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $customer->phone ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($customer->channel === 'WhatsApp')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">WhatsApp</span>
                                        @elseif($customer->channel === 'Offline')
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Offline</span>
                                        @elseif($customer->channel === 'Shopee')
                                            <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">Shopee</span>
                                        @elseif($customer->channel === 'GoFood')
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">GoFood</span>
                                        @elseif($customer->channel === 'Instagram')
                                            <span class="px-3 py-1 rounded-full bg-pink-100 text-pink-700 text-xs font-semibold">Instagram</span>
                                        @elseif($customer->channel)
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">{{ $customer->channel }}</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 font-semibold">
                                        {{ $customer->total_orders }}x
                                    </td>

                                    <td class="px-6 py-4 font-bold text-emerald-600">
                                        Rp{{ number_format($customer->total_spent, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $customer->last_order_date ? \Carbon\Carbon::parse($customer->last_order_date)->format('d M Y') : '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <form action="/customers/{{ $customer->id }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus customer ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-14 text-center">
                                        <div class="max-w-sm mx-auto">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-2xl">
                                                👥
                                            </div>
                                            <h3 class="font-bold text-slate-900 mt-4">Belum ada customer</h3>
                                            <p class="text-sm text-slate-500 mt-2">
                                                Tambahkan data customer pertama melalui form di sebelah kanan.
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

                <!-- Quick Add Customer -->
                <div id="quick-add-customer" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Tambah Customer</h3>
                    <p class="text-sm text-slate-500 mt-1">Customer akan tersimpan ke database</p>

                    <form action="/customers" method="POST" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label class="text-sm font-medium text-slate-700">Nama customer</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Bu Sari"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Nomor WhatsApp</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="0812xxxx"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Asal channel</label>
                            <select
                                name="channel"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                                <option value="">Pilih channel</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Offline">Offline</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Shopee">Shopee</option>
                                <option value="Tokopedia">Tokopedia</option>
                                <option value="GoFood">GoFood</option>
                                <option value="GrabFood">GrabFood</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Total order</label>
                                <input
                                    type="number"
                                    name="total_orders"
                                    value="{{ old('total_orders', 0) }}"
                                    min="0"
                                    placeholder="0"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                >
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700">Total belanja</label>
                                <input
                                    type="number"
                                    name="total_spent"
                                    value="{{ old('total_spent', 0) }}"
                                    min="0"
                                    placeholder="0"
                                    class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Terakhir beli</label>
                            <input
                                type="date"
                                name="last_order_date"
                                value="{{ old('last_order_date') }}"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                            >
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Catatan</label>
                            <textarea
                                name="note"
                                rows="3"
                                placeholder="Contoh: Sering beli hampers pagi hari"
                                class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 resize-none"
                            >{{ old('note') }}</textarea>
                        </div>

                        <button class="w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-semibold hover:bg-emerald-600">
                            Simpan Customer
                        </button>
                    </form>
                </div>

                <!-- Channel Source -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold">Asal Customer</h3>
                    <p class="text-sm text-slate-500 mb-5">Distribusi customer berdasarkan channel</p>

                    @php
                        $channelSummary = $customers
                            ->filter(fn ($customer) => !empty($customer->channel))
                            ->groupBy('channel')
                            ->map(fn ($items) => $items->count())
                            ->sortDesc();

                        $totalChannelCustomers = $channelSummary->sum();
                    @endphp

                    <div class="space-y-5">
                        @forelse($channelSummary as $channel => $count)
                            @php
                                $percent = $totalChannelCustomers > 0 ? round(($count / $totalChannelCustomers) * 100) : 0;
                            @endphp

                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span>{{ $channel }}</span>
                                    <span class="font-semibold">{{ $count }} customer</span>
                                </div>

                                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>

                                <p class="text-xs text-slate-500 mt-1">{{ $percent }}% dari data customer</p>
                            </div>
                        @empty
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="font-semibold text-slate-700">Belum ada data channel</p>
                                <p class="text-sm text-slate-500 mt-1">Data akan muncul setelah customer ditambahkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Customer Insight -->
                <div class="rounded-2xl p-6 bg-[#0F172A] text-white shadow-sm">
                    <p class="text-sm text-emerald-300 font-semibold">Insight</p>

                    @if($topChannel)
                        <h3 class="text-xl font-bold mt-2">
                            {{ $topChannel }} paling kuat untuk customer.
                        </h3>

                        <p class="text-sm text-slate-300 mt-3 leading-relaxed">
                            Customer paling banyak datang dari {{ $topChannel }}. Channel ini bisa diprioritaskan untuk follow-up, promo, atau repeat order.
                        </p>
                    @else
                        <h3 class="text-xl font-bold mt-2">
                            Belum ada insight customer.
                        </h3>

                        <p class="text-sm text-slate-300 mt-3 leading-relaxed">
                            Tambahkan data customer agar DagangFlow bisa membaca channel customer yang paling dominan.
                        </p>
                    @endif
                </div>

            </div>
        </div>

    </div>
@endsection