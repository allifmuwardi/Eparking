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
            --ep-primary: #0d6efd;
            --ep-primary-dark: #062b68;
            --ep-primary-deep: #003b8f;
            --ep-primary-soft: #eaf3ff;

            --ep-success: #198754;
            --ep-warning: #f59f00;
            --ep-danger: #dc3545;

            --ep-text-dark: #071b4d;
            --ep-text-main: #21345f;
            --ep-text-muted: #6b7fae;

            --ep-border: #d7e3f7;
            --ep-border-soft: rgba(185, 203, 234, 0.72);

            --ep-body-bg: #f5f8fc;
            --ep-card-bg: rgba(255, 255, 255, 0.96);

            --ep-sidebar-width: 286px;
            --ep-topbar-height: 82px;

            --ep-radius-sm: 12px;
            --ep-radius-md: 16px;
            --ep-radius-lg: 22px;

            --ep-shadow-card: 0 18px 42px rgba(15, 23, 42, 0.08);
            --ep-shadow-panel: 0 22px 52px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at 8% 12%, rgba(13, 110, 253, 0.08), transparent 28%),
                radial-gradient(circle at 92% 86%, rgba(13, 110, 253, 0.08), transparent 30%),
                linear-gradient(135deg, #f8fbff 0%, #edf6ff 45%, #f9fcff 100%);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--ep-text-dark);
            overflow-x: hidden;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
        }

        .app-shell {
            min-height: 100vh;
        }

        /*
        |--------------------------------------------------------------------------
        | Sidebar Drawer
        |--------------------------------------------------------------------------
        */

        .sidebar {
            position: fixed;
            top: 18px;
            left: 18px;
            bottom: 18px;
            width: var(--ep-sidebar-width);
            background: var(--ep-card-bg);
            border: 1px solid var(--ep-border-soft);
            border-radius: var(--ep-radius-lg);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            box-shadow: var(--ep-shadow-panel);
            backdrop-filter: blur(16px);
            overflow: hidden;
            transform: translateX(-120%);
            transition: all 0.25s ease;
        }

        body.sidebar-open .sidebar {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(7, 27, 77, 0.32);
            backdrop-filter: blur(3px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        body.sidebar-open .sidebar-overlay {
            opacity: 1;
            visibility: visible;
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
            color: var(--ep-primary-dark);
            line-height: 1.1;
            letter-spacing: -0.2px;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--ep-text-muted);
            margin-top: 4px;
            font-weight: 650;
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
            letter-spacing: 0.07em;
            margin: 18px 12px 8px;
        }

        .menu-label:first-child {
            margin-top: 0;
        }

        .menu-item {
            min-height: 46px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            border-radius: 15px;
            color: #31466f;
            font-size: 14px;
            font-weight: 780;
            margin-bottom: 5px;
            transition: all 0.18s ease;
            position: relative;
        }

        .menu-item i {
            width: 25px;
            height: 25px;
            border-radius: 10px;
            background: #f0f6ff;
            color: var(--ep-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            transition: all 0.18s ease;
        }

        .menu-item:hover {
            color: var(--ep-primary-deep);
            background: #f3f8ff;
            transform: translateX(2px);
        }

        .menu-item.active {
            color: #ffffff;
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            box-shadow: 0 14px 26px rgba(13, 110, 253, 0.24);
        }

        .menu-item.active i {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.18);
        }

        .sidebar-info {
            margin: 0 14px 14px;
            padding: 17px;
            border-radius: 19px;
            background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 38%),
                linear-gradient(180deg, #f8fbff, #ffffff);
            border: 1px solid rgba(185, 203, 234, 0.78);
        }

        .sidebar-info-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: var(--ep-primary-soft);
            color: var(--ep-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .sidebar-info-title {
            font-size: 13px;
            font-weight: 950;
            color: var(--ep-primary-dark);
            margin-bottom: 4px;
        }

        .sidebar-info-text {
            font-size: 12px;
            color: var(--ep-text-muted);
            font-weight: 650;
            line-height: 1.45;
            margin-bottom: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Topbar Full Width
        |--------------------------------------------------------------------------
        */

        .topbar {
            position: sticky;
            top: 18px;
            margin: 18px 18px 0;
            min-height: var(--ep-topbar-height);
            background: var(--ep-card-bg);
            border: 1px solid var(--ep-border-soft);
            border-radius: var(--ep-radius-lg);
            box-shadow: var(--ep-shadow-card);
            backdrop-filter: blur(16px);
            z-index: 1030;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
            flex: 1;
        }

        .mobile-menu-btn {
            width: 46px;
            height: 46px;
            border: none;
            border-radius: 14px;
            background: var(--ep-primary-soft);
            color: var(--ep-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
            transition: all 0.18s ease;
        }

        .mobile-menu-btn:hover {
            background: var(--ep-primary);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .topbar-title {
            min-width: 0;
        }

        .topbar-title h1 {
            color: var(--ep-primary-dark);
            font-size: 22px;
            font-weight: 950;
            margin: 0;
            letter-spacing: -0.3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-title p {
            color: var(--ep-text-muted);
            font-size: 13px;
            font-weight: 650;
            margin: 4px 0 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .notification-btn {
            width: 48px;
            height: 48px;
            border-radius: 15px;
            background: #ffffff;
            border: 1px solid var(--ep-border);
            color: var(--ep-primary-dark);
            position: relative;
            transition: all 0.18s ease;
        }

        .notification-btn:hover {
            color: var(--ep-primary);
            background: #f3f8ff;
            transform: translateY(-1px);
        }

        .notification-count {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            background: var(--ep-danger);
            color: #ffffff;
            border: 2px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 950;
            line-height: 1;
        }

        .user-profile {
            min-width: 214px;
            height: 50px;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 6px 12px 6px 7px;
            border-radius: 16px;
            border: 1px solid var(--ep-border);
            background: #ffffff;
            color: var(--ep-text-dark);
            transition: all 0.18s ease;
        }

        .user-profile:hover {
            background: #f8fbff;
            border-color: #b9cbea;
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 13px;
            background: linear-gradient(145deg, #0b3969, #07264c);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 950;
            overflow: hidden;
            flex-shrink: 0;
        }

        .user-avatar img {
            width: 38px;
            height: 38px;
            object-fit: cover;
        }

        .user-name {
            color: var(--ep-text-dark);
            font-size: 13px;
            font-weight: 950;
            line-height: 1.1;
            max-width: 132px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: var(--ep-text-muted);
            font-weight: 750;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            white-space: nowrap;
        }

        .logout-btn:hover {
            background: var(--ep-danger);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /*
        |--------------------------------------------------------------------------
        | Main Content Full Width
        |--------------------------------------------------------------------------
        */

        .content-wrapper {
            margin-left: 0;
            padding-top: 0;
            min-height: 100vh;
        }

        .main-content {
            padding: 24px 18px 24px;
            min-height: calc(100vh - var(--ep-topbar-height) - 94px);
        }

        .page-card {
            background: var(--ep-card-bg);
            border-radius: 20px;
            border: 1px solid var(--ep-border-soft);
            box-shadow: var(--ep-shadow-card);
            backdrop-filter: blur(14px);
        }

        .section-title {
            color: var(--ep-primary-dark);
            font-weight: 950;
            letter-spacing: -0.2px;
        }

        .section-subtitle {
            color: var(--ep-text-muted);
            font-weight: 650;
        }

        .footer {
            min-height: 54px;
            color: #8a9abc;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 12px;
            font-weight: 650;
            padding: 12px 18px 24px;
        }

        /*
        |--------------------------------------------------------------------------
        | Bootstrap Enhancement
        |--------------------------------------------------------------------------
        */

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

        .alert-info {
            background: #e5f8ff;
            color: #086074;
        }

        .btn {
            font-weight: 800;
            border-radius: 12px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            border: none;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.18);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0d6efd, #003b8f);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border-color: #b9cef2;
            color: var(--ep-primary-deep);
        }

        .btn-outline-primary:hover {
            background: var(--ep-primary);
            border-color: var(--ep-primary);
        }

        .form-control,
        .form-select {
            border-radius: 13px;
            border-color: var(--ep-border);
            color: var(--ep-text-dark);
            font-weight: 600;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
        }

        .form-label {
            color: var(--ep-text-dark);
            font-weight: 850;
            font-size: 13px;
        }

        .table {
            color: var(--ep-text-main);
            vertical-align: middle;
        }

        .table-responsive {
            border-radius: 16px;
        }

        .table thead th {
            color: var(--ep-primary-dark);
            font-size: 12px;
            font-weight: 950;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background: #f4f8ff;
            border-bottom: 1px solid var(--ep-border);
            white-space: nowrap;
        }

        .table tbody td {
            font-size: 13px;
            font-weight: 650;
            border-color: #edf2fb;
        }

        .table-hover tbody tr:hover {
            background: #f8fbff;
        }

        .badge {
            border-radius: 999px;
            font-weight: 850;
            padding: 0.45rem 0.7rem;
        }

        .card {
            border-radius: 18px;
            border-color: var(--ep-border-soft);
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 22px 52px rgba(15, 23, 42, 0.18);
        }

        .modal-header {
            border-bottom-color: #edf2fb;
        }

        .modal-footer {
            border-top-color: #edf2fb;
        }

        .toast {
            border-radius: 18px !important;
            overflow: hidden;
        }

        .pagination {
            gap: 5px;
            flex-wrap: wrap;
            align-items: center;
        }

        .pagination .page-item {
            display: inline-flex;
            align-items: center;
        }

        .pagination .page-link {
            min-width: 38px;
            min-height: 38px;
            border-radius: 10px;
            color: var(--ep-primary-deep);
            border-color: var(--ep-border);
            font-weight: 750;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .pagination .page-link:hover {
            background: #f3f8ff;
            border-color: #b9cbea;
            color: var(--ep-primary);
        }

        .pagination .page-item.active .page-link {
            background: var(--ep-primary);
            border-color: var(--ep-primary);
            color: #ffffff;
        }

        .pagination .page-item.disabled .page-link {
            color: #9aa9c5;
            background: #f8fbff;
            border-color: var(--ep-border);
        }

        .pagination svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            vertical-align: middle;
            display: inline-block;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive Global
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1200px) {
            .user-profile {
                min-width: 180px;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                top: 12px;
                left: 12px;
                bottom: 12px;
                width: 286px;
                border-radius: 20px;
            }

            .topbar {
                top: 12px;
                margin: 12px 12px 0;
                min-height: 76px;
                padding: 14px;
                border-radius: 20px;
            }

            .topbar-title h1 {
                font-size: 18px;
            }

            .topbar-title p {
                font-size: 12px;
            }

            .main-content {
                padding: 16px 12px 20px;
            }

            .topbar-actions {
                gap: 8px;
            }

            .user-profile {
                min-width: auto;
                width: 48px;
                height: 48px;
                padding: 6px;
                justify-content: center;
            }

            .user-profile > div:last-child {
                display: none;
            }

            .logout-btn {
                width: 46px;
                height: 46px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .logout-btn span {
                display: none;
            }

            .notification-btn {
                width: 46px;
                height: 46px;
            }

            .sidebar-info {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .topbar {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .topbar-left {
                width: 100%;
            }

            .topbar-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .topbar-title h1 {
                max-width: 230px;
            }

            .topbar-title p {
                max-width: 230px;
            }

            .main-content {
                padding: 14px 12px 18px;
            }

            .footer {
                font-size: 11px;
                padding-bottom: 18px;
            }
        }

        @media (max-width: 390px) {
            .sidebar {
                width: calc(100vw - 24px);
            }

            .topbar-title h1 {
                max-width: 200px;
            }

            .topbar-title p {
                max-width: 200px;
            }
        }
    </style>

    @stack('styles')
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

        $profilePhotoUrl = null;

        if (!empty($profilePhoto)) {
            if (\Illuminate\Support\Str::startsWith($profilePhoto, ['http://', 'https://'])) {
                $profilePhotoUrl = $profilePhoto;
            } elseif (\Illuminate\Support\Str::startsWith($profilePhoto, 'storage/')) {
                $profilePhotoUrl = asset($profilePhoto);
            } else {
                $profilePhotoUrl = asset('storage/' . $profilePhoto);
            }
        }

        $pageTitle = trim($__env->yieldContent('page_title')) ?: 'Sistem Penanganan Kendala Parkir';
        $pageSubtitle = trim($__env->yieldContent('page_subtitle')) ?: 'Monitoring Operasional Parkir';
    @endphp

    <div class="app-shell">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- SIDEBAR DRAWER --}}
        <aside class="sidebar" id="appSidebar">
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
            <div class="topbar-left">
                <button type="button" class="mobile-menu-btn" id="mobileMenuButton" aria-label="Buka Menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="topbar-title">
                    <h1>{{ $pageTitle }}</h1>
                    <p>{{ $pageSubtitle }}</p>
                </div>
            </div>

            <div class="topbar-actions">
                <a href="{{ route('notifications.index') }}" class="notification-btn d-flex align-items-center justify-content-center" title="Notifikasi">
                    <i class="bi bi-bell"></i>

                    <span id="notificationCountBadge" class="notification-count {{ $unreadNotificationCount > 0 ? '' : 'd-none' }}">
                        {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                    </span>
                </a>

                <a href="{{ route('profile.index') }}" class="user-profile" title="Profil Saya">
                    <div class="user-avatar">
                        @if ($profilePhotoUrl)
                            <img src="{{ $profilePhotoUrl }}" alt="Foto Profil">
                        @else
                            {{ $initial }}
                        @endif
                    </div>

                    <div>
                        <div class="user-name">{{ $displayName }}</div>
                        <div class="user-role">{{ $roleLabel }}</div>
                    </div>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="logout-btn" title="Logout">
                        <i class="bi bi-box-arrow-right me-lg-1"></i>
                        <span>Logout</span>
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
                © 2026 ELITE Parkir. Sistem Penanganan Kendala Parkir Berbasis Web.
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
        const body = document.body;
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            body.classList.add('sidebar-open');
        }

        function closeSidebar() {
            body.classList.remove('sidebar-open');
        }

        function toggleSidebar() {
            body.classList.toggle('sidebar-open');
        }

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', toggleSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        document.querySelectorAll('.sidebar .menu-item').forEach(function (menuItem) {
            menuItem.addEventListener('click', function () {
                closeSidebar();
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });

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

            if (!toastElement || typeof bootstrap === 'undefined') {
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
        setInterval(checkRealtimeNotifications, 30000);
    </script>

    @stack('scripts')
</body>
</html>