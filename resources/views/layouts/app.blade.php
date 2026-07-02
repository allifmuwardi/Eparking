<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Penanganan Kendala Parkir')</title>
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
            --border: #d7e3f7;
            --body-bg: #f5f8fc;
            --sidebar-width: 286px;
            --topbar-height: 82px;
            --card-shadow: 0 18px 42px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background:
                radial-gradient(circle at 8% 12%, rgba(13, 110, 253, 0.08), transparent 28%),
                radial-gradient(circle at 92% 86%, rgba(13, 110, 253, 0.08), transparent 30%),
                linear-gradient(135deg, #f8fbff 0%, #edf6ff 45%, #f9fcff 100%);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 18px;
            left: 18px;
            bottom: 18px;
            width: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(185, 203, 234, 0.72);
            border-radius: 22px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 0 22px 52px rgba(15, 23, 42, 0.10);
            backdrop-filter: blur(16px);
            overflow: hidden;
        }

        .sidebar-brand {
            min-height: 92px;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 22px 22px 18px;
            border-bottom: 1px solid rgba(215, 227, 247, 0.85);
            background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.10), transparent 42%),
                linear-gradient(180deg, #ffffff, #f8fbff);
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(145deg, #0b3969, #07264c);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: 0 14px 28px rgba(7, 38, 76, 0.24);
            flex-shrink: 0;
        }

        .brand-logo .elite {
            font-size: 15px;
            line-height: 1;
            font-weight: 950;
            letter-spacing: 0.3px;
        }

        .brand-logo .parkir {
            font-size: 13px;
            line-height: 1.05;
            font-weight: 500;
        }

        .brand-title {
            font-size: 21px;
            font-weight: 950;
            color: var(--primary-dark);
            line-height: 1.1;
            letter-spacing: -0.2px;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 600;
        }

        .sidebar-menu {
            flex: 1;
            padding: 18px 13px;
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #cbdaf2;
            border-radius: 999px;
        }

        .menu-label {
            font-size: 11px;
            font-weight: 900;
            color: #8a9abc;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 20px 13px 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 7px;
            border-radius: 14px;
            color: #21345f;
            font-weight: 750;
            font-size: 14px;
            transition: all 0.18s ease;
            border: 1px solid transparent;
        }

        .menu-item i {
            width: 24px;
            height: 24px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1f6de2;
            font-size: 16px;
            background: #eef6ff;
            flex-shrink: 0;
        }

        .menu-item:hover {
            background: #f3f8ff;
            color: var(--primary-deep);
            border-color: #dbeafe;
            transform: translateX(3px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            color: #ffffff;
            box-shadow: 0 14px 26px rgba(13, 110, 253, 0.24);
        }

        .menu-item.active i {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.18);
        }

        .sidebar-info {
            margin: 0 13px 14px;
            padding: 16px;
            border-radius: 18px;
            background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 40%),
                linear-gradient(180deg, #f8fbff, #ffffff);
            border: 1px solid #dbeafe;
        }

        .sidebar-info-icon {
            width: 42px;
            height: 42px;
            border-radius: 15px;
            background: linear-gradient(145deg, #0d6efd, #003b8f);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 12px;
            box-shadow: 0 12px 24px rgba(13, 110, 253, 0.22);
        }

        .sidebar-info-title {
            font-size: 13px;
            font-weight: 900;
            color: var(--primary-dark);
            margin-bottom: 4px;
        }

        .sidebar-info-text {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.45;
            margin: 0;
        }

        .topbar {
            position: fixed;
            top: 18px;
            left: calc(var(--sidebar-width) + 36px);
            right: 18px;
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(185, 203, 234, 0.72);
            border-radius: 22px;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(16px);
        }

        .topbar-title h1 {
            margin: 0;
            color: var(--primary-dark);
            font-size: 23px;
            font-weight: 950;
            letter-spacing: -0.2px;
        }

        .topbar-title p {
            margin: 5px 0 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-search {
            width: 310px;
            height: 46px;
            border-radius: 14px;
            border: 1px solid #d7e3f7;
            background: #f8fbff;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 15px;
            color: #7d91bd;
        }

        .top-search input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            color: #6b7fae;
            font-size: 13px;
            font-weight: 650;
        }

        .notification-btn {
            position: relative;
            width: 46px;
            height: 46px;
            border-radius: 14px;
            border: 1px solid #d7e3f7;
            background: #ffffff;
            transition: all 0.18s ease;
        }

        .notification-btn:hover {
            background: #f3f8ff;
            border-color: #c6d6ef;
            transform: translateY(-1px);
        }

        .notification-btn i {
            font-size: 20px;
            color: #071b4d;
        }

        .notification-count {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 21px;
            height: 21px;
            padding: 0 6px;
            border-radius: 999px;
            background: #dc3545;
            color: #ffffff;
            font-size: 11px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }

        .user-profile {
            min-width: 210px;
            height: 50px;
            border-radius: 16px;
            border: 1px solid #d7e3f7;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 7px 12px 7px 7px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 13px;
            background: linear-gradient(145deg, #0b3969, #07264c);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 950;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 8px 18px rgba(7, 38, 76, 0.18);
        }

        .user-avatar img {
            width: 36px;
            height: 36px;
            object-fit: cover;
        }

        .user-name {
            font-size: 13px;
            font-weight: 900;
            color: #071b4d;
            line-height: 1.1;
            max-width: 132px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: #6b7fae;
            font-weight: 700;
            margin-top: 3px;
        }

        .logout-btn {
            height: 46px;
            border: none;
            border-radius: 14px;
            padding: 0 16px;
            background: #fde8e8;
            color: #b4232a;
            font-weight: 900;
            transition: all 0.18s ease;
        }

        .logout-btn:hover {
            background: #dc3545;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .content-wrapper {
            margin-left: calc(var(--sidebar-width) + 18px);
            padding-top: calc(var(--topbar-height) + 36px);
            min-height: 100vh;
        }

        .main-content {
            padding: 24px 18px 24px 18px;
            min-height: calc(100vh - var(--topbar-height) - 94px);
        }

        .page-card {
            background: rgba(255, 255, 255, 0.94);
            border-radius: 20px;
            border: 1px solid rgba(185, 203, 234, 0.72);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(14px);
        }

        .footer {
            height: 54px;
            color: #8a9abc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 650;
        }

        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
            font-weight: 650;
        }

        .alert-success {
            background: #e7f7ee;
            color: #146c43;
        }

        .alert-warning {
            background: #fff6dc;
            color: #946200;
        }

        .alert-danger {
            background: #fde8e8;
            color: #b4232a;
        }

        .toast {
            border-radius: 18px !important;
            overflow: hidden;
        }

        @media (max-width: 1200px) {
            .top-search {
                display: none;
            }

            .user-profile {
                min-width: 170px;
            }
        }

        @media (max-width: 992px) {
            :root {
                --sidebar-width: 0px;
            }

            body {
                overflow-x: hidden;
            }

            .sidebar {
                position: relative;
                top: auto;
                left: auto;
                bottom: auto;
                width: calc(100% - 32px);
                height: auto;
                margin: 16px;
                border-radius: 20px;
            }

            .sidebar-menu {
                max-height: none;
            }

            .sidebar-info {
                display: none;
            }

            .topbar {
                position: relative;
                top: auto;
                left: auto;
                right: auto;
                margin: 0 16px 16px;
                height: auto;
                min-height: 82px;
                padding: 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .topbar-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .content-wrapper {
                margin-left: 0;
                padding-top: 0;
            }

            .main-content {
                padding: 16px;
            }

            .logout-btn {
                width: auto;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                margin: 12px;
                width: calc(100% - 24px);
            }

            .sidebar-brand {
                padding: 18px;
            }

            .brand-title {
                font-size: 18px;
            }

            .topbar {
                margin: 0 12px 12px;
            }

            .topbar-title h1 {
                font-size: 19px;
            }

            .user-profile {
                width: 100%;
            }

            .logout-btn {
                width: 100%;
            }

            .notification-btn {
                width: 44px;
                height: 44px;
            }
        }
    </style>
</head>
<body>
    @php
        $authUser = Auth::user();
        $role = $authUser->role ?? '';

        $unreadNotificationCount = 0;

        if (Auth::check()) {
            $unreadNotificationCount = \App\Models\Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }

        $displayName = $authUser->full_name ?? $authUser->name ?? 'User';
        $initial = strtoupper(substr($displayName, 0, 1));

        $roleLabel = match ($role) {
            'petugas' => 'Petugas Parkir',
            'teknisi' => 'Teknisi Vendor',
            'manajer' => 'Manajer Operasional',
            'admin' => 'Admin Operasional',
            default => 'Pengguna',
        };

        $profilePhoto = $authUser->profile_photo ?? null;
    @endphp

    <div class="app-shell">
        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <div class="elite">ELITE</div>
                    <div class="parkir">Parkir</div>
                </div>

                <div>
                    <div class="brand-title">ELITE Parkir</div>
                    <div class="brand-subtitle">Operational Parking System</div>
                </div>
            </div>

            <div class="sidebar-menu">
                <div class="menu-label">Menu Utama</div>

                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                {{-- PETUGAS PARKIR --}}
                @if ($role === 'petugas')
                    <a href="{{ route('issue-reports.index') }}" class="menu-item {{ request()->routeIs('issue-reports.*') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Pelaporan Kendala</span>
                    </a>

                    <a href="{{ route('traffic-reports.index') }}" class="menu-item {{ request()->routeIs('traffic-reports.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Traffic Harian</span>
                    </a>

                    <a href="{{ route('backup-requests.index') }}" class="menu-item {{ request()->routeIs('backup-requests.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Permintaan Backup</span>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifikasi</span>
                    </a>
                @endif

                {{-- TEKNISI VENDOR --}}
                @if ($role === 'teknisi')
                    <a href="{{ route('technician-reports.index') }}" class="menu-item {{ request()->routeIs('technician-reports.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Laporan Ditugaskan</span>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifikasi</span>
                    </a>
                @endif

                {{-- ADMIN OPERASIONAL --}}
                @if ($role === 'admin')
                    <a href="{{ route('backup-requests.index') }}" class="menu-item {{ request()->routeIs('backup-requests.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Permintaan Backup</span>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifikasi</span>
                    </a>

                    <div class="menu-label">Manajemen Pengguna</div>

                    <a href="{{ route('user-management.index') }}" class="menu-item {{ request()->routeIs('user-management.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Kelola Akun Pengguna</span>
                    </a>

                    <div class="menu-label">Master Data</div>

                    <a href="{{ route('parking-locations.index') }}" class="menu-item {{ request()->routeIs('parking-locations.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i>
                        <span>Master Lokasi Parkir</span>
                    </a>

                    <a href="{{ route('backup-items.index') }}" class="menu-item {{ request()->routeIs('backup-items.*') ? 'active' : '' }}">
                        <i class="bi bi-box"></i>
                        <span>Master Barang Backup</span>
                    </a>
                @endif

                {{-- MANAJER OPERASIONAL --}}
                @if ($role === 'manajer')
                    <a href="{{ route('manage-reports.index') }}" class="menu-item {{ request()->routeIs('manage-reports.*') ? 'active' : '' }}">
                        <i class="bi bi-folder2-open"></i>
                        <span>Manage Report</span>
                    </a>

                    <a href="{{ route('backup-requests.index') }}" class="menu-item {{ request()->routeIs('backup-requests.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Permintaan Backup</span>
                    </a>

                    <a href="{{ route('report-recaps.index') }}" class="menu-item {{ request()->routeIs('report-recaps.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Laporan Rekap</span>
                    </a>

                    <div class="menu-label">Monitoring</div>

                    <a href="{{ route('user-management.index') }}" class="menu-item {{ request()->routeIs('user-management.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Data Pengguna Operasional</span>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifikasi</span>
                    </a>
                @endif

                <div class="menu-label">Akun</div>

                <a href="{{ route('profile.index') }}" class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil Saya</span>
                </a>
            </div>

            <div class="sidebar-info">
                <div class="sidebar-info-icon">
                    <i class="bi bi-p-square-fill"></i>
                </div>

                <div class="sidebar-info-title">Monitoring Operasional</div>
                <p class="sidebar-info-text">
                    Sistem pelaporan dan pemantauan kendala parkir berbasis web.
                </p>
            </div>
        </aside>

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-title">
                <h1>Sistem Penanganan Kendala Parkir</h1>
                <p>Monitoring Operasional Parkir</p>
            </div>

            <div class="topbar-actions">
                <div class="top-search" title="Fitur pencarian global dapat dikembangkan pada tahap berikutnya">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Cari laporan, lokasi, barang..." readonly>
                </div>

                <a href="{{ route('notifications.index') }}" class="notification-btn d-flex align-items-center justify-content-center text-dark">
                    <i class="bi bi-bell"></i>

                    <span id="notificationCountBadge" class="notification-count {{ $unreadNotificationCount > 0 ? '' : 'd-none' }}">
                        {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                    </span>
                </a>

                <div class="user-profile">
                    <div class="user-avatar">
                        @if ($profilePhoto)
                            <img src="{{ asset('storage/' . $profilePhoto) }}" alt="Foto Profil">
                        @else
                            {{ $initial }}
                        @endif
                    </div>

                    <div>
                        <div class="user-name">{{ $displayName }}</div>
                        <div class="user-role">{{ $roleLabel }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- CONTENT --}}
        <div class="content-wrapper">
            <main class="main-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>{{ session('warning') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="fw-bold mb-1">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>
                            Terjadi kesalahan input
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="footer">
                © 2026 ELITE Parkir. All rights reserved.
            </footer>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 9999">
        <div id="realtimeNotificationToast" class="toast align-items-center border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header border-0">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 34px; height: 34px;">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <strong class="me-auto" id="toastNotificationTitle">Notifikasi Baru</strong>
                <small>Baru saja</small>
                <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>

            <div class="toast-body" id="toastNotificationMessage">
                Ada notifikasi baru untuk Anda.
            </div>

            <div class="px-3 pb-3">
                <a href="{{ route('notifications.index') }}" id="toastNotificationLink" class="btn btn-sm btn-primary rounded-3">
                    Buka Notifikasi
                </a>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let lastNotificationId = null;
        let firstRealtimeCheck = true;

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notificationCountBadge');

            if (!badge) {
                return;
            }

            if (count > 0) {
                badge.classList.remove('d-none');
                badge.textContent = count > 99 ? '99+' : count;
            } else {
                badge.classList.add('d-none');
                badge.textContent = '0';
            }
        }

        function showRealtimeToast(title, message, url) {
            const toastElement = document.getElementById('realtimeNotificationToast');

            if (!toastElement) {
                return;
            }

            document.getElementById('toastNotificationTitle').textContent = title || 'Notifikasi Baru';
            document.getElementById('toastNotificationMessage').textContent = message || 'Ada notifikasi baru untuk Anda.';

            const toastLink = document.getElementById('toastNotificationLink');
            toastLink.href = url || "{{ route('notifications.index') }}";

            const toast = new bootstrap.Toast(toastElement, {
                delay: 6000
            });

            toast.show();
        }

        async function checkRealtimeNotifications() {
            try {
                const response = await fetch("{{ route('notifications.unread-count') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const result = await response.json();

                if (!result.success) {
                    return;
                }

                updateNotificationBadge(result.count);

                if (firstRealtimeCheck) {
                    lastNotificationId = result.latest_id;
                    firstRealtimeCheck = false;
                    return;
                }

                if (
                    result.latest_id &&
                    lastNotificationId &&
                    result.latest_id !== lastNotificationId &&
                    result.latest_is_read === false
                ) {
                    showRealtimeToast(
                        result.latest_title,
                        result.latest_message,
                        result.latest_url
                    );
                }

                lastNotificationId = result.latest_id;
            } catch (error) {
                console.log('Realtime notification check failed:', error);
            }
        }

        checkRealtimeNotifications();

        setInterval(checkRealtimeNotifications, 5000);
    </script>

    @stack('scripts')
</body>
</html>