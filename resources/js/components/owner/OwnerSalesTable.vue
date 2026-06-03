<template>
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-black">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Live Sales Data
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 mt-4">
                        Penjualan
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed max-w-2xl">
                        Catat transaksi penjualan, channel, jumlah produk, biaya platform, omzet, dan uang bersih secara realtime.
                    </p>
                </div>

                <button
                    type="button"
                    @click="openCreateModal"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-500 text-white text-sm font-black hover:bg-emerald-600 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Tambah Penjualan
                </button>
            </div>
        </div>

        <!-- Filter -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/60">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Cari Transaksi
                    </label>

                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>

                        <input
                            v-model="filters.search"
                            type="text"
                            placeholder="Cari produk, channel, status, atau catatan..."
                            class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Channel
                    </label>

                    <select
                        v-model="filters.channel"
                        class="mt-2 w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                        <option value="">Semua channel</option>
                        <option v-for="channel in channels" :key="channel" :value="channel">
                            {{ channel }}
                        </option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Urutkan
                    </label>

                    <select
                        v-model="filters.sort"
                        class="mt-2 w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                        <option value="latest">Terbaru</option>
                        <option value="gross_desc">Omzet tertinggi</option>
                        <option value="net_desc">Bersih tertinggi</option>
                        <option value="qty_desc">Jumlah Terjual</option>
                    </select>
                </div>

                <div class="md:col-span-1 flex md:items-end">
                    <button
                        type="button"
                        @click="resetFilters"
                        class="w-full h-[46px] mt-6 md:mt-0 inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition"
                        title="Reset filter"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                            <path d="M3 3v5h5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Meta -->
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-sm text-slate-500">
                Menampilkan
                <span class="font-black text-slate-900">{{ meta.from || 0 }}</span>
                -
                <span class="font-black text-slate-900">{{ meta.to || 0 }}</span>
                dari
                <span class="font-black text-slate-900">{{ meta.total || 0 }}</span>
                transaksi
            </p>

            <div class="flex items-center gap-2">
                <span
                    v-if="hasActiveFilter"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-black"
                >
                    Filter aktif
                </span>

                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                    Realtime filter
                </span>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Produk</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Channel</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Jumlah Terjual</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Omzet</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Fee</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Bersih</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Tanggal</th>
                        <th class="text-right px-6 py-4 font-black text-xs uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr v-if="loading">
                        <td colspan="8" class="px-6 py-14 text-center">
                            <div class="inline-flex items-center gap-3 text-slate-500 font-semibold">
                                <span class="w-5 h-5 rounded-full border-2 border-slate-300 border-t-emerald-500 animate-spin"></span>
                                Memuat data penjualan...
                            </div>
                        </td>
                    </tr>

                    <tr v-else-if="sales.length === 0">
                        <td colspan="8" class="px-6 py-14 text-center">
                            <h3 class="text-lg font-black text-slate-900">
                                Transaksi tidak ditemukan
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Coba ubah kata kunci atau reset filter.
                            </p>
                        </td>
                    </tr>

                    <tr v-for="sale in sales" :key="sale.id" class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black shrink-0">
                                    {{ getInitial(sale.product_name) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 truncate">
                                        {{ sale.product_name || 'Produk terhapus' }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ sale.status || 'Selesai' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <span :class="['px-3 py-1.5 rounded-full text-xs font-black', channelClass(sale.channel)]">
                                {{ sale.channel || '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <p class="font-black text-slate-900">{{ sale.quantity }}</p>
                            <p class="text-xs text-slate-500 mt-1">pcs</p>
                        </td>

                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="font-black text-slate-900">{{ sale.gross_total }}</p>
                        </td>

                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="font-black text-red-600">{{ sale.platform_fee }}</p>
                        </td>

                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="font-black text-emerald-600">{{ sale.net_total }}</p>
                        </td>

                        <td class="px-6 py-5 whitespace-nowrap text-slate-600">
                            {{ sale.sale_date || '-' }}
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    type="button"
                                    @click="openEditModal(sale)"
                                    class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center justify-center"
                                    title="Edit penjualan"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    @click="deleteSale(sale)"
                                    class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition flex items-center justify-center"
                                    title="Hapus penjualan"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4h8v2" />
                                        <path d="M19 6l-1 14H6L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden divide-y divide-slate-100">
            <div v-if="loading" class="p-8 text-center text-slate-500 font-semibold">
                Memuat data penjualan...
            </div>

            <div v-else-if="sales.length === 0" class="p-8 text-center">
                <h3 class="font-black text-slate-900">Transaksi tidak ditemukan</h3>
                <p class="text-sm text-slate-500 mt-1">Coba ubah kata kunci atau reset filter.</p>
            </div>

            <div v-for="sale in sales" v-else :key="sale.id" class="p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black shrink-0">
                        {{ getInitial(sale.product_name) }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="font-black text-slate-900">
                            {{ sale.product_name || 'Produk terhapus' }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ sale.channel || '-' }} • {{ sale.sale_date || '-' }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-3">
                            <span :class="['px-3 py-1.5 rounded-full text-xs font-black', channelClass(sale.channel)]">
                                {{ sale.channel || '-' }}
                            </span>

                            <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-black">
                                Jumlah Terjual {{ sale.quantity }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="button"
                            @click="openEditModal(sale)"
                            class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center justify-center"
                        >
                            ✎
                        </button>

                        <button
                            type="button"
                            @click="deleteSale(sale)"
                            class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition flex items-center justify-center"
                        >
                            ×
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mt-4">
                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs text-slate-500">Omzet</p>
                        <p class="text-sm font-bold text-slate-900 mt-1">{{ sale.gross_total }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs text-slate-500">Fee</p>
                        <p class="text-sm font-bold text-red-600 mt-1">{{ sale.platform_fee }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs text-slate-500">Bersih</p>
                        <p class="text-sm font-bold text-emerald-600 mt-1">{{ sale.net_total }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <button
                type="button"
                @click="goToPage(meta.current_page - 1)"
                :disabled="meta.current_page <= 1 || loading"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-200 text-sm font-bold text-slate-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 transition"
            >
                Sebelumnya
            </button>

            <p class="text-sm text-slate-500 text-center">
                Halaman <span class="font-black text-slate-900">{{ meta.current_page || 1 }}</span>
                dari <span class="font-black text-slate-900">{{ meta.last_page || 1 }}</span>
            </p>

            <button
                type="button"
                @click="goToPage(meta.current_page + 1)"
                :disabled="meta.current_page >= meta.last_page || loading"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-200 text-sm font-bold text-slate-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 transition"
            >
                Berikutnya
            </button>
        </div>

        <SaleFormModal
            v-if="showCreateModal"
            title="Tambah Penjualan"
            subtitle="Omzet dan uang bersih dihitung otomatis dari produk, qty, dan biaya platform."
            :saving="saving"
            :form="createForm"
            :products="productsForSelect"
            submit-label="Simpan Penjualan"
            @close="closeCreateModal"
            @submit="createSale"
        />

        <SaleFormModal
            v-if="showEditModal"
            title="Edit Penjualan"
            subtitle="Perubahan akan menyesuaikan stok produk otomatis."
            :saving="saving"
            :form="editForm"
            :products="productsForSelect"
            submit-label="Simpan Perubahan"
            @close="closeEditModal"
            @submit="updateSale"
        />
    </div>
</template>

<script setup>
import { computed, defineComponent, h, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue';

function formatRibuan(value) {
    const number = String(value || '').replace(/\D/g, '');
    if (!number) return '';
    return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

const channelOptions = [
    'Offline',
    'WhatsApp',
    'Instagram',
    'Shopee',
    'Tokopedia',
    'TikTok Shop',
    'GoFood',
    'GrabFood',
];

const SaleFormModal = defineComponent({
    props: {
        title: String,
        subtitle: String,
        saving: Boolean,
        form: Object,
        products: Array,
        submitLabel: String,
    },
    emits: ['close', 'submit'],
    setup(props, { emit }) {
        return () => h('div', {
            class: 'fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center px-4',
        }, [
            h('div', { class: 'bg-white rounded-3xl p-6 w-full max-w-lg shadow-xl' }, [
                h('div', { class: 'flex items-center justify-between mb-6' }, [
                    h('div', {}, [
                        h('h3', { class: 'text-xl font-black text-slate-900' }, props.title),
                        h('p', { class: 'text-sm text-slate-500 mt-1' }, props.subtitle),
                    ]),
                    h('button', {
                        type: 'button',
                        class: 'w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700',
                        onClick: () => emit('close'),
                    }, '✕'),
                ]),

                h('form', {
                    class: 'space-y-4',
                    onSubmit: (event) => {
                        event.preventDefault();
                        emit('submit');
                    },
                }, [
                    h('div', {}, [
                        h('label', { class: 'text-sm font-bold text-slate-700' }, 'Produk'),
                        h('select', {
                            value: props.form.product_id,
                            required: true,
                            class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                            onChange: (event) => props.form.product_id = event.target.value,
                        }, [
                            h('option', { value: '' }, 'Pilih produk'),
                            ...(props.products || []).map((product) => h('option', {
                                value: product.id,
                            }, `${product.name} — stok ${product.stock}`)),
                        ]),
                    ]),

                    h('div', {}, [
                        h('label', { class: 'text-sm font-bold text-slate-700' }, 'Channel'),
                        h('select', {
                            value: props.form.channel,
                            required: true,
                            class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                            onChange: (event) => props.form.channel = event.target.value,
                        }, [
                            h('option', { value: '' }, 'Pilih channel'),
                            ...channelOptions.map((channel) => h('option', {
                                value: channel,
                            }, channel)),
                        ]),
                    ]),

                    h('div', { class: 'grid grid-cols-2 gap-3' }, [
                        h('div', {}, [
                            h('label', { class: 'text-sm font-bold text-slate-700' }, 'Jumlah Terjual'),
                            h('input', {
                                value: props.form.quantity,
                                type: 'number',
                                required: true,
                                min: 1,
                                class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                                onInput: (event) => props.form.quantity = event.target.value,
                            }),
                        ]),

                        h('div', {}, [
                            h('label', { class: 'text-sm font-bold text-slate-700' }, 'Tanggal'),
                            h('input', {
                                value: props.form.sale_date,
                                type: 'date',
                                class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                                onInput: (event) => props.form.sale_date = event.target.value,
                            }),
                        ]),
                    ]),

                    h('div', {}, [
                        h('label', { class: 'text-sm font-bold text-slate-700' }, 'Biaya Platform'),
                        h('input', {
                            value: props.form.platform_fee,
                            type: 'text',
                            inputmode: 'numeric',
                            placeholder: 'Contoh: 2.000',
                            class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                            onInput: (event) => props.form.platform_fee = formatRibuan(event.target.value),
                        }),
                    ]),

                    h('div', {}, [
                        h('label', { class: 'text-sm font-bold text-slate-700' }, 'Status'),
                        h('select', {
                            value: props.form.status,
                            class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                            onChange: (event) => props.form.status = event.target.value,
                        }, [
                            h('option', { value: 'Selesai' }, 'Selesai'),
                            h('option', { value: 'Diproses' }, 'Diproses'),
                            h('option', { value: 'Belum Bayar' }, 'Belum Bayar'),
                        ]),
                    ]),

                    h('div', {}, [
                        h('label', { class: 'text-sm font-bold text-slate-700' }, 'Catatan'),
                        h('textarea', {
                            value: props.form.note,
                            rows: 3,
                            class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                            onInput: (event) => props.form.note = event.target.value,
                        }),
                    ]),

                    h('button', {
                        type: 'submit',
                        disabled: props.saving,
                        class: 'w-full py-3 rounded-xl bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-600 disabled:opacity-50 transition',
                    }, props.saving ? 'Menyimpan...' : props.submitLabel),
                ]),
            ]),
        ]);
    },
});

const props = defineProps({
    dataUrl: { type: String, required: true },
    createUrl: { type: String, required: true },
    editBaseUrl: { type: String, required: true },
    csrfToken: { type: String, required: true },
});

const sales = ref([]);
const productsForSelect = ref([]);
const channels = ref([]);
const loading = ref(false);
const saving = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const debounceTimer = ref(null);

const filters = reactive({
    search: '',
    channel: '',
    sort: 'latest',
    page: 1,
});

const meta = reactive({
    current_page: 1,
    last_page: 1,
    from: 0,
    to: 0,
    total: 0,
});

const createForm = reactive({
    product_id: '',
    channel: '',
    quantity: 1,
    platform_fee: '',
    status: 'Selesai',
    note: '',
    sale_date: new Date().toISOString().slice(0, 10),
});

const editForm = reactive({
    id: null,
    product_id: '',
    channel: '',
    quantity: 1,
    platform_fee: '',
    status: 'Selesai',
    note: '',
    sale_date: '',
});

const hasActiveFilter = computed(() => {
    return Boolean(filters.search || filters.channel || filters.sort !== 'latest');
});

const cleanNumber = (value) => String(value || '').replace(/\D/g, '');

const getInitial = (name) => {
    if (!name) return 'S';
    return name.charAt(0).toUpperCase();
};

const channelClass = (channel) => {
    const value = String(channel || '').toLowerCase();

    if (value.includes('shopee')) return 'bg-orange-50 text-orange-700';
    if (value.includes('tiktok')) return 'bg-slate-900 text-white';
    if (value.includes('gofood')) return 'bg-emerald-50 text-emerald-700';
    if (value.includes('grab')) return 'bg-green-50 text-green-700';
    if (value.includes('whatsapp')) return 'bg-emerald-50 text-emerald-700';
    if (value.includes('instagram')) return 'bg-pink-50 text-pink-700';
    if (value.includes('tokopedia')) return 'bg-green-50 text-green-700';
    if (value.includes('offline')) return 'bg-slate-100 text-slate-700';

    return 'bg-blue-50 text-blue-700';
};

const fetchSales = async () => {
    loading.value = true;

    const params = new URLSearchParams();

    if (filters.search) params.append('search', filters.search);
    if (filters.channel) params.append('channel', filters.channel);
    if (filters.sort) params.append('sort', filters.sort);

    params.append('page', filters.page);

    try {
        const response = await fetch(`${props.dataUrl}?${params.toString()}`, {
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const result = await response.json();

        sales.value = result.data || [];
        productsForSelect.value = result.products || [];
        channels.value = result.channels || [];
        channelOptions.forEach((channel) => {
            if (!channels.value.includes(channel)) {
                channels.value.push(channel);
            }
        });

        meta.current_page = result.meta?.current_page || 1;
        meta.last_page = result.meta?.last_page || 1;
        meta.from = result.meta?.from || 0;
        meta.to = result.meta?.to || 0;
        meta.total = result.meta?.total || 0;
    } catch (error) {
        console.error('Gagal memuat data penjualan:', error);
    } finally {
        loading.value = false;
    }
};

const resetFilters = () => {
    filters.search = '';
    filters.channel = '';
    filters.sort = 'latest';
    filters.page = 1;

    fetchSales();
};

const goToPage = (page) => {
    if (page < 1 || page > meta.last_page) return;

    filters.page = page;
    fetchSales();
};

watch(
    () => [filters.search, filters.channel, filters.sort],
    () => {
        clearTimeout(debounceTimer.value);

        debounceTimer.value = setTimeout(() => {
            filters.page = 1;
            fetchSales();
        }, 350);
    }
);

const resetCreateForm = () => {
    createForm.product_id = '';
    createForm.channel = '';
    createForm.quantity = 1;
    createForm.platform_fee = '';
    createForm.status = 'Selesai';
    createForm.note = '';
    createForm.sale_date = new Date().toISOString().slice(0, 10);
};

const openCreateModal = () => {
    resetCreateForm();
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
};

const openEditModal = (sale) => {
    editForm.id = sale.id;
    editForm.product_id = sale.product_id || '';
    editForm.channel = sale.channel || '';
    editForm.quantity = sale.quantity_raw ?? 1;
    editForm.platform_fee = formatRibuan(sale.platform_fee_raw);
    editForm.status = sale.status || 'Selesai';
    editForm.note = sale.note || '';
    editForm.sale_date = sale.sale_date_raw || '';

    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const buildFormData = (form, method = null) => {
    const formData = new FormData();

    formData.append('_token', props.csrfToken);

    if (method) {
        formData.append('_method', method);
    }

    formData.append('product_id', form.product_id);
    formData.append('channel', form.channel || '');
    formData.append('quantity', form.quantity);
    formData.append('platform_fee', cleanNumber(form.platform_fee));
    formData.append('status', form.status || 'Selesai');
    formData.append('note', form.note || '');
    formData.append('sale_date', form.sale_date || '');

    return formData;
};

const createSale = async () => {
    saving.value = true;

    try {
        const response = await fetch(props.createUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': props.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: buildFormData(createForm),
        });

        if (!response.ok) {
            alert(`Gagal menambah penjualan. Status: ${response.status}`);
            return;
        }

        showCreateModal.value = false;
        window.location.reload();
    } finally {
        saving.value = false;
    }
};

const updateSale = async () => {
    saving.value = true;

    try {
        const response = await fetch(`${props.editBaseUrl}/${editForm.id}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': props.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: buildFormData(editForm, 'PUT'),
        });

        if (!response.ok) {
            alert(`Gagal menyimpan penjualan. Status: ${response.status}`);
            return;
        }

        showEditModal.value = false;
        window.location.reload();
    } finally {
        saving.value = false;
    }
};

const deleteSale = async (sale) => {
    const confirmed = confirm(`Yakin ingin menghapus transaksi "${sale.product_name}"?`);

    if (!confirmed) return;

    const formData = new FormData();
    formData.append('_token', props.csrfToken);
    formData.append('_method', 'DELETE');

    const response = await fetch(`${props.editBaseUrl}/${sale.id}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': props.csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData,
    });

    if (!response.ok) {
        alert(`Gagal menghapus penjualan. Status: ${response.status}`);
        return;
    }

    window.location.reload();
};

const handleExternalCreateModalOpen = () => {
    openCreateModal();
};

onMounted(() => {
    fetchSales();
    window.addEventListener('open-create-sale-modal', handleExternalCreateModalOpen);
});

onBeforeUnmount(() => {
    window.removeEventListener('open-create-sale-modal', handleExternalCreateModalOpen);
});
</script>