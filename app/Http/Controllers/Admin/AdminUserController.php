<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $plan = $request->query('plan');

        $users = User::where('role', 'owner')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%")
                        ->orWhere('business_type', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($plan, function ($query) use ($plan) {
                $query->where('plan_name', $plan);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalOwners = User::where('role', 'owner')->count();

        $activeOwners = User::where('role', 'owner')
            ->where('status', 'active')
            ->count();

        $suspendedOwners = User::where('role', 'owner')
            ->where('status', 'suspended')
            ->count();

        $expiredOwners = User::where('role', 'owner')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<', now())
            ->count();

        $plans = User::where('role', 'owner')
            ->whereNotNull('plan_name')
            ->select('plan_name')
            ->distinct()
            ->orderBy('plan_name')
            ->pluck('plan_name');

        return view('admin.users.index', compact(
            'users',
            'search',
            'status',
            'plan',
            'plans',
            'totalOwners',
            'activeOwners',
            'suspendedOwners',
            'expiredOwners'
        ));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'owner', 404);

        $totalProducts = Product::where('user_id', $user->id)->count();

        $totalSales = Sale::where('user_id', $user->id)->count();

        $totalRevenue = Sale::where('user_id', $user->id)->sum('gross_total');

        $totalNetRevenue = Sale::where('user_id', $user->id)->sum('net_total');

        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');

        $latestSales = Sale::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        $latestProducts = Product::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'totalProducts',
            'totalSales',
            'totalRevenue',
            'totalNetRevenue',
            'totalExpenses',
            'latestSales',
            'latestProducts'
        ));
    }

    public function suspend(User $user)
    {
        abort_if($user->role !== 'owner', 404);

        $user->update([
            'status' => 'suspended',
        ]);

        return back()->with('success', 'User berhasil disuspend.');
    }

    public function activate(User $user)
    {
        abort_if($user->role !== 'owner', 404);

        $user->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'User berhasil diaktifkan kembali.');
    }

    public function updateSubscription(Request $request, User $user)
{
    abort_if($user->role !== 'owner', 404);

    $data = $request->validate([
        'plan_name' => ['required', 'string', 'max:255'],
        'subscription_status' => ['required', 'string', 'max:255'],
        'subscription_started_at' => ['nullable', 'date'],
        'subscription_ends_at' => ['nullable', 'date'],
    ], [
        'plan_name.required' => 'Paket wajib dipilih.',
        'subscription_status.required' => 'Status subscription wajib dipilih.',
        'subscription_started_at.date' => 'Tanggal mulai tidak valid.',
        'subscription_ends_at.date' => 'Tanggal berakhir tidak valid.',
    ]);

    $user->update([
        'plan_name' => $data['plan_name'],
        'subscription_status' => $data['subscription_status'],
        'subscription_started_at' => $data['subscription_started_at'] ?? now(),
        'subscription_ends_at' => $data['subscription_ends_at'] ?? null,
    ]);

    return back()->with('success', 'Subscription user berhasil diperbarui.');
}

    public function extendSubscription(Request $request, User $user)
    {
        abort_if($user->role !== 'owner', 404);

        $data = $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ], [
            'days.required' => 'Jumlah hari wajib diisi.',
            'days.integer' => 'Jumlah hari harus berupa angka.',
            'days.min' => 'Jumlah hari minimal 1.',
            'days.max' => 'Jumlah hari maksimal 365.',
        ]);

        $currentEndDate = $user->subscription_ends_at && $user->subscription_ends_at->isFuture()
            ? $user->subscription_ends_at
            : now();

        $user->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => $currentEndDate->copy()->addDays((int) $data['days']),
        ]);

        return back()->with('success', 'Masa subscription user berhasil diperpanjang.');
    }

    public function subscriptions(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $plan = $request->query('plan');

        $users = User::where('role', 'owner')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('subscription_status', $status);
            })
            ->when($plan, function ($query) use ($plan) {
                $query->where('plan_name', $plan);
            })
            ->orderByRaw('subscription_ends_at IS NULL')
            ->orderBy('subscription_ends_at')
            ->paginate(10)
            ->withQueryString();

        $activeSubscriptions = User::where('role', 'owner')
            ->where('subscription_status', 'active')
            ->count();

        $trialSubscriptions = User::where('role', 'owner')
            ->where('subscription_status', 'trial')
            ->count();

        $expiredSubscriptions = User::where('role', 'owner')
            ->where(function ($query) {
                $query->where('subscription_status', 'expired')
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNotNull('subscription_ends_at')
                            ->where('subscription_ends_at', '<', now());
                    });
            })
            ->count();

        $expiredSoonSubscriptions = User::where('role', 'owner')
            ->whereNotNull('subscription_ends_at')
            ->whereBetween('subscription_ends_at', [
                now(),
                now()->addDays(7),
            ])
            ->count();

        $plans = User::where('role', 'owner')
            ->whereNotNull('plan_name')
            ->select('plan_name')
            ->distinct()
            ->orderBy('plan_name')
            ->pluck('plan_name');

        return view('admin.subscriptions.index', compact(
            'users',
            'search',
            'status',
            'plan',
            'plans',
            'activeSubscriptions',
            'trialSubscriptions',
            'expiredSubscriptions',
            'expiredSoonSubscriptions'
        ));
    }

    public function destroy(User $user)
    {
        abort_if($user->role !== 'owner', 404);

        DB::transaction(function () use ($user) {
            Sale::where('user_id', $user->id)->delete();
            Expense::where('user_id', $user->id)->delete();
            Customer::where('user_id', $user->id)->delete();
            Product::where('user_id', $user->id)->delete();

            $user->delete();
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun owner dan seluruh data bisnisnya berhasil dihapus.');
    }
}