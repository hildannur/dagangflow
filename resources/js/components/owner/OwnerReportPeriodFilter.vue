<template>
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-5">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Filter Periode</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Data laporan otomatis mengikuti periode yang dipilih.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full xl:w-auto">
                <div>
                    <label class="text-xs font-semibold text-slate-500">Tanggal mulai</label>
                    <input
                        v-model="form.start_date"
                        type="date"
                        class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-500">Tanggal akhir</label>
                    <input
                        v-model="form.end_date"
                        type="date"
                        class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                    >
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 mt-5">
            <button
                v-for="period in periods"
                :key="period.label"
                type="button"
                @click="applyShortcut(period.start, period.end)"
                :class="[
                    'px-4 py-2 rounded-xl border text-sm transition',
                    isActivePeriod(period.start, period.end)
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100 font-bold'
                        : 'border-slate-200 text-slate-700 font-medium hover:bg-slate-50'
                ]"
            >
                {{ period.label }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from 'vue';

const props = defineProps({
    baseUrl: {
        type: String,
        required: true,
    },
    startDate: {
        type: String,
        required: true,
    },
    endDate: {
        type: String,
        required: true,
    },
});

const debounceTimer = ref(null);

const form = reactive({
    start_date: props.startDate,
    end_date: props.endDate,
});

const formatDate = (date) => {
    return date.toISOString().slice(0, 10);
};

const now = new Date();

const today = formatDate(new Date());

const sevenDaysAgo = (() => {
    const date = new Date();
    date.setDate(date.getDate() - 6);

    return formatDate(date);
})();

const thisMonthStart = (() => {
    const date = new Date(now.getFullYear(), now.getMonth(), 1);

    return formatDate(date);
})();

const thisMonthEnd = (() => {
    const date = new Date(now.getFullYear(), now.getMonth() + 1, 0);

    return formatDate(date);
})();

const lastMonthStart = (() => {
    const date = new Date(now.getFullYear(), now.getMonth() - 1, 1);

    return formatDate(date);
})();

const lastMonthEnd = (() => {
    const date = new Date(now.getFullYear(), now.getMonth(), 0);

    return formatDate(date);
})();

const periods = [
    {
        label: 'Hari Ini',
        start: today,
        end: today,
    },
    {
        label: '7 Hari',
        start: sevenDaysAgo,
        end: today,
    },
    {
        label: 'Bulan Ini',
        start: thisMonthStart,
        end: thisMonthEnd,
    },
    {
        label: 'Bulan Lalu',
        start: lastMonthStart,
        end: lastMonthEnd,
    },
];

const isActivePeriod = (start, end) => {
    return form.start_date === start && form.end_date === end;
};

const updateUrl = () => {
    if (!form.start_date || !form.end_date) {
        return;
    }

    const url = new URL(props.baseUrl, window.location.origin);

    url.searchParams.set('start_date', form.start_date);
    url.searchParams.set('end_date', form.end_date);

    window.location.href = url.toString();
};

const applyShortcut = (start, end) => {
    form.start_date = start;
    form.end_date = end;

    updateUrl();
};

watch(
    () => [form.start_date, form.end_date],
    () => {
        clearTimeout(debounceTimer.value);

        debounceTimer.value = setTimeout(() => {
            updateUrl();
        }, 600);
    }
);

onMounted(() => {
    form.start_date = props.startDate;
    form.end_date = props.endDate;
});
</script>