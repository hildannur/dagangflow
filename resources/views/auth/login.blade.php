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

        .password-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 58px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: none;
            background: #F1F5F9;
            color: #475569;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        .password-toggle svg {
            display: block;
        }

        .hidden {
            display: none;
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
            cursor: pointer;
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

                @if (session('status'))
                    <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; padding: 12px 14px; border-radius: 16px; font-size: 14px; margin-bottom: 18px;">
                        {{ session('status') }}
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
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="form-input"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                            <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                        </div>

                        <div class="password-wrapper">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Masukkan password"
                                class="form-input password-input"
                                required
                            >

                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password', this)" aria-label="Lihat password">
                                <span class="eye-open">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </span>

                                <span class="eye-closed hidden">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M10.6 10.6A2 2 0 0 0 13.4 13.4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M9.9 5.2A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a18.5 18.5 0 0 1-2.1 3.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.6 6.7C3.7 8.6 2 12 2 12s3.5 7 10 7a10.4 10.4 0 0 0 4.1-.8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
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

                <form action="{{ route('demo.login') }}" method="POST">
                    @csrf

                    <button type="submit" class="demo-button">
                        Masuk sebagai demo
                    </button>
                </form>

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

    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);

            if (!input || !button) return;

            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen?.classList.add('hidden');
                eyeClosed?.classList.remove('hidden');
                button.setAttribute('aria-label', 'Tutup password');
            } else {
                input.type = 'password';
                eyeOpen?.classList.remove('hidden');
                eyeClosed?.classList.add('hidden');
                button.setAttribute('aria-label', 'Lihat password');
            }
        }
    </script>
</body>
</html>