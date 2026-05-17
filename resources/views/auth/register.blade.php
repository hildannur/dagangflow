<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DagangFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .register-page {
            min-height: 100vh;
            background: #F8FAF7;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .error-alert {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #B91C1C;
            padding: 12px 14px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 18px;
            text-align: center;
        }

        .register-wrapper {
            width: 100%;
            max-width: 460px;
        }

        .register-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            text-decoration: none;
        }

        .logo-box {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: #0F172A;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10B981;
            font-weight: 900;
            font-size: 22px;
        }

        .logo-title {
            font-size: 26px;
            font-weight: 900;
            line-height: 1;
            color: #0F172A;
            letter-spacing: -0.04em;
        }

        .logo-title span {
            color: #10B981;
        }

        .logo-subtitle {
            font-size: 14px;
            color: #64748B;
            margin-top: 4px;
        }

        .register-card {
            width: 100%;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 16px 45px rgba(15, 23, 42, 0.06);
        }

        .register-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-eyebrow {
            font-size: 13px;
            font-weight: 800;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .register-title {
            font-size: 32px;
            font-weight: 900;
            color: #0F172A;
            letter-spacing: -0.04em;
            margin-top: 10px;
        }

        .register-desc {
            font-size: 15px;
            color: #64748B;
            line-height: 1.6;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-input,
        .form-select {
            width: 100%;
            height: 48px;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 0 16px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
            box-sizing: border-box;
            background: white;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
        }

        .register-button {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 18px;
            background: #10B981;
            color: white;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.22);
            transition: 0.2s;
            margin-top: 6px;
        }

        .register-button:hover {
            background: #059669;
        }

        .auth-switch {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #64748B;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        .auth-switch a {
            color: #059669;
            font-weight: 800;
            text-decoration: none;
        }

        .auth-switch a:hover {
            color: #047857;
        }

        .login-text a {
            color: #059669;
            font-weight: 800;
            text-decoration: none;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #94A3B8;
            margin-top: 22px;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 28px 22px;
                border-radius: 24px;
            }

            .register-title {
                font-size: 28px;
            }

            .logo-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <main class="register-page">
        <div class="register-wrapper">

            <a href="/" class="register-logo">
                <div class="logo-box">D</div>
                <div>
                    <div class="logo-title">
                        Dagang<span>Flow</span>
                    </div>
                    <div class="logo-subtitle">
                        Business Dashboard
                    </div>
                </div>
            </a>

            <div class="register-card">
                <div class="register-header">
                    <div class="register-eyebrow">Create Account</div>
                    <h1 class="register-title">Buat akun baru</h1>
                    <p class="register-desc">
                        Daftarkan bisnis kamu dan mulai rapikan penjualan dari semua channel.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="error-alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="/register" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Nama owner</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            placeholder="Contoh: Nizar"
                            class="form-input"
                        >
                    </div>

                    <div class="form-group">
                        <label for="business_name" class="form-label">Nama bisnis</label>
                        <input
                            id="business_name"
                            name="business_name"
                            type="text"
                            placeholder="Contoh: Kopi Rumahan"
                            class="form-input"
                        >
                    </div>

                    <div class="form-group">
                        <label for="business_type" class="form-label">Jenis usaha</label>
                        <select
                            id="business_type"
                            name="business_type"
                            class="form-select"
                        >
                            <option value="">Pilih jenis usaha</option>
                            <option value="food_beverage">Food & Beverage</option>
                            <option value="fashion">Fashion</option>
                            <option value="beauty">Beauty</option>
                            <option value="retail">Retail</option>
                            <option value="service">Jasa</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="nama@email.com"
                            class="form-input"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Minimal 8 karakter"
                            class="form-input"
                        >
                    </div>

                    <button type="submit" class="register-button">
                        Buat Akun & Masuk
                    </button>
                </form>

                               <div class="auth-switch">
                    <span>Sudah punya akun?</span>
                    <a href="/login">Login di sini</a>
                </div>
            </div>

            <p class="footer-text">
                © 2026 DagangFlow. Multi-channel sales tracker for UMKM.
            </p>

        </div>
    </main>
</body>
</html>