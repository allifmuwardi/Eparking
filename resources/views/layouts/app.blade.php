<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Penanganan Kendala Parkir')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        | CS ELITE Parkir Floating Chat
        |--------------------------------------------------------------------------
        */

        .cs-ai-floating {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 1080;
        }

        .cs-ai-button {
            border: none;
            min-width: 150px;
            height: 54px;
            border-radius: 999px;
            background: linear-gradient(135deg, #0b3969, #0d6efd);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 18px;
            font-size: 14px;
            font-weight: 950;
            box-shadow: 0 18px 38px rgba(13, 110, 253, 0.32);
            transition: all 0.2s ease;
        }

        .cs-ai-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 44px rgba(13, 110, 253, 0.38);
        }

        .cs-ai-button i {
            width: 28px;
            height: 28px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .cs-ai-panel {
            position: fixed;
            right: 24px;
            bottom: 90px;
            width: 430px;
            height: 590px;
            min-width: 360px;
            min-height: 455px;
            max-width: calc(100vw - 48px);
            max-height: calc(100vh - 118px);
            background: #ffffff;
            border: 1px solid rgba(185, 203, 234, 0.88);
            border-radius: 24px;
            box-shadow: 0 28px 70px rgba(7, 27, 77, 0.22);
            z-index: 1081;
            display: none;
            flex-direction: column;
            overflow: hidden;
            resize: both;
            transform-origin: bottom right;
        }

        .cs-ai-panel.show {
            display: flex;
            animation: csAiPop 0.18s ease;
        }

        .cs-ai-panel.fullscreen {
            top: 24px;
            right: 24px;
            bottom: 24px;
            width: 560px !important;
            height: calc(100vh - 48px) !important;
            max-height: calc(100vh - 48px);
            resize: none;
        }

        @keyframes csAiPop {
            from {
                transform: translateY(12px) scale(0.96);
                opacity: 0;
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .cs-ai-header {
            min-height: 70px;
            padding: 14px 15px;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.22), transparent 35%),
                linear-gradient(135deg, #082d66, #0d6efd);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
        }

        .cs-ai-header-left {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .cs-ai-avatar {
            width: 43px;
            height: 43px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.26);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .cs-ai-title {
            font-size: 14px;
            font-weight: 950;
            margin: 0;
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cs-ai-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 800;
            opacity: 0.92;
            margin-top: 4px;
        }

        .cs-ai-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #19d67d;
            box-shadow: 0 0 0 4px rgba(25, 214, 125, 0.18);
            flex-shrink: 0;
        }

        .cs-ai-header-actions {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
        }

        .cs-ai-tool,
        .cs-ai-close {
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.18s ease;
            flex-shrink: 0;
            font-size: 14px;
        }

        .cs-ai-tool:hover,
        .cs-ai-close:hover {
            background: rgba(255, 255, 255, 0.24);
            transform: translateY(-1px);
        }

        .cs-ai-body {
            flex: 1;
            padding: 16px;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.06), transparent 32%),
                #f7faff;
            overflow-y: auto;
        }

        .cs-ai-body::-webkit-scrollbar {
            width: 5px;
        }

        .cs-ai-body::-webkit-scrollbar-thumb {
            background: #cbdaf2;
            border-radius: 999px;
        }

        .cs-ai-message {
            display: flex;
            margin-bottom: 12px;
        }

        .cs-ai-message.ai {
            justify-content: flex-start;
        }

        .cs-ai-message.user {
            justify-content: flex-end;
        }

        .cs-ai-bubble {
            max-width: 88%;
            border-radius: 18px;
            padding: 12px 14px;
            font-size: 13px;
            font-weight: 650;
            line-height: 1.58;
            white-space: normal;
            word-break: break-word;
            position: relative;
        }

        .cs-ai-content {
            display: block;
        }

        .cs-ai-content p {
            margin: 0 0 9px;
        }

        .cs-ai-content p:last-child {
            margin-bottom: 0;
        }

        .cs-ai-content .cs-ai-line {
            display: block;
            margin-bottom: 5px;
        }

        .cs-ai-content .cs-ai-section-title {
            display: block;
            color: var(--ep-primary-dark);
            font-weight: 950;
            margin: 10px 0 6px;
        }

        .cs-ai-message.user .cs-ai-content .cs-ai-section-title {
            color: #ffffff;
        }

        .cs-ai-message.ai .cs-ai-bubble {
            background: #ffffff;
            color: var(--ep-text-main);
            border-top-left-radius: 6px;
            border: 1px solid rgba(215, 227, 247, 0.9);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
        }

        .cs-ai-message.user .cs-ai-bubble {
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            color: #ffffff;
            border-top-right-radius: 6px;
            box-shadow: 0 8px 18px rgba(13, 110, 253, 0.20);
        }

        .cs-ai-meta {
            display: block;
            margin-top: 7px;
            font-size: 10px;
            font-weight: 800;
            opacity: 0.55;
        }

        .cs-ai-quick {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            margin: 4px 0 14px;
        }

        .cs-ai-chip {
            border: 1px solid #c9d9f2;
            background: #ffffff;
            color: var(--ep-primary-deep);
            border-radius: 999px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 850;
            transition: all 0.18s ease;
        }

        .cs-ai-chip:hover {
            background: var(--ep-primary-soft);
            border-color: #9fc0ef;
            transform: translateY(-1px);
        }

        .cs-ai-typing {
            display: none;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
        }

        .cs-ai-typing.show {
            display: flex;
        }

        .cs-ai-typing .cs-ai-bubble {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: auto;
        }

        .cs-ai-typing span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #8aa4cf;
            animation: csAiTyping 1s infinite ease-in-out;
        }

        .cs-ai-typing span:nth-child(2) {
            animation-delay: 0.15s;
        }

        .cs-ai-typing span:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes csAiTyping {
            0%, 80%, 100% {
                transform: translateY(0);
                opacity: 0.45;
            }

            40% {
                transform: translateY(-4px);
                opacity: 1;
            }
        }

        .cs-ai-footer {
            padding: 13px;
            background: #ffffff;
            border-top: 1px solid #edf2fb;
            flex-shrink: 0;
        }

        .cs-ai-form {
            display: flex;
            align-items: flex-end;
            gap: 9px;
        }

        .cs-ai-input {
            flex: 1;
            min-height: 44px;
            max-height: 112px;
            resize: none;
            border-radius: 15px;
            border: 1px solid var(--ep-border);
            padding: 11px 13px;
            font-size: 13px;
            font-weight: 650;
            color: var(--ep-text-dark);
            outline: none;
            line-height: 1.45;
        }

        .cs-ai-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.10);
        }

        .cs-ai-send {
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 15px;
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            transition: all 0.18s ease;
        }

        .cs-ai-send:hover {
            transform: translateY(-1px);
        }

        .cs-ai-send:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .cs-ai-hint {
            margin-top: 7px;
            font-size: 10.5px;
            color: #6b7fae;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .cs-ai-footer-note {
            margin-bottom: 10px;
            border-radius: 14px;
            padding: 9px 11px;
            background: #f4f8ff;
            border: 1px solid #dbe8fb;
            color: #587099;
            font-size: 11px;
            line-height: 1.45;
            font-weight: 750;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .cs-ai-footer-note i {
            color: var(--ep-primary);
            margin-top: 1px;
            flex-shrink: 0;
        }

        .cs-ai-counter {
            white-space: nowrap;
            color: #8a9abc;
        }

        .cs-ai-counter.warning {
            color: #b7791f;
        }

        .cs-ai-counter.danger {
            color: #b4232a;
        }

        .cs-ai-panel.is-sending .cs-ai-chip {
            opacity: 0.65;
            cursor: not-allowed;
            pointer-events: none;
        }

        .cs-ai-panel.is-sending .cs-ai-input {
            background: #f8fbff;
        }

        .cs-ai-resize-note {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
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

            .cs-ai-floating {
                right: 14px;
                bottom: 16px;
            }

            .cs-ai-button {
                min-width: 54px;
                width: 54px;
                padding: 0;
            }

            .cs-ai-button span {
                display: none;
            }

            .cs-ai-panel {
                right: 12px;
                bottom: 82px;
                width: calc(100vw - 24px) !important;
                min-width: 0;
                height: min(590px, calc(100vh - 112px)) !important;
                max-height: calc(100vh - 112px);
                border-radius: 22px;
                resize: none;
            }

            .cs-ai-panel.fullscreen {
                top: 12px;
                right: 12px;
                bottom: 12px;
                height: calc(100vh - 24px) !important;
                width: calc(100vw - 24px) !important;
            }

            .cs-ai-header-actions {
                gap: 5px;
            }

            .cs-ai-tool,
            .cs-ai-close {
                width: 32px;
                height: 32px;
            }

            .cs-ai-hint {
                align-items: flex-start;
                flex-direction: column;
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
        $firstName = trim(explode(' ', $displayName)[0] ?? $displayName);
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


    {{-- CS ELITE PARKIR FLOATING CHAT --}}
    <div class="cs-ai-floating">
        <button type="button" class="cs-ai-button" id="csAiToggleButton" aria-label="Buka CS ELITE Parkir">
            <i class="bi bi-headset"></i>
            <span>CS by AI</span>
        </button>
    </div>

    <div class="cs-ai-panel" id="csAiPanel" aria-live="polite">
        <div class="cs-ai-header">
            <div class="cs-ai-header-left">
                <div class="cs-ai-avatar">
                    <i class="bi bi-headset"></i>
                </div>

                <div class="min-w-0">
                    <p class="cs-ai-title">CS ELITE Parkir</p>
                    <div class="cs-ai-status">
                        <span class="cs-ai-status-dot"></span>
                        <span>Online • Siap memberi arahan operasional</span>
                    </div>
                </div>
            </div>

            <div class="cs-ai-header-actions">
                <button type="button" class="cs-ai-tool" id="csAiCompactButton" title="Ukuran compact" aria-label="Ukuran compact">
                    <i class="bi bi-arrows-collapse"></i>
                </button>

                <button type="button" class="cs-ai-tool" id="csAiWideButton" title="Perbesar chat" aria-label="Perbesar chat">
                    <i class="bi bi-arrows-angle-expand"></i>
                </button>

                <button type="button" class="cs-ai-close" id="csAiCloseButton" aria-label="Tutup CS ELITE Parkir">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <div class="cs-ai-body" id="csAiMessages">
            <div class="cs-ai-message ai">
                <div class="cs-ai-bubble">
Halo {{ $firstName }}, saya CS by AI ELITE Parkir. Saya siap membantu menjelaskan tindakan saat terjadi kendala di area parkir, cara membuat laporan, data yang perlu diisi, alur penanganan, dan panduan penggunaan sistem sesuai role Anda.
                    <span class="cs-ai-meta">CS ELITE Parkir • siap membantu</span>
                </div>
            </div>

            <div class="cs-ai-quick" id="csAiQuickQuestions">
                @if ($role === 'petugas')
                    <button type="button" class="cs-ai-chip" data-question="Ada kendala di area parkir, apa yang harus saya lakukan dari tindakan lapangan sampai membuat laporan?">Alur saat kendala</button>
                    <button type="button" class="cs-ai-chip" data-question="Buatkan contoh deskripsi laporan untuk gate keluar tidak terbuka dan kendaraan mulai antre.">Template deskripsi</button>
                    <button type="button" class="cs-ai-chip" data-question="Jika printer tiket error dan antrean panjang, bagaimana cara menyikapi kejadian di area dan melaporkannya di sistem?">Kendala lapangan</button>
                    <button type="button" class="cs-ai-chip" data-question="Setelah laporan dikirim, siapa yang memproses dan apa arti statusnya?">Alur status</button>
                    <button type="button" class="cs-ai-chip" data-question="Jika butuh barang backup saat kendala, apa yang harus dilakukan?">Butuh backup</button>
                    <button type="button" class="cs-ai-chip" data-question="Apa saja foto bukti yang sebaiknya diupload saat ada kendala parkir?">Foto bukti</button>
                @elseif ($role === 'teknisi')
                    <button type="button" class="cs-ai-chip" data-question="Sebagai teknisi, apa yang harus dilakukan setelah menerima tugas laporan kendala?">Saat menerima tugas</button>
                    <button type="button" class="cs-ai-chip" data-question="Bagaimana cara teknisi memberi update penanganan agar petugas dan manajer paham progresnya?">Update penanganan</button>
                    <button type="button" class="cs-ai-chip" data-question="Buatkan contoh catatan teknisi untuk laporan gate keluar yang sudah dicek dan diperbaiki.">Template catatan</button>
                    <button type="button" class="cs-ai-chip" data-question="Dokumentasi apa yang harus diupload teknisi agar laporan penanganan jelas?">Bukti teknisi</button>
                    <button type="button" class="cs-ai-chip" data-question="Apa arti status Menunggu Informasi dan Selesai Ditangani?">Arti status</button>
                    <button type="button" class="cs-ai-chip" data-question="Kalau bukti penanganan gagal upload, apa yang harus dicek?">Upload gagal</button>
                @elseif ($role === 'manajer')
                    <button type="button" class="cs-ai-chip" data-question="Sebagai manajer, apa yang harus dicek sebelum memverifikasi laporan dan menugaskan teknisi?">Verifikasi & assign</button>
                    <button type="button" class="cs-ai-chip" data-question="Bagaimana manajer menyikapi laporan yang kurang lengkap, sudah selesai, atau perlu ditolak?">Sikap laporan</button>
                    <button type="button" class="cs-ai-chip" data-question="Bagaimana laporan rekap membantu manajer memonitor kendala operasional parkir?">Laporan rekap</button>
                    <button type="button" class="cs-ai-chip" data-question="Bagaimana cara menyetujui atau menolak permintaan barang backup?">Approve backup</button>
                    <button type="button" class="cs-ai-chip" data-question="Apa checklist manajer sebelum menutup laporan kendala?">Checklist tutup</button>
                    <button type="button" class="cs-ai-chip" data-question="Apa arti status laporan kendala pada sistem?">Arti status</button>
                @elseif ($role === 'admin')
                    <button type="button" class="cs-ai-chip" data-question="Sebagai admin, bagaimana cara membantu user baru agar bisa memakai sistem?">Bantu user baru</button>
                    <button type="button" class="cs-ai-chip" data-question="Data apa saja yang harus dicek admin sebelum membuat akun Petugas atau Teknisi?">Data akun</button>
                    <button type="button" class="cs-ai-chip" data-question="Bagaimana cara mengelola master lokasi parkir?">Master lokasi</button>
                    <button type="button" class="cs-ai-chip" data-question="Bagaimana cara mengelola master barang backup?">Master barang</button>
                    <button type="button" class="cs-ai-chip" data-question="Sebagai admin, bagaimana proses barang backup yang sudah disetujui manajer?">Proses backup</button>
                    <button type="button" class="cs-ai-chip" data-question="Jika pengguna tidak bisa login atau lupa password, bagaimana admin menyikapinya?">Kendala login</button>
                @else
                    <button type="button" class="cs-ai-chip" data-question="Bagaimana cara menggunakan Sistem ELITE Parkir?">Panduan sistem</button>
                    <button type="button" class="cs-ai-chip" data-question="Apa saja fitur utama Sistem ELITE Parkir?">Fitur utama</button>
                    <button type="button" class="cs-ai-chip" data-question="Siapa saja role yang ada di Sistem ELITE Parkir?">Role sistem</button>
                    <button type="button" class="cs-ai-chip" data-question="Apa batasan CS ELITE Parkir dalam membantu pengguna?">Batasan CS</button>
                @endif
            </div>

            <div class="cs-ai-typing" id="csAiTyping">
                <div class="cs-ai-bubble">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <div class="cs-ai-footer">
            <div class="cs-ai-footer-note">
                <i class="bi bi-info-circle-fill"></i>
                <span>CS by AI membantu menggantikan fungsi CS internal untuk arahan awal, panduan laporan, dan prosedur kendala operasional. Keputusan dan perubahan data tetap mengikuti kewenangan role serta SOP yang berlaku.</span>
            </div>

            <form class="cs-ai-form" id="csAiForm">
                <textarea
                    class="cs-ai-input"
                    id="csAiInput"
                    rows="1"
                    maxlength="1000"
                    placeholder="Tulis pertanyaan Anda..."
                ></textarea>

                <button type="submit" class="cs-ai-send" id="csAiSendButton" aria-label="Kirim pertanyaan">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>

            <div class="cs-ai-hint">
                <span>Enter untuk kirim • Shift + Enter untuk baris baru</span>
                <span class="cs-ai-counter" id="csAiCounter">0/1000</span>
                <span class="cs-ai-resize-note">
                    <i class="bi bi-arrows-angle-expand"></i>
                    Chat dapat diperbesar
                </span>
            </div>
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


        const csAiToggleButton = document.getElementById('csAiToggleButton');
        const csAiCloseButton = document.getElementById('csAiCloseButton');
        const csAiCompactButton = document.getElementById('csAiCompactButton');
        const csAiWideButton = document.getElementById('csAiWideButton');
        const csAiPanel = document.getElementById('csAiPanel');
        const csAiMessages = document.getElementById('csAiMessages');
        const csAiForm = document.getElementById('csAiForm');
        const csAiInput = document.getElementById('csAiInput');
        const csAiSendButton = document.getElementById('csAiSendButton');
        const csAiTyping = document.getElementById('csAiTyping');
        const csAiQuickQuestions = document.getElementById('csAiQuickQuestions');
        const csAiCounter = document.getElementById('csAiCounter');
        let isCsAiSending = false;

        const csAiDefaultSize = {
            width: 430,
            height: 590
        };

        const csAiLargeSize = {
            width: 560,
            height: 680
        };

        function openCsAiPanel() {
            if (csAiPanel) {
                csAiPanel.classList.add('show');
            }

            if (csAiInput) {
                setTimeout(function () {
                    csAiInput.focus();
                }, 180);
            }

            scrollCsAiToBottom();
        }

        function closeCsAiPanel() {
            if (csAiPanel) {
                csAiPanel.classList.remove('show');
            }
        }

        function toggleCsAiPanel() {
            if (!csAiPanel) {
                return;
            }

            if (csAiPanel.classList.contains('show')) {
                closeCsAiPanel();
            } else {
                openCsAiPanel();
            }
        }

        function setCsAiSize(width, height) {
            if (!csAiPanel) {
                return;
            }

            csAiPanel.classList.remove('fullscreen');
            csAiPanel.style.width = width + 'px';
            csAiPanel.style.height = height + 'px';
            localStorage.setItem('csEliteChatSize', JSON.stringify({ width, height }));
            scrollCsAiToBottom();
        }

        function toggleCsAiLargeSize() {
            if (!csAiPanel) {
                return;
            }

            if (csAiPanel.classList.contains('fullscreen')) {
                csAiPanel.classList.remove('fullscreen');
                setCsAiSize(csAiLargeSize.width, csAiLargeSize.height);
                return;
            }

            const currentWidth = csAiPanel.offsetWidth;
            const currentHeight = csAiPanel.offsetHeight;

            if (currentWidth >= 540 || currentHeight >= 650) {
                setCsAiSize(csAiDefaultSize.width, csAiDefaultSize.height);
            } else {
                setCsAiSize(csAiLargeSize.width, csAiLargeSize.height);
            }
        }

        function applySavedCsAiSize() {
            if (!csAiPanel) {
                return;
            }

            try {
                const saved = JSON.parse(localStorage.getItem('csEliteChatSize') || '{}');

                if (saved.width && saved.height && window.innerWidth > 576) {
                    csAiPanel.style.width = Math.min(saved.width, window.innerWidth - 48) + 'px';
                    csAiPanel.style.height = Math.min(saved.height, window.innerHeight - 118) + 'px';
                }
            } catch (error) {
                localStorage.removeItem('csEliteChatSize');
            }
        }

        function scrollCsAiToBottom() {
            if (csAiMessages) {
                csAiMessages.scrollTop = csAiMessages.scrollHeight;
            }
        }

        function currentCsAiTime() {
            const date = new Date();
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function escapeCsAiHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function normalizeCsAiMessage(message) {
            return String(message || '')
                .replace(/\r\n/g, '\n')
                .replace(/\r/g, '\n')
                .replace(/\*\*/g, '')
                .replace(/__/g, '')
                .replace(/^#{1,6}\s*/gm, '')
                .replace(/\[(.*?)\]\((.*?)\)/g, '$1')
                .replace(/[ \t]+$/gm, '')
                .replace(/\n{3,}/g, '\n\n')
                .trim();
        }

        function formatCsAiMessageHtml(message) {
            const normalized = normalizeCsAiMessage(message);

            if (!normalized) {
                return '';
            }

            const paragraphs = normalized.split(/\n{2,}/).filter(Boolean);

            return paragraphs.map(function (paragraph) {
                const lines = paragraph.split('\n').filter(function (line) {
                    return line.trim() !== '';
                });

                const htmlLines = lines.map(function (line) {
                    const cleanLine = line.trim();
                    const escapedLine = escapeCsAiHtml(cleanLine);

                    if (/^\d+[\).]\s+/.test(cleanLine) || /^[A-Za-zÀ-ÿ\s]+:$/.test(cleanLine)) {
                        return '<span class="cs-ai-section-title">' + escapedLine + '</span>';
                    }

                    return '<span class="cs-ai-line">' + escapedLine + '</span>';
                }).join('');

                return '<p>' + htmlLines + '</p>';
            }).join('');
        }

        function appendCsAiMessage(type, message) {
            if (!csAiMessages) {
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'cs-ai-message ' + type;

            const bubble = document.createElement('div');
            bubble.className = 'cs-ai-bubble';

            const messageText = document.createElement('div');
            messageText.className = 'cs-ai-content';
            messageText.innerHTML = formatCsAiMessageHtml(message);

            const meta = document.createElement('span');
            meta.className = 'cs-ai-meta';
            meta.textContent = type === 'user' ? 'Anda • ' + currentCsAiTime() : 'CS ELITE Parkir • ' + currentCsAiTime();

            bubble.appendChild(messageText);
            bubble.appendChild(meta);
            wrapper.appendChild(bubble);

            if (csAiTyping) {
                csAiMessages.insertBefore(wrapper, csAiTyping);
            } else {
                csAiMessages.appendChild(wrapper);
            }

            scrollCsAiToBottom();
        }

        function setCsAiLoading(isLoading) {
            isCsAiSending = isLoading;

            if (csAiTyping) {
                csAiTyping.classList.toggle('show', isLoading);
            }

            if (csAiPanel) {
                csAiPanel.classList.toggle('is-sending', isLoading);
            }

            if (csAiSendButton) {
                csAiSendButton.disabled = isLoading;
            }

            if (csAiInput) {
                csAiInput.disabled = isLoading;
                csAiInput.placeholder = isLoading ? 'CS sedang mengetik...' : 'Tulis pertanyaan Anda...';
            }

            scrollCsAiToBottom();
        }

        function updateCsAiCounter() {
            if (!csAiInput || !csAiCounter) {
                return;
            }

            const length = csAiInput.value.length;
            csAiCounter.textContent = length + '/1000';
            csAiCounter.classList.toggle('warning', length >= 850 && length < 960);
            csAiCounter.classList.toggle('danger', length >= 960);
        }

        function autoResizeCsAiInput() {
            if (!csAiInput) {
                return;
            }

            csAiInput.style.height = 'auto';
            csAiInput.style.height = Math.min(csAiInput.scrollHeight, 112) + 'px';
            updateCsAiCounter();
        }

        async function sendCsAiMessage(message) {
            if (isCsAiSending) {
                return;
            }

            const question = (message || '').trim();

            if (question.length > 1000) {
                appendCsAiMessage('ai', 'Pertanyaan maksimal 1000 karakter. Silakan ringkas pertanyaan terlebih dahulu.');
                return;
            }

            if (question.length < 2) {
                appendCsAiMessage('ai', 'Silakan tulis pertanyaan minimal 2 karakter agar saya bisa membantu.');
                return;
            }

            appendCsAiMessage('user', question);

            if (csAiInput) {
                csAiInput.value = '';
                autoResizeCsAiInput();
            }

            setCsAiLoading(true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const response = await fetch("{{ route('cs-ai.chat') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({
                        message: question
                    })
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    appendCsAiMessage('ai', result.message || 'Maaf, layanan CS sedang tidak dapat diakses. Silakan coba beberapa saat lagi.');
                    return;
                }

                appendCsAiMessage('ai', result.message || 'Maaf, saya belum mendapatkan jawaban untuk pertanyaan tersebut.');
            } catch (error) {
                appendCsAiMessage('ai', 'Maaf, koneksi ke layanan CS sedang bermasalah. Pastikan koneksi internet aktif lalu coba lagi.');
            } finally {
                setCsAiLoading(false);
            }
        }

        applySavedCsAiSize();
        updateCsAiCounter();

        if (csAiToggleButton) {
            csAiToggleButton.addEventListener('click', toggleCsAiPanel);
        }

        if (csAiCloseButton) {
            csAiCloseButton.addEventListener('click', closeCsAiPanel);
        }

        if (csAiCompactButton) {
            csAiCompactButton.addEventListener('click', function () {
                setCsAiSize(csAiDefaultSize.width, csAiDefaultSize.height);
            });
        }

        if (csAiWideButton) {
            csAiWideButton.addEventListener('click', toggleCsAiLargeSize);
        }

        if (csAiForm) {
            csAiForm.addEventListener('submit', function (event) {
                event.preventDefault();

                if (csAiInput) {
                    sendCsAiMessage(csAiInput.value);
                }
            });
        }

        if (csAiInput) {
            csAiInput.addEventListener('input', autoResizeCsAiInput);

            csAiInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();

                    if (!isCsAiSending) {
                        sendCsAiMessage(csAiInput.value);
                    }
                }
            });
        }

        if (csAiQuickQuestions) {
            csAiQuickQuestions.querySelectorAll('.cs-ai-chip').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    if (!isCsAiSending) {
                        sendCsAiMessage(chip.dataset.question || chip.textContent);
                    }
                });
            });
        }

        window.addEventListener('resize', function () {
            if (!csAiPanel || window.innerWidth <= 576) {
                return;
            }

            const width = Math.min(csAiPanel.offsetWidth, window.innerWidth - 48);
            const height = Math.min(csAiPanel.offsetHeight, window.innerHeight - 118);

            csAiPanel.style.width = width + 'px';
            csAiPanel.style.height = height + 'px';
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