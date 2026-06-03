@extends('layouts.admin', [
    'title' => 'Subscriptions - Superadmin DagangFlow',
    'pageTitle' => 'Subscription Management',
    'subtitle' => 'Superadmin / Subscriptions',
])

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-badge-check class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Active</p>
            <h3 class="text-4xl font-black mt-3 text-emerald-600">
                {{ number_format($activeSubscriptions, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                <x-lucide-sparkles class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Trial</p>
            <h3 class="text-4xl font-black mt-3 text-blue-600">
                {{ number_format($trialSubscriptions, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                <x-lucide-badge-x class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Expired</p>
            <h3 class="text-4xl font-black mt-3 text-red-600">
                {{ number_format($expiredSubscriptions, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <x-lucide-clock class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Expired Soon</p>
            <h3 class="text-4xl font-black mt-3 text-amber-600">
                {{ number_format($expiredSoonSubscriptions, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Vue Subscriptions Table -->
    <div
        id="admin-subscriptions-table"
        data-data-url="{{ route('admin.subscriptions.data') }}"
        data-user-show-base-url="{{ url('/admin/users') }}"
    ></div>
@endsection