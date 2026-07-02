<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Sistem Penanganan Kendala Parkir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #062b68;
            --primary-deep: #003b8f;
            --primary-soft: #eaf3ff;
            --text-dark: #071b4d;
            --text-muted: #5f719a;
            --border: #b9cbea;
            --danger: #dc3545;
            --card-shadow: 0 24px 55px rgba(15, 23, 42, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text-dark);
            background:
                radial-gradient(circle at 10% 20%, rgba(13, 110, 253, 0.12), transparent 32%),
                radial-gradient(circle at 88% 86%, rgba(13, 110, 253, 0.11), transparent 32%),
                linear-gradient(135deg, #f8fbff 0%, #edf6ff 45%, #f9fcff 100%);
            overflow: hidden;
        }

        .login-page {
            position: relative;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 38% 62%;
            align-items: center;
        }

        .bg-circle-left {
            position: absolute;
            width: 650px;
            height: 650px;
            left: -210px;
            top: -120px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.10), rgba(13, 110, 253, 0.02));
            z-index: 0;
        }

        .bg-wave-bottom {
            position: absolute;
            right: -160px;
            bottom: -240px;
            width: 880px;
            height: 380px;
            border-radius: 50% 50% 0 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.11), rgba(13, 110, 253, 0.03));
            transform: rotate(-8deg);
            z-index: 0;
        }

        .dot-pattern {
            position: absolute;
            width: 150px;
            height: 150px;
            background-image: radial-gradient(rgba(13, 110, 253, 0.34) 2px, transparent 2px);
            background-size: 22px 22px;
            opacity: 0.7;
            z-index: 0;
        }

        .dot-pattern.top {
            top: 82px;
            right: 74px;
        }

        .dot-pattern.bottom {
            bottom: 48px;
            left: 64px;
        }

        .illustration-panel {
            position: relative;
            z-index: 2;
            height: 100vh;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            overflow: hidden;
        }

        .illustration-scene {
            position: relative;
            width: 100%;
            height: 78%;
            min-height: 560px;
        }

        .cloud {
            position: absolute;
            background: #ffffff;
            border-radius: 999px;
            opacity: 0.95;
            filter: drop-shadow(0 10px 18px rgba(15, 23, 42, 0.03));
        }

        .cloud::before,
        .cloud::after {
            content: "";
            position: absolute;
            background: #ffffff;
            border-radius: 50%;
        }

        .cloud-one {
            width: 118px;
            height: 26px;
            top: 54px;
            left: 28px;
        }

        .cloud-one::before {
            width: 48px;
            height: 48px;
            left: 28px;
            top: -28px;
        }

        .cloud-one::after {
            width: 62px;
            height: 62px;
            left: 64px;
            top: -36px;
        }

        .cloud-two {
            width: 112px;
            height: 26px;
            top: 8px;
            right: 76px;
        }

        .cloud-two::before {
            width: 46px;
            height: 46px;
            left: 18px;
            top: -25px;
        }

        .cloud-two::after {
            width: 58px;
            height: 58px;
            left: 55px;
            top: -34px;
        }

        .building {
            position: absolute;
            bottom: 190px;
            background: rgba(85, 124, 173, 0.13);
        }

        .building.one {
            width: 52px;
            height: 92px;
            left: 20px;
        }

        .building.two {
            width: 86px;
            height: 56px;
            left: 105px;
        }

        .building.three {
            width: 58px;
            height: 105px;
            left: 230px;
        }

        .building.four {
            width: 68px;
            height: 160px;
            left: 315px;
        }

        .building.five {
            width: 72px;
            height: 82px;
            left: 410px;
        }

        .window {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.42);
            margin: 16px 0 0 14px;
            box-shadow:
                28px 0 rgba(255, 255, 255, 0.35),
                0 28px rgba(255, 255, 255, 0.35),
                28px 28px rgba(255, 255, 255, 0.28);
        }

        .ground {
            position: absolute;
            left: -80px;
            right: -20px;
            bottom: 0;
            height: 190px;
            background: linear-gradient(180deg, #a7bddb, #8daacf);
            border-top-left-radius: 62% 70px;
            border-top-right-radius: 48% 62px;
        }

        .road-arrow {
            position: absolute;
            bottom: 62px;
            left: 320px;
            width: 0;
            height: 0;
            border-left: 34px solid transparent;
            border-right: 34px solid transparent;
            border-bottom: 60px solid rgba(255, 255, 255, 0.88);
            transform: rotate(2deg);
        }

        .island {
            position: absolute;
            left: 0;
            bottom: 92px;
            width: 220px;
            height: 94px;
            background: #cbd8e8;
            border-radius: 0 18px 0 0;
            box-shadow: inset 0 -10px rgba(112, 139, 170, 0.16);
        }

        .island::after {
            content: "";
            position: absolute;
            left: 0;
            right: -50px;
            bottom: -20px;
            height: 24px;
            background: #a9bdd6;
            transform: skewX(-28deg);
            transform-origin: left bottom;
        }

        .plant {
            position: absolute;
            left: 18px;
            bottom: 92px;
            width: 80px;
            height: 82px;
        }

        .leaf {
            position: absolute;
            bottom: 0;
            width: 22px;
            height: 78px;
            border-radius: 28px 28px 0 0;
            transform-origin: bottom center;
        }

        .leaf.one {
            left: 4px;
            transform: rotate(-32deg);
            background: #2d648e;
        }

        .leaf.two {
            left: 22px;
            transform: rotate(-14deg);
            background: #3b7ead;
        }

        .leaf.three {
            left: 39px;
            transform: rotate(8deg);
            background: #2f6b99;
        }

        .leaf.four {
            left: 56px;
            transform: rotate(30deg);
            background: #3e84b5;
        }

        .parking-gate {
            position: absolute;
            left: 92px;
            bottom: 136px;
            width: 330px;
            height: 340px;
        }

        .sign-pole {
            position: absolute;
            width: 12px;
            height: 125px;
            left: 66px;
            top: 108px;
            background: #1d4774;
            border-radius: 8px;
            box-shadow: inset -2px 0 rgba(255, 255, 255, 0.22);
        }

        .sign-board {
            position: absolute;
            top: 0;
            left: 0;
            width: 138px;
            height: 118px;
            border-radius: 16px;
            background: linear-gradient(145deg, #1f6de2, #06478f);
            border: 5px solid #d8e8ff;
            outline: 4px solid #1e61c8;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: 0 18px 30px rgba(29, 71, 116, 0.25);
        }

        .sign-board .elite {
            font-size: 28px;
            line-height: 1;
            font-weight: 950;
            letter-spacing: -1px;
        }

        .sign-board .parkir {
            font-size: 25px;
            line-height: 1.05;
            font-weight: 500;
        }

        .gate-box {
            position: absolute;
            left: 40px;
            bottom: 0;
            width: 92px;
            height: 160px;
            border-radius: 10px 10px 4px 4px;
            background: linear-gradient(145deg, #5d89b3, #315d87);
            box-shadow: 0 16px 28px rgba(30, 70, 110, 0.26);
        }

        .gate-box::before {
            content: "";
            position: absolute;
            top: 60px;
            left: 24px;
            width: 44px;
            height: 34px;
            border-radius: 3px;
            background: #21466b;
            opacity: 0.86;
        }

        .gate-box::after {
            content: "P";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 36px;
            text-align: center;
            font-size: 34px;
            line-height: 1;
            font-weight: 900;
            color: #ffffff;
        }

        .barrier {
            position: absolute;
            left: 120px;
            bottom: 118px;
            width: 250px;
            height: 18px;
            border-radius: 999px;
            background:
                repeating-linear-gradient(
                    -16deg,
                    #ffffff 0 34px,
                    #d5222a 34px 58px
                );
            box-shadow: 0 12px 20px rgba(30, 70, 110, 0.22);
            transform: rotate(0deg);
            transform-origin: left center;
        }

        .login-card-wrap {
            position: relative;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 120px;
            padding-left: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 680px;
            min-height: 640px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(185, 203, 234, 0.75);
            border-radius: 18px;
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(16px);
            padding: 48px 54px;
        }

        .card-logo {
            width: 160px;
            height: 120px;
            border-radius: 8px;
            margin: 0 auto 28px;
            background: linear-gradient(145deg, #0b3969, #07264c);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: 0 16px 34px rgba(7, 38, 76, 0.28);
        }

        .card-logo .elite {
            font-size: 38px;
            line-height: 1;
            font-weight: 950;
            letter-spacing: 1px;
        }

        .card-logo .parkir {
            font-size: 34px;
            line-height: 1.05;
            font-weight: 400;
        }

        .login-title {
            text-align: center;
            color: #071b4d;
            font-size: 28px;
            font-weight: 950;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .login-subtitle {
            text-align: center;
            color: #536796;
            font-size: 17px;
            margin-bottom: 26px;
        }

        .divider {
            height: 1px;
            background: #c6d6ef;
            margin-bottom: 28px;
        }

        .form-label {
            color: #071b4d;
            font-size: 16px;
            font-weight: 850;
            margin-bottom: 10px;
        }

        .form-label i {
            color: var(--primary);
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #8ba1ca;
            font-size: 20px;
            z-index: 2;
        }

        .form-control {
            height: 60px;
            border-radius: 8px;
            border: 1.5px solid #b8c9ea;
            background: rgba(255, 255, 255, 0.86);
            padding-left: 58px;
            padding-right: 54px;
            color: #071b4d;
            font-size: 16px;
            font-weight: 650;
            transition: all 0.2s ease;
        }

        .form-control::placeholder {
            color: #6b7fae;
            font-weight: 500;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
            background: #ffffff;
        }

        .password-toggle {
            position: absolute;
            right: 17px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6b7fae;
            font-size: 20px;
            z-index: 3;
        }

        .btn-login {
            width: 100%;
            height: 60px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            color: #ffffff;
            font-size: 20px;
            font-weight: 850;
            box-shadow: 0 14px 26px rgba(13, 110, 253, 0.26);
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #0d63dd, #003f9d);
            color: #ffffff;
        }

        .alert {
            border: none;
            border-radius: 8px;
            font-weight: 650;
            padding: 16px 18px;
            margin-bottom: 22px;
        }

        .alert-danger {
            background: #fde8e8;
            color: #b4232a;
        }

        .alert-success {
            background: #e7f7ee;
            color: #146c43;
        }

        .alert-warning {
            background: #fff6dc;
            color: #946200;
        }

        .footer-note {
            margin-top: 28px;
            text-align: center;
            color: #8a9abc;
            font-size: 13px;
        }

        @media (max-width: 1200px) {
            .login-card-wrap {
                padding-right: 50px;
            }

            .login-card {
                max-width: 620px;
            }
        }

        @media (max-width: 992px) {
            body {
                overflow-y: auto;
            }

            .login-page {
                grid-template-columns: 1fr;
                padding: 28px;
            }

            .illustration-panel {
                display: none;
            }

            .login-card-wrap {
                padding: 0;
            }

            .login-card {
                max-width: 620px;
                min-height: auto;
                padding: 38px 30px;
            }
        }

        @media (max-width: 576px) {
            .login-page {
                padding: 16px;
            }

            .login-card {
                padding: 30px 22px;
                border-radius: 16px;
            }

            .card-logo {
                width: 130px;
                height: 96px;
            }

            .card-logo .elite {
                font-size: 31px;
            }

            .card-logo .parkir {
                font-size: 28px;
            }

            .login-title {
                font-size: 23px;
            }

            .login-subtitle {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="bg-circle-left"></div>
        <div class="bg-wave-bottom"></div>
        <div class="dot-pattern top"></div>
        <div class="dot-pattern bottom"></div>

        {{-- LEFT ILLUSTRATION --}}
        <section class="illustration-panel">
            <div class="illustration-scene">
                <div class="cloud cloud-one"></div>
                <div class="cloud cloud-two"></div>

                <div class="building one"><div class="window"></div></div>
                <div class="building two"><div class="window"></div></div>
                <div class="building three"><div class="window"></div></div>
                <div class="building four"><div class="window"></div></div>
                <div class="building five"><div class="window"></div></div>

                <div class="ground"></div>
                <div class="road-arrow"></div>

                <div class="island"></div>

                <div class="plant">
                    <div class="leaf one"></div>
                    <div class="leaf two"></div>
                    <div class="leaf three"></div>
                    <div class="leaf four"></div>
                </div>

                <div class="parking-gate">
                    <div class="sign-board">
                        <div class="elite">ELITE</div>
                        <div class="parkir">Parkir</div>
                    </div>
                    <div class="sign-pole"></div>
                    <div class="gate-box"></div>
                    <div class="barrier"></div>
                </div>
            </div>
        </section>

        {{-- LOGIN CARD --}}
        <section class="login-card-wrap">
            <div class="login-card">
                <div class="card-logo">
                    <div class="elite">ELITE</div>
                    <div class="parkir">Parkir</div>
                </div>

                <h1 class="login-title">Sistem Penanganan Kendala Parkir</h1>
                <div class="login-subtitle">Monitoring Operasional Parkir</div>

                <div class="divider"></div>

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-person-badge me-2"></i>
                            NIK
                        </label>

                        <div class="input-wrap">
                            <i class="bi bi-person input-icon"></i>
                            <input
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                class="form-control"
                                placeholder="Masukkan NIK Anda"
                                autocomplete="username"
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>
                            Password
                        </label>

                        <div class="input-wrap">
                            <i class="bi bi-lock input-icon"></i>
                            <input
                                type="password"
                                name="password"
                                id="passwordInput"
                                class="form-control"
                                placeholder="Masukkan password Anda"
                                autocomplete="current-password"
                            >

                            <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Tampilkan password">
                                <i class="bi bi-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Login
                    </button>
                </form>

                <div class="footer-note">
                    © 2026 ELITE Parkir. All rights reserved.
                </div>
            </div>
        </section>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('passwordToggleIcon');

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>