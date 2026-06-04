import './bootstrap';

import { createApp } from 'vue';
import AdminSubscriptionsTable from './components/admin/AdminSubscriptionsTable.vue';
import AdminUsersTable from './components/admin/AdminUsersTable.vue';
import OwnerProductsTable from './components/owner/OwnerProductsTable.vue';
import OwnerSalesTable from './components/owner/OwnerSalesTable.vue';
import OwnerReportPeriodFilter from './components/owner/OwnerReportPeriodFilter.vue';

const adminSubscriptionsTable = document.getElementById('admin-subscriptions-table');

if (adminSubscriptionsTable) {
    createApp(AdminSubscriptionsTable, {
        dataUrl: adminSubscriptionsTable.dataset.dataUrl,
        userShowBaseUrl: adminSubscriptionsTable.dataset.userShowBaseUrl,
    }).mount('#admin-subscriptions-table');
}

const adminUsersTable = document.getElementById('admin-users-table');

if (adminUsersTable) {
    createApp(AdminUsersTable, {
        dataUrl: adminUsersTable.dataset.dataUrl,
        userShowBaseUrl: adminUsersTable.dataset.userShowBaseUrl,
    }).mount('#admin-users-table');
}

const ownerProductsTable = document.getElementById('owner-products-table');

if (ownerProductsTable) {
    createApp(OwnerProductsTable, {
        dataUrl: ownerProductsTable.dataset.dataUrl,
        createUrl: ownerProductsTable.dataset.createUrl,
        editBaseUrl: ownerProductsTable.dataset.editBaseUrl,
        csrfToken: ownerProductsTable.dataset.csrfToken,
    }).mount('#owner-products-table');
}

const ownerSalesTable = document.getElementById('owner-sales-table');

if (ownerSalesTable) {
    createApp(OwnerSalesTable, {
        dataUrl: ownerSalesTable.dataset.dataUrl,
        createUrl: ownerSalesTable.dataset.createUrl,
        editBaseUrl: ownerSalesTable.dataset.editBaseUrl,
        csrfToken: ownerSalesTable.dataset.csrfToken,
    }).mount('#owner-sales-table');
}

const ownerReportPeriodFilter = document.getElementById('owner-report-period-filter');

if (ownerReportPeriodFilter) {
    createApp(OwnerReportPeriodFilter, {
        baseUrl: ownerReportPeriodFilter.dataset.baseUrl,
        startDate: ownerReportPeriodFilter.dataset.startDate,
        endDate: ownerReportPeriodFilter.dataset.endDate,
    }).mount('#owner-report-period-filter');
}