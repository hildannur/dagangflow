<template>
    <div class="xl:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-bold">Tren {{ activeMetric.label }}</h3>
                <p class="text-sm text-slate-500">
                    Performa {{ activeMetric.label.toLowerCase() }} 7 hari terakhir
                </p>
            </div>

            <select
                v-model="selectedMetric"
                class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
            >
                <option value="omzet">Omzet</option>
                <option value="laba">Laba</option>
                <option value="transaksi">Transaksi</option>
            </select>
        </div>

        <div class="h-80 rounded-2xl bg-gradient-to-br from-emerald-50 to-slate-50 border border-slate-100 p-6 flex items-end gap-4">
            <div
                v-for="item in normalizedItems"
                :key="item.day"
                class="flex-1 h-full flex flex-col justify-end items-center gap-3"
            >
                <p class="text-xs font-semibold text-slate-500 text-center">
                    {{ formatValue(item.value) }}
                </p>

                <div
                    :class="[
                        'w-full rounded-t-xl transition',
                        activeMetric.barClass
                    ]"
                    :style="{ height: item.height + '%' }"
                ></div>

                <p class="text-xs text-slate-500">
                    {{ item.day }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    chartItems: {
        type: Array,
        required: true,
    },
});

const selectedMetric = ref('omzet');

const metrics = {
    omzet: {
        key: 'total',
        label: 'Omzet',
        prefix: 'Rp',
        barClass: 'bg-emerald-500 hover:bg-emerald-600',
    },
    laba: {
        key: 'profit',
        label: 'Laba',
        prefix: 'Rp',
        barClass: 'bg-blue-500 hover:bg-blue-600',
    },
    transaksi: {
        key: 'transactions',
        label: 'Transaksi',
        prefix: '',
        suffix: 'x',
        barClass: 'bg-slate-700 hover:bg-slate-800',
    },
};

const activeMetric = computed(() => {
    return metrics[selectedMetric.value] || metrics.omzet;
});

const maxValue = computed(() => {
    const values = props.chartItems.map((item) => {
        return Number(item[activeMetric.value.key] || 0);
    });

    return Math.max(...values, 1);
});

const normalizedItems = computed(() => {
    return props.chartItems.map((item) => {
        const value = Number(item[activeMetric.value.key] || 0);

        return {
            day: item.day,
            value,
            height: value > 0 ? Math.max(12, (value / maxValue.value) * 100) : 6,
        };
    });
});

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID').format(Number(value || 0));
};

const formatValue = (value) => {
    if (selectedMetric.value === 'transaksi') {
        return `${formatRupiah(value)}x`;
    }

    return `Rp${formatRupiah(value)}`;
};
</script>