<template>
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-black">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Live Subscription Data
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 mt-4">
                        Daftar Subscription
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed max-w-2xl">
                        Pantau paket langganan, status aktif, masa berakhir, dan kelola akun owner DagangFlow.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 6h13" />
                            <path d="M8 12h13" />
                            <path d="M8 18h13" />
                            <path d="M3 6h.01" />
                            <path d="M3 12h.01" />
                            <path d="M3 18h.01" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/60">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Cari User
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
                            placeholder="Cari nama, email, atau bisnis..."
                            class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Status
                    </label>

                    <select
                        v-model="filters.status"
                        class="mt-2 w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                        <option value="">Semua status</option>
                        <option value="trial">Trial</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-wide">
                        Paket
                    </label>

                    <select
                        v-model="filters.plan"
                        class="mt-2 w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                        <option value="">Semua paket</option>
                        <option value="Free">Free</option>
                        <option value="Trial">Trial</option>
                        <option value="Bulanan">Bulanan</option>
                        <option value="Tahunan">Tahunan</option>
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
                subscription
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

        <!-- Table Desktop -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">User</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Bisnis</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Paket</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Status</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Periode</th>
                        <th class="text-left px-6 py-4 font-black text-xs uppercase tracking-wide">Sisa Hari</th>
                        <th class="text-right px-6 py-4 font-black text-xs uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <tr v-if="loading">
                        <td colspan="7" class="px-6 py-14 text-center">
                            <div class="inline-flex items-center gap-3 text-slate-500 font-semibold">
                                <span class="w-5 h-5 rounded-full border-2 border-slate-300 border-t-emerald-500 animate-spin"></span>
                                Memuat data subscription...
                            </div>
                        </td>
                    </tr>

                    <tr v-else-if="users.length === 0">
                        <td colspan="7" class="px-6 py-14 text-center">
                            <div class="w-16 h-16 mx-auto rounded-3xl bg-slate-100 text-slate-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" x2="12" y1="15" y2="3" />
                                </svg>
                            </div>

                            <h3 class="text-lg font-black text-slate-900 mt-4">
                                Data tidak ditemukan
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Coba ubah kata kunci atau reset filter.
                            </p>
                        </td>
                    </tr>

                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="hover:bg-slate-50/80 transition"
                    >
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black shrink-0">
                                    {{ getInitial(user.name) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 truncate">
                                        {{ user.name }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-900">
                                {{ user.business_name || '-' }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ user.business_type || '-' }}
                            </p>
                        </td>

                        <td class="px-6 py-5">
                            <span :class="['px-3 py-1.5 rounded-full text-xs font-black', planClass(user.plan_name)]">
                                {{ user.plan_name || 'Free' }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <span :class="['px-3 py-1.5 rounded-full text-xs font-black capitalize', statusClass(user.subscription_status)]">
                                {{ user.subscription_status || '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-5 whitespace-nowrap">
                            <p class="font-bold text-slate-900">
                                {{ user.subscription_started_at || '-' }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                sampai {{ user.subscription_ends_at || '-' }}
                            </p>
                        </td>

                        <td class="px-6 py-5 whitespace-nowrap">
                            <span :class="daysLeftClass(user.days_left)">
                                {{ daysLeftText(user.days_left) }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    :href="`${userShowBaseUrl}/${user.id}`"
                                    class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition flex items-center justify-center"
                                    title="Kelola subscription"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden divide-y divide-slate-100">
            <div v-if="loading" class="p-8 text-center text-slate-500 font-semibold">
                Memuat data subscription...
            </div>

            <div v-else-if="users.length === 0" class="p-8 text-center">
                <h3 class="font-black text-slate-900">
                    Data tidak ditemukan
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Coba ubah kata kunci atau reset filter.
                </p>
            </div>

            <div
                v-for="user in users"
                v-else
                :key="user.id"
                class="p-5"
            >
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black shrink-0">
                        {{ getInitial(user.name) }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="font-black text-slate-900">
                            {{ user.name }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ user.email }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-3">
                            <span :class="['px-3 py-1.5 rounded-full text-xs font-black', planClass(user.plan_name)]">
                                {{ user.plan_name || 'Free' }}
                            </span>

                            <span :class="['px-3 py-1.5 rounded-full text-xs font-black capitalize', statusClass(user.subscription_status)]">
                                {{ user.subscription_status || '-' }}
                            </span>
                        </div>
                    </div>

                    <a
                        :href="`${userShowBaseUrl}/${user.id}`"
                        class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition flex items-center justify-center shrink-0"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4">
                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs text-slate-500">Bisnis</p>
                        <p class="text-sm font-bold text-slate-900 mt-1">
                            {{ user.business_name || '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs text-slate-500">Sisa Hari</p>
                        <p :class="['text-sm mt-1', daysLeftClass(user.days_left)]">
                            {{ daysLeftText(user.days_left) }}
                        </p>
                    </div>

                    <div class="col-span-2 rounded-2xl bg-slate-50 border border-slate-100 p-4">
                        <p class="text-xs text-slate-500">Periode</p>
                        <p class="text-sm font-bold text-slate-900 mt-1">
                            {{ user.subscription_started_at || '-' }} — {{ user.subscription_ends_at || '-' }}
                        </p>
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
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';

const props = defineProps({
    dataUrl: {
        type: String,
        required: true,
    },
    userShowBaseUrl: {
        type: String,
        required: true,
    },
});

const users = ref([]);
const loading = ref(false);
const debounceTimer = ref(null);

const filters = reactive({
    search: '',
    status: '',
    plan: '',
    page: 1,
});

const meta = reactive({
    current_page: 1,
    last_page: 1,
    from: 0,
    to: 0,
    total: 0,
});

const hasActiveFilter = computed(() => {
    return Boolean(filters.search || filters.status || filters.plan);
});

const fetchUsers = async () => {
    loading.value = true;

    const params = new URLSearchParams();

    if (filters.search) params.append('search', filters.search);
    if (filters.status) params.append('status', filters.status);
    if (filters.plan) params.append('plan', filters.plan);

    params.append('page', filters.page);

    try {
        const response = await fetch(`${props.dataUrl}?${params.toString()}`, {
            headers: {
                Accept: 'application/json',
            },
        });

        const result = await response.json();

        users.value = result.data || [];

        meta.current_page = result.meta?.current_page || 1;
        meta.last_page = result.meta?.last_page || 1;
        meta.from = result.meta?.from || 0;
        meta.to = result.meta?.to || 0;
        meta.total = result.meta?.total || 0;
    } catch (error) {
        console.error('Gagal memuat data subscriptions:', error);
    } finally {
        loading.value = false;
    }
};

const resetFilters = () => {
    filters.search = '';
    filters.status = '';
    filters.plan = '';
    filters.page = 1;

    fetchUsers();
};

const goToPage = (page) => {
    if (page < 1 || page > meta.last_page) return;

    filters.page = page;
    fetchUsers();
};

watch(
    () => [filters.search, filters.status, filters.plan],
    () => {
        clearTimeout(debounceTimer.value);

        debounceTimer.value = setTimeout(() => {
            filters.page = 1;
            fetchUsers();
        }, 350);
    }
);

const getInitial = (name) => {
    if (!name) return 'U';

    return name.charAt(0).toUpperCase();
};

const planClass = (planName) => {
    if (planName === 'Trial') return 'bg-blue-50 text-blue-700';
    if (planName === 'Bulanan') return 'bg-emerald-50 text-emerald-700';
    if (planName === 'Tahunan') return 'bg-indigo-50 text-indigo-700';

    return 'bg-slate-100 text-slate-700';
};

const statusClass = (status) => {
    if (status === 'trial') return 'bg-blue-50 text-blue-700';
    if (status === 'active') return 'bg-emerald-50 text-emerald-700';
    if (status === 'expired') return 'bg-red-50 text-red-700';

    return 'bg-slate-100 text-slate-700';
};

const daysLeftText = (daysLeft) => {
    if (daysLeft === null || daysLeft === undefined) return '-';
    if (daysLeft < 0) return 'Expired';

    return `${daysLeft} hari`;
};

const daysLeftClass = (daysLeft) => {
    if (daysLeft === null || daysLeft === undefined) return 'font-bold text-slate-400';
    if (daysLeft < 0) return 'font-bold text-red-600';
    if (daysLeft <= 7) return 'font-bold text-amber-600';

    return 'font-bold text-emerald-600';
};

onMounted(() => {
    fetchUsers();
});
</script>