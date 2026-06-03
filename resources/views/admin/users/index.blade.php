@extends('layouts.admin', [
    'title' => 'Users - Superadmin DagangFlow',
    'pageTitle' => 'Users Management',
    'subtitle' => 'Superadmin / Users',
])

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mb-5">
                <x-lucide-users class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Total Owner</p>
            <h3 class="text-4xl font-black mt-3">
                {{ number_format($totalOwners, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <x-lucide-user-check class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Aktif</p>
            <h3 class="text-4xl font-black mt-3 text-emerald-600">
                {{ number_format($activeOwners, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center mb-5">
                <x-lucide-user-x class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Suspended</p>
            <h3 class="text-4xl font-black mt-3 text-slate-600">
                {{ number_format($suspendedOwners, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <x-lucide-clock class="w-6 h-6" />
            </div>

            <p class="text-sm text-slate-500">Expired</p>
            <h3 class="text-4xl font-black mt-3 text-amber-600">
                {{ number_format($expiredOwners, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Vue Users Table -->
    <div
        id="admin-users-table"
        data-data-url="{{ route('admin.users.data') }}"
        data-user-show-base-url="{{ url('/admin/users') }}"
    ></div>
@endsection