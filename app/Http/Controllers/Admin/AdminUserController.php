<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    private array $availablePlans = [
        'Free',
        'Trial',
        'Bulanan',
        'Tahunan',
    ];

    private array $availableSubscriptionStatuses = [
        'trial',
        'active',
        'expired',
        'cancelled',
        'suspended',
    ];

    private function normalizePlanName(?string $planName): string
    {
        return match ($planName) {
            'Trial' => 'Trial',
            'Bulanan' => 'Bulanan',
            'Tahunan' => 'Tahunan',

            'Starter' => 'Free',
            'Pro' => 'Bulanan',
            'Business' => 'Tahunan',
            'Internal' => 'Tahunan',

            default => 'Free',
        };
    }

    private function normalizeSubscriptionStatus(?string $status): string
    {
        return in_array($status, $this->availableSubscriptionStatuses, true)
            ? $status
            : 'active';
    }

    private function applyPlanFilter($query, ?string $plan): void
    {
        if (!$plan) {
            return;
        }

        if ($plan === 'Free') {
            $query->where(function ($subQuery) {
                $subQuery->where('plan_name', 'Free')
                    ->orWhere('plan_name', 'Starter')
                    ->orWhereNull('plan_name');
            });

            return;
        }

        if ($plan === 'Bulanan') {
            $query->whereIn('plan_name', ['Bulanan', 'Pro']);

            return;
        }

        if ($plan === 'Tahunan') {
            $query->whereIn('plan_name', ['Tahunan', 'Business', 'Internal']);

            return;
        }

        $query->where('plan_name', $plan);
    }

    public function usersData(Request $request)
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
                $this->applyPlanFilter($query, $plan);
            })
            ->latest()
            ->paginate(10);

        return response()
            ->json([
                'data' => $users->through(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'business_name' => $user->business_name,
                        'business_type' => $user->business_type,
                        'plan_name' => $this->normalizePlanName($user->plan_name),
                        'subscription_status' => $this->normalizeSubscriptionStatus($user->subscription_status),
                        'subscription_ends_at' => $user->subscription_ends_at
                            ? $user->subscription_ends_at->format('d M Y')
                            : null,
                        'last_login_at' => $user->last_login_at
                            ? $user->last_login_at->format('d M Y H:i')
                            : null,
                        'status' => $user->status ?: '-',
                        'created_at' => $user->created_at
                            ? $user->created_at->format('d M Y')
                            : null,
                    ];
                })->items(),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                    'total' => $users->total(),
                ],
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

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
                $this->applyPlanFilter($query, $plan);
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

        $plans = $this->availablePlans;

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

        $user->forceFill([
            'status' => 'suspended',
        ])->save();

        return back()->with('success', 'User berhasil disuspend.');
    }

    public function activate(User $user)
    {
        abort_if($user->role !== 'owner', 404);

        $user->forceFill([
            'status' => 'active',
        ])->save();

        return back()->with('success', 'User berhasil diaktifkan kembali.');
    }

    public function updateSubscription(Request $request, User $user)
    {
        abort_if($user->role !== 'owner', 404);

        $data = $request->validate([
            'plan_name' => ['required', 'string', 'in:Free,Trial,Bulanan,Tahunan'],
            'subscription_status' => ['required', 'string', 'in:trial,active,expired,cancelled,suspended'],
            'subscription_started_at' => ['nullable', 'date'],
            'subscription_ends_at' => ['nullable', 'date'],
        ], [
            'plan_name.required' => 'Paket wajib dipilih.',
            'plan_name.in' => 'Paket yang dipilih tidak valid.',
            'subscription_status.required' => 'Status subscription wajib dipilih.',
            'subscription_status.in' => 'Status subscription tidak valid.',
            'subscription_started_at.date' => 'Tanggal mulai tidak valid.',
            'subscription_ends_at.date' => 'Tanggal berakhir tidak valid.',
        ]);

        $user->forceFill([
            'plan_name' => $data['plan_name'],
            'subscription_status' => $data['subscription_status'],
            'subscription_started_at' => $data['subscription_started_at'] ?: now(),
            'subscription_ends_at' => $data['subscription_ends_at'] ?: null,
        ])->save();

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Subscription user berhasil diperbarui.');
    }

    public function extendSubscription(Request $request, User $user)
    {
        abort_if($user->role !== 'owner', 404);

        $data = $request->validate([
            'days' => ['required', 'integer', 'in:14,30,365'],
        ], [
            'days.required' => 'Jumlah hari wajib dipilih.',
            'days.integer' => 'Jumlah hari harus berupa angka.',
            'days.in' => 'Pilihan perpanjangan tidak valid.',
        ]);

        $days = (int) $data['days'];

        $planName = match ($days) {
            14 => 'Trial',
            30 => 'Bulanan',
            365 => 'Tahunan',
        };

        $subscriptionStatus = match ($days) {
            14 => 'trial',
            30, 365 => 'active',
        };

        $currentEndDate = $user->subscription_ends_at && $user->subscription_ends_at->isFuture()
            ? $user->subscription_ends_at
            : now();

        $user->forceFill([
            'plan_name' => $planName,
            'subscription_status' => $subscriptionStatus,
            'subscription_started_at' => $user->subscription_started_at ?: now(),
            'subscription_ends_at' => $currentEndDate->copy()->addDays($days),
        ])->save();

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Paket user berhasil diubah ke {$planName} dan masa aktif diperpanjang {$days} hari.");
    }

    public function subscriptionsData(Request $request)
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
                $query->where('subscription_status', $status);
            })
            ->when($plan, function ($query) use ($plan) {
                $this->applyPlanFilter($query, $plan);
            })
            ->orderByRaw('subscription_ends_at IS NULL')
            ->orderBy('subscription_ends_at')
            ->paginate(10);

        return response()
            ->json([
                'data' => $users->through(function ($user) {
                    $daysLeft = $user->subscription_ends_at
                        ? now()->startOfDay()->diffInDays($user->subscription_ends_at->copy()->startOfDay(), false)
                        : null;

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'business_name' => $user->business_name,
                        'business_type' => $user->business_type,
                        'plan_name' => $this->normalizePlanName($user->plan_name),
                        'subscription_status' => $this->normalizeSubscriptionStatus($user->subscription_status),
                        'subscription_started_at' => $user->subscription_started_at
                            ? $user->subscription_started_at->format('d M Y')
                            : null,
                        'subscription_ends_at' => $user->subscription_ends_at
                            ? $user->subscription_ends_at->format('d M Y')
                            : null,
                        'days_left' => $daysLeft,
                    ];
                })->items(),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                    'total' => $users->total(),
                ],
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
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
                        ->orWhere('business_name', 'like', "%{$search}%")
                        ->orWhere('business_type', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('subscription_status', $status);
            })
            ->when($plan, function ($query) use ($plan) {
                $this->applyPlanFilter($query, $plan);
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

        $plans = $this->availablePlans;

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