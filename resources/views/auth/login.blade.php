<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DagangFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .login-page {
            min-height: 100vh;
            background: #F8FAF7;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 430px;
        }

        .login-logo {
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

        .login-card {
            width: 100%;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 16px 45px rgba(15, 23, 42, 0.06);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-eyebrow {
            font-size: 13px;
            font-weight: 800;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .login-title {
            font-size: 32px;
            font-weight: 900;
            color: #0F172A;
            letter-spacing: -0.04em;
            margin-top: 10px;
        }

        .login-desc {
            font-size: 15px;
            color: #64748B;
            line-height: 1.6;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 700;
            color: #059669;
            text-decoration: none;
        }

        .form-input {
            width: 100%;
            height: 48px;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 0 16px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 4px 0 22px;
            font-size: 14px;
            color: #64748B;
        }

        .login-button {
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
        }

        .login-button:hover {
            background: #059669;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 26px 0;
            color: #94A3B8;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #E2E8F0;
        }

        .demo-button {
            width: 100%;
            height: 52px;
            border: 1px solid #E2E8F0;
            border-radius: 18px;
            background: white;
            color: #0F172A;
            font-size: 15px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.2s;
            box-sizing: border-box;
        }

        .demo-button:hover {
            background: #F8FAFC;
        }

        .register-text {
            text-align: center;
            font-size: 14px;
            color: #64748B;
            margin-top: 26px;
        }

        .register-text a {
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
            .login-card {
                padding: 28px 22px;
                border-radius: 24px;
            }

            .login-title {
                font-size: 28px;
            }

            .logo-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <main class="login-page">
        <div class="login-wrapper">

            <a href="/" class="login-logo">
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

            <div class="login-card">
                <div class="login-header">
                    <div class="login-eyebrow">Welcome Back</div>
                    <h1 class="login-title">Masuk ke akunmu</h1>
                    <p class="login-desc">
                        Gunakan email dan password untuk masuk ke dashboard DagangFlow.
                    </p>
                </div>

                @if ($errors->any())
                    <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C; padding: 12px 14px; border-radius: 16px; font-size: 14px; margin-bottom: 18px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="/login" method="POST">
                    @csrf

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
                        <div class="form-row">
                            <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                            <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                        </div>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Masukkan password"
                            class="form-input"
                        >
                    </div>

                    <label class="remember-row">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    <button type="submit" class="login-button">
                        Masuk Dashboard
                    </button>
                </form>

                <div class="divider">atau</div>

                <a href="/dashboard" class="demo-button">
                    Masuk sebagai demo
                </a>

                <p class="register-text">
                    Belum punya akun?
                    <a href="/register">Buat akun baru</a>
                </p>
            </div>

            <p class="footer-text">
                © 2026 DagangFlow. Multi-channel sales tracker for UMKM.
            </p>

        </div>
    </main>
</body>
</html>