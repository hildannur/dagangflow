<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'owner')->count();

        $activeUsers = User::where('role', 'owner')
            ->where('status', 'active')
            ->count();

        $newUsersThisMonth = User::where('role', 'owner')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $freePlanUsers = User::where('role', 'owner')
            ->where('plan_name', 'Free')
            ->count();

        $internalUsers = User::where('role', 'superadmin')
            ->count();

        $expiredSoonUsers = User::where('role', 'owner')
            ->whereNotNull('subscription_ends_at')
            ->whereBetween('subscription_ends_at', [
                now(),
                now()->addDays(7),
            ])
            ->count();

        $latestUsers = User::where('role', 'owner')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'newUsersThisMonth',
            'freePlanUsers',
            'internalUsers',
            'expiredSoonUsers',
            'latestUsers'
        ));
    }
}