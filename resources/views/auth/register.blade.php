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

        .password-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 78px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: none;
            background: #F1F5F9;
            color: #475569;
            font-size: 12px;
            font-weight: 800;
            padding: 7px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        .password-toggle:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        .form-help {
            font-size: 12px;
            color: #64748B;
            margin-top: 8px;
            line-height: 1.5;
        }

        .hidden {
            display: none;
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
                            value="{{ old('name') }}"
                            placeholder="Contoh: Nizar"
                            class="form-input"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="business_name" class="form-label">Nama bisnis</label>
                        <input
                            id="business_name"
                            name="business_name"
                            type="text"
                            value="{{ old('business_name') }}"
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
                            <option value="food_beverage" @selected(old('business_type') === 'food_beverage')>Food & Beverage</option>
                            <option value="fashion" @selected(old('business_type') === 'fashion')>Fashion</option>
                            <option value="beauty" @selected(old('business_type') === 'beauty')>Beauty</option>
                            <option value="retail" @selected(old('business_type') === 'retail')>Retail</option>
                            <option value="service" @selected(old('business_type') === 'service')>Jasa</option>
                            <option value="other" @selected(old('business_type') === 'other')>Lainnya</option>
                        </select>

                        <div id="custom_business_type_wrapper" class="form-group hidden" style="margin-top: 14px; margin-bottom: 0;">
                            <label for="custom_business_type" class="form-label">Keterangan jenis usaha</label>
                            <input
                                id="custom_business_type"
                                name="custom_business_type"
                                type="text"
                                value="{{ old('custom_business_type') }}"
                                placeholder="Contoh: Laundry, Barbershop, Percetakan"
                                class="form-input"
                            >

                            <p class="form-help">
                                Isi jenis usaha kamu jika tidak tersedia di pilihan.
                            </p>
                        </div>
                    </div>

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
                        <label for="password" class="form-label">Password</label>

                        <div class="password-wrapper">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Minimal 8 karakter"
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

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi password</label>

                        <div class="password-wrapper">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                placeholder="Ulangi password"
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

    <script>
        const businessTypeSelect = document.getElementById('business_type');
        const customBusinessTypeWrapper = document.getElementById('custom_business_type_wrapper');
        const customBusinessTypeInput = document.getElementById('custom_business_type');

        function toggleCustomBusinessType() {
            if (!businessTypeSelect || !customBusinessTypeWrapper || !customBusinessTypeInput) return;

            if (businessTypeSelect.value === 'other') {
                customBusinessTypeWrapper.classList.remove('hidden');
                customBusinessTypeInput.setAttribute('required', 'required');
            } else {
                customBusinessTypeWrapper.classList.add('hidden');
                customBusinessTypeInput.removeAttribute('required');
                customBusinessTypeInput.value = '';
            }
        }

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);

            if (!input || !button) return;

            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = 'Tutup';
            } else {
                input.type = 'password';
                button.textContent = 'Lihat';
            }
        }

        toggleCustomBusinessType();

        businessTypeSelect?.addEventListener('change', toggleCustomBusinessType);
    </script>
</body>
</html>