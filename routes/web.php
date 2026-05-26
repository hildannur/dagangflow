<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\SaleImportController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Auth Pages
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->middleware('guest');

Route::post('/demo-login', function (Request $request) {
    $demoUser = User::firstOrCreate(
        ['email' => 'demo@dagangflow.test'],
        [
            'name' => 'Demo Owner',
            'business_name' => 'Kopi Demo Nusantara',
            'business_type' => 'Food & Beverage',
            'password' => Hash::make(Str::random(40)),
        ]
    );

    if (Product::where('user_id', $demoUser->id)->count() === 0) {
        $kopiSusu = Product::create([
            'user_id' => $demoUser->id,
            'name' => 'Kopi Susu Aren',
            'category' => 'Minuman',
            'selling_price' => 18000,
            'cost_price' => 9000,
            'stock' => 120,
            'low_stock_limit' => 10,
        ]);

        $esCoklat = Product::create([
            'user_id' => $demoUser->id,
            'name' => 'Es Coklat Premium',
            'category' => 'Minuman',
            'selling_price' => 16000,
            'cost_price' => 7500,
            'stock' => 85,
            'low_stock_limit' => 10,
        ]);

        $rotiBakar = Product::create([
            'user_id' => $demoUser->id,
            'name' => 'Roti Bakar Coklat Keju',
            'category' => 'Makanan',
            'selling_price' => 22000,
            'cost_price' => 11000,
            'stock' => 60,
            'low_stock_limit' => 8,
        ]);

        $demoSales = [
            [$kopiSusu, 'Shopee', 8, 6000, 'Selesai', now()->subDays(1)->toDateString()],
            [$kopiSusu, 'GrabFood', 5, 7500, 'Selesai', now()->subDays(2)->toDateString()],
            [$esCoklat, 'TikTok Shop', 6, 5000, 'Selesai', now()->subDays(3)->toDateString()],
            [$rotiBakar, 'WhatsApp', 4, 0, 'Selesai', now()->subDays(4)->toDateString()],
            [$esCoklat, 'Offline', 7, 0, 'Selesai', now()->subDays(5)->toDateString()],
            [$kopiSusu, 'Website', 3, 0, 'Selesai', now()->subDays(6)->toDateString()],
        ];

        foreach ($demoSales as [$product, $channel, $quantity, $platformFee, $status, $saleDate]) {
            $grossTotal = $product->selling_price * $quantity;
            $netTotal = $grossTotal - $platformFee;

            Sale::create([
                'user_id' => $demoUser->id,
                'product_id' => $product->id,
                'channel' => $channel,
                'quantity' => $quantity,
                'selling_price' => $product->selling_price,
                'gross_total' => $grossTotal,
                'platform_fee' => $platformFee,
                'net_total' => $netTotal,
                'status' => $status,
                'note' => 'Data contoh akun demo',
                'sale_date' => $saleDate,
            ]);

            $product->decrement('stock', $quantity);
        }

        Expense::create([
            'user_id' => $demoUser->id,
            'category' => 'Bahan Baku',
            'amount' => 350000,
            'payment_method' => 'Transfer',
            'note' => 'Belanja bahan kopi, susu, coklat, dan roti',
            'expense_date' => now()->subDays(2)->toDateString(),
        ]);

        Expense::create([
            'user_id' => $demoUser->id,
            'category' => 'Packaging',
            'amount' => 150000,
            'payment_method' => 'Cash',
            'note' => 'Cup, plastik, sedotan, dan stiker',
            'expense_date' => now()->subDays(3)->toDateString(),
        ]);

        Expense::create([
            'user_id' => $demoUser->id,
            'category' => 'Iklan',
            'amount' => 200000,
            'payment_method' => 'E-Wallet',
            'note' => 'Promosi marketplace dan konten sosial media',
            'expense_date' => now()->subDays(4)->toDateString(),
        ]);

        Expense::create([
            'user_id' => $demoUser->id,
            'category' => 'Operasional',
            'amount' => 125000,
            'payment_method' => 'Cash',
            'note' => 'Biaya operasional harian toko',
            'expense_date' => now()->subDays(5)->toDateString(),
        ]);
    }

    Auth::login($demoUser);

    $request->session()->regenerate();

    return redirect('/dashboard')->with('success', 'Kamu sedang masuk sebagai akun demo read-only.');
})->middleware('guest')->name('demo.login');

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'business_name' => ['nullable', 'string', 'max:255'],
        'business_type' => ['nullable', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'min:8'],
    ], [
        'email.unique' => 'Akun sudah terdaftar',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 8 karakter',
        'name.required' => 'Nama owner wajib diisi',
    ]);

    $user = User::create([
        'name' => $data['name'],
        'business_name' => $data['business_name'] ?? null,
        'business_type' => $data['business_type'] ?? null,
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);

    $request->session()->regenerate();

    return redirect('/dashboard');
})->middleware('guest');

/*
|--------------------------------------------------------------------------
| Password Reset Pages
|--------------------------------------------------------------------------
| Dibuat tanpa middleware guest agar bisa diakses dari halaman Biodata
| ketika user masih login.
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return back()->with('status', 'Link reset password sudah dikirim ke email jika akun terdaftar.');
    }

    return back()->withErrors([
        'email' => 'Reset gagal. Status Laravel: ' . $status,
    ]);
})->name('password.email');

Route::get('/reset-password/{token}', function (string $token, Request $request) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email'),
    ]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak sesuai.',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect('/login')->with('status', 'Password berhasil diubah. Silakan login dengan password baru.')
        : back()->withErrors(['email' => 'Token reset tidak valid atau sudah kedaluwarsa.']);
})->name('password.update');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Dashboard Pages
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'demo.readonly'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/biodata', [BiodataController::class, 'index'])->name('biodata.index');
    Route::put('/biodata/profile', [BiodataController::class, 'updateProfile'])->name('biodata.profile.update');
    Route::put('/biodata/password', [BiodataController::class, 'updatePassword'])->name('biodata.password.update');

    Route::get('/sales/export', [SaleController::class, 'export'])->name('sales.export');
    Route::get('/sales/import-template', [SaleImportController::class, 'downloadTemplate'])->name('sales.import-template');
    Route::post('/sales/import', [SaleImportController::class, 'import'])->name('sales.import');
    Route::resource('sales', SaleController::class)->except(['create', 'show', 'edit']);

    Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);

    Route::resource('expenses', ExpenseController::class)->except(['create', 'show', 'edit']);

    Route::resource('customers', CustomerController::class)->except(['create', 'show', 'edit']);

    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/ai-insight', [ReportController::class, 'generateAiInsight'])->name('reports.ai-insight');

    Route::view('/help', 'help')->name('help');
});