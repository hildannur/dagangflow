<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

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

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest');

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

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Protected Dashboard Pages
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('/sales', SaleController::class)->except(['create', 'show', 'edit']);

    Route::resource('/products', ProductController::class)->except(['create', 'show', 'edit']);

    Route::resource('/expenses', ExpenseController::class)->except(['create', 'show', 'edit']);

    Route::resource('/customers', CustomerController::class)->except(['create', 'show', 'edit']);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/ai-insight', [ReportController::class, 'generateAiInsight'])->name('reports.ai-insight');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::view('/help', 'help')->name('help');
});