<template>
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-black">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Live Product Data
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 mt-4">
                        Produk & Stok
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed max-w-2xl">
                        Kelola produk, kategori, harga jual, harga modal, dan status stok secara realtime.
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
                    Tambah Produk
                </button>
            </div>
        </div>

        <!-- Filter -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/60">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Cari Produk
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
                            placeholder="Cari nama produk atau kategori..."
                            class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Kategori
                    </label>

                    <select
                        v-model="filters.category"
                        class="mt-2 w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                        <option value="">Semua kategori</option>
                        <option v-for="category in categories" :key="category" :value="category">
                            {{ category }}
                        </option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Status Stok
                    </label>

                    <select
                        v-model="filters.stock_status"
                        class="mt-2 w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                        <option value="">Semua status</option>
                        <option value="safe">Aman</option>
                        <option value="low">Stok Rendah</option>
                        <option value="out">Habis</option>
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
                produk
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
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Kategori</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Harga</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Stok</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Status</th>
                        <th class="text-right px-6 py-4 font-black text-xs uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr v-if="loading">
                        <td colspan="6" class="px-6 py-14 text-center">
                            <div class="inline-flex items-center gap-3 text-slate-500 font-semibold">
                                <span class="w-5 h-5 rounded-full border-2 border-slate-300 border-t-emerald-500 animate-spin"></span>
                                Memuat data produk...
                            </div>
                        </td>
                    </tr>

                    <tr v-else-if="products.length === 0">
                        <td colspan="6" class="px-6 py-14 text-center">
                            <h3 class="text-lg font-black text-slate-900">
                                Produk tidak ditemukan
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Coba ubah kata kunci atau reset filter.
                            </p>
                        </td>
                    </tr>

                    <tr v-for="product in products" :key="product.id" class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black shrink-0">
                                    {{ getInitial(product.name) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 truncate">
                                        {{ product.name }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Dibuat: {{ product.created_at || '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-black">
                                {{ product.category || '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="font-black text-slate-900">
                                {{ product.selling_price }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Modal {{ product.cost_price }}
                            </p>
                        </td>

                        <td class="px-6 py-5">
                            <p class="font-black text-slate-900">
                                {{ product.stock }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                Limit {{ product.low_stock_limit }}
                            </p>
                        </td>

                        <td class="px-6 py-5">
                            <span :class="['px-3 py-1.5 rounded-full text-xs font-black', stockStatusClass(product.stock_status)]">
                                {{ stockStatusText(product.stock_status) }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    type="button"
                                    @click="openEditModal(product)"
                                    class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex items-center justify-center"
                                    title="Edit produk"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    @click="deleteProduct(product)"
                                    class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition flex items-center justify-center"
                                    title="Hapus produk"
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
                Halaman
                <span class="font-black text-slate-900">{{ meta.current_page || 1 }}</span>
                dari
                <span class="font-black text-slate-900">{{ meta.last_page || 1 }}</span>
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

        <!-- Create Modal -->
        <ProductFormModal
            v-if="showCreateModal"
            title="Tambah Produk"
            subtitle="Tambahkan produk baru ke database"
            :saving="saving"
            :form="createForm"
            submit-label="Simpan Produk"
            @close="closeCreateModal"
            @submit="createProduct"
        />

        <!-- Edit Modal -->
        <ProductFormModal
            v-if="showEditModal"
            title="Edit Produk"
            subtitle="Perbarui data produk"
            :saving="saving"
            :form="editForm"
            submit-label="Simpan Perubahan"
            @close="closeEditModal"
            @submit="updateProduct"
        />
    </div>
</template>

<script setup>
import { computed, defineComponent, h, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue';

const ProductFormModal = defineComponent({
    props: {
        title: String,
        subtitle: String,
        saving: Boolean,
        form: Object,
        submitLabel: String,
    },
    emits: ['close', 'submit'],
    setup(props, { emit }) {
        return () => h('div', {
            class: 'fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center px-4',
        }, [
            h('div', {
                class: 'bg-white rounded-3xl p-6 w-full max-w-lg shadow-xl',
            }, [
                h('div', {
                    class: 'flex items-center justify-between mb-6',
                }, [
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
                        h('label', { class: 'text-sm font-bold text-slate-700' }, 'Nama produk'),
                        h('input', {
                            value: props.form.name,
                            type: 'text',
                            required: true,
                            class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                            onInput: (event) => props.form.name = event.target.value,
                        }),
                    ]),
                    h('div', {}, [
                        h('label', { class: 'text-sm font-bold text-slate-700' }, 'Kategori'),
                        h('input', {
                            value: props.form.category,
                            type: 'text',
                            class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                            onInput: (event) => props.form.category = event.target.value,
                        }),
                    ]),
                    h('div', { class: 'grid grid-cols-2 gap-3' }, [
                        h('div', {}, [
                            h('label', { class: 'text-sm font-bold text-slate-700' }, 'Harga jual'),
                            h('input', {
                                value: props.form.selling_price,
                                type: 'text',
                                inputmode: 'numeric',
                                required: true,
                                class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                                onInput: (event) => props.form.selling_price = formatRibuan(event.target.value),
                            }),
                        ]),
                        h('div', {}, [
                            h('label', { class: 'text-sm font-bold text-slate-700' }, 'Modal'),
                            h('input', {
                                value: props.form.cost_price,
                                type: 'text',
                                inputmode: 'numeric',
                                class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                                onInput: (event) => props.form.cost_price = formatRibuan(event.target.value),
                            }),
                        ]),
                    ]),
                    h('div', { class: 'grid grid-cols-2 gap-3' }, [
                        h('div', {}, [
                            h('label', { class: 'text-sm font-bold text-slate-700' }, 'Stok'),
                            h('input', {
                                value: props.form.stock,
                                type: 'number',
                                required: true,
                                class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                                onInput: (event) => props.form.stock = event.target.value,
                            }),
                        ]),
                        h('div', {}, [
                            h('label', { class: 'text-sm font-bold text-slate-700' }, 'Limit rendah'),
                            h('input', {
                                value: props.form.low_stock_limit,
                                type: 'number',
                                class: 'mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500',
                                onInput: (event) => props.form.low_stock_limit = event.target.value,
                            }),
                        ]),
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
    dataUrl: {
        type: String,
        required: true,
    },
    createUrl: {
        type: String,
        required: true,
    },
    editBaseUrl: {
        type: String,
        required: true,
    },
    csrfToken: {
        type: String,
        required: true,
    },
});

const products = ref([]);
const categories = ref([]);
const loading = ref(false);
const saving = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const debounceTimer = ref(null);

const filters = reactive({
    search: '',
    category: '',
    stock_status: '',
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
    name: '',
    category: '',
    selling_price: '',
    cost_price: '',
    stock: '',
    low_stock_limit: 5,
});

const editForm = reactive({
    id: null,
    name: '',
    category: '',
    selling_price: '',
    cost_price: '',
    stock: '',
    low_stock_limit: '',
});

const hasActiveFilter = computed(() => {
    return Boolean(filters.search || filters.category || filters.stock_status);
});

const fetchProducts = async () => {
    loading.value = true;

    const params = new URLSearchParams();

    if (filters.search) params.append('search', filters.search);
    if (filters.category) params.append('category', filters.category);
    if (filters.stock_status) params.append('stock_status', filters.stock_status);

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

        products.value = result.data || [];
        categories.value = result.categories || [];

        meta.current_page = result.meta?.current_page || 1;
        meta.last_page = result.meta?.last_page || 1;
        meta.from = result.meta?.from || 0;
        meta.to = result.meta?.to || 0;
        meta.total = result.meta?.total || 0;
    } catch (error) {
        console.error('Gagal memuat data produk:', error);
    } finally {
        loading.value = false;
    }
};

const resetFilters = () => {
    filters.search = '';
    filters.category = '';
    filters.stock_status = '';
    filters.page = 1;

    fetchProducts();
};

const goToPage = (page) => {
    if (page < 1 || page > meta.last_page) return;

    filters.page = page;
    fetchProducts();
};

watch(
    () => [filters.search, filters.category, filters.stock_status],
    () => {
        clearTimeout(debounceTimer.value);

        debounceTimer.value = setTimeout(() => {
            filters.page = 1;
            fetchProducts();
        }, 350);
    }
);

const getInitial = (name) => {
    if (!name) return 'P';

    return name.charAt(0).toUpperCase();
};

const stockStatusText = (status) => {
    if (status === 'out') return 'Habis';
    if (status === 'low') return 'Stok Rendah';

    return 'Aman';
};

const stockStatusClass = (status) => {
    if (status === 'out') return 'bg-red-50 text-red-700';
    if (status === 'low') return 'bg-amber-50 text-amber-700';

    return 'bg-emerald-50 text-emerald-700';
};

function formatRibuan(value) {
    const number = String(value || '').replace(/\D/g, '');

    if (!number) return '';

    return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

const cleanNumber = (value) => {
    return String(value || '').replace(/\D/g, '');
};

const resetCreateForm = () => {
    createForm.name = '';
    createForm.category = '';
    createForm.selling_price = '';
    createForm.cost_price = '';
    createForm.stock = '';
    createForm.low_stock_limit = 5;
};

const openCreateModal = () => {
    resetCreateForm();
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
};

const openEditModal = (product) => {
    editForm.id = product.id;
    editForm.name = product.name || '';
    editForm.category = product.category || '';
    editForm.selling_price = formatRibuan(product.selling_price_raw);
    editForm.cost_price = formatRibuan(product.cost_price_raw);
    editForm.stock = product.stock_raw ?? 0;
    editForm.low_stock_limit = product.low_stock_limit_raw ?? 0;

    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const createProduct = async () => {
    saving.value = true;

    const formData = new FormData();

    formData.append('_token', props.csrfToken);
    formData.append('name', createForm.name);
    formData.append('category', createForm.category || '');
    formData.append('selling_price', cleanNumber(createForm.selling_price));
    formData.append('cost_price', cleanNumber(createForm.cost_price));
    formData.append('stock', createForm.stock);
    formData.append('low_stock_limit', createForm.low_stock_limit || 5);

    try {
        const response = await fetch(props.createUrl, {
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
            const errorText = await response.text();

            console.error('Gagal tambah produk:', {
                status: response.status,
                response: errorText,
            });

            alert(`Gagal menambah produk. Status: ${response.status}`);
            return;
        }

        showCreateModal.value = false;
        window.location.reload();
    } catch (error) {
        console.error('Gagal tambah produk:', error);
        alert('Gagal menambah produk.');
    } finally {
        saving.value = false;
    }
};

const updateProduct = async () => {
    saving.value = true;

    const formData = new FormData();

    formData.append('_token', props.csrfToken);
    formData.append('_method', 'PUT');
    formData.append('name', editForm.name);
    formData.append('category', editForm.category || '');
    formData.append('selling_price', cleanNumber(editForm.selling_price));
    formData.append('cost_price', cleanNumber(editForm.cost_price));
    formData.append('stock', editForm.stock);
    formData.append('low_stock_limit', editForm.low_stock_limit || 0);

    try {
        const response = await fetch(`${props.editBaseUrl}/${editForm.id}`, {
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
            const errorText = await response.text();

            console.error('Gagal update produk:', {
                status: response.status,
                response: errorText,
            });

            alert(`Gagal menyimpan produk. Status: ${response.status}`);
            return;
        }

        showEditModal.value = false;
        window.location.reload();
    } catch (error) {
        console.error('Gagal update produk:', error);
        alert('Gagal menyimpan perubahan produk.');
    } finally {
        saving.value = false;
    }
};

const deleteProduct = async (product) => {
    const confirmed = confirm(`Yakin ingin menghapus produk "${product.name}"?`);

    if (!confirmed) return;

    const formData = new FormData();

    formData.append('_token', props.csrfToken);
    formData.append('_method', 'DELETE');

    try {
        const response = await fetch(`${props.editBaseUrl}/${product.id}`, {
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
            const errorText = await response.text();

            console.error('Gagal menghapus produk:', {
                status: response.status,
                response: errorText,
            });

            alert(`Gagal menghapus produk. Status: ${response.status}`);
            return;
        }

        window.location.reload();
    } catch (error) {
        console.error('Gagal menghapus produk:', error);
        alert('Gagal menghapus produk.');
    }
};

const handleExternalCreateModalOpen = () => {
    openCreateModal();
};

onMounted(() => {
    fetchProducts();
    window.addEventListener('open-create-product-modal', handleExternalCreateModalOpen);
});

onBeforeUnmount(() => {
    window.removeEventListener('open-create-product-modal', handleExternalCreateModalOpen);
});
</script>