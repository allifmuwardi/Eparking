@extends('layouts.app')

@section('title', 'Notifikasi | Sistem Penanganan Kendala Parkir')
@section('page_title', 'Notifikasi')
@section('page_subtitle', 'Pemberitahuan aktivitas sistem dan update proses operasional')

@section('content')
@php
    $totalNotifications = $notifications->total();
    $unreadCount = $notifications->where('is_read', false)->count();
    $readCount = $notifications->where('is_read', true)->count();

    $typeLabel = function ($type) {
        return match ($type) {
            'report' => 'Laporan Kendala',
            'report_assignment' => 'Penugasan Teknisi',
            'report_update' => 'Update Penanganan',
            'backup_request' => 'Backup Barang',
            'traffic' => 'Traffic Harian',
            default => 'Notifikasi Sistem',
        };
    };

    $typeIcon = function ($type) {
        return match ($type) {
            'report' => 'bi-exclamation-triangle',
            'report_assignment' => 'bi-clipboard-check',
            'report_update' => 'bi-tools',
            'backup_request' => 'bi-box-seam',
            'traffic' => 'bi-bar-chart-line',
            default => 'bi-bell',
        };
    };

    $typeBadgeClass = function ($type) {
        return match ($type) {
            'report' => 'bg-danger',
            'report_assignment' => 'bg-primary',
            'report_update' => 'bg-info text-dark',
            'backup_request' => 'bg-warning text-dark',
            'traffic' => 'bg-success',
            default => 'bg-secondary',
        };
    };
@endphp

<style>
    .page-title-local {
        color: #071b4d;
        font-size: 26px;
        font-weight: 950;
        letter-spacing: -0.35px;
        margin-bottom: 6px;
    }

    .page-subtitle-local {
        color: #5f719a;
        font-size: 14px;
        font-weight: 650;
        line-height: 1.55;
        margin-bottom: 0;
    }

    .header-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        box-shadow: 0 14px 28px rgba(13, 110, 253, 0.22);
        flex-shrink: 0;
    }

    .section-title-local {
        color: #071b4d;
        font-size: 18px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .section-subtitle-local {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 650;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .btn-soft {
        border: 1px solid #d7e3f7;
        background: #ffffff;
        color: #071b4d;
        font-weight: 850;
    }

    .btn-soft:hover {
        background: #f3f8ff;
        border-color: #b9cbea;
        color: #0649bd;
    }

    .notification-hero {
        border-radius: 24px;
        padding: 24px;
        color: #ffffff;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.24), transparent 36%),
            linear-gradient(135deg, #0b3969 0%, #0649bd 55%, #0d6efd 100%);
        box-shadow: 0 22px 50px rgba(13, 110, 253, 0.20);
        overflow: hidden;
        position: relative;
    }

    .notification-hero::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -90px;
        bottom: -100px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .notification-hero-content {
        position: relative;
        z-index: 1;
    }

    .notification-hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 31px;
        flex-shrink: 0;
    }

    .notification-hero-label {
        color: rgba(255, 255, 255, 0.76);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .notification-hero-title {
        color: #ffffff;
        font-size: 24px;
        font-weight: 950;
        margin-bottom: 4px;
    }

    .notification-hero-subtitle {
        color: rgba(255, 255, 255, 0.84);
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 0;
        line-height: 1.6;
    }

    .summary-card {
        height: 100%;
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.05);
    }

    .summary-label {
        color: #7b8caf;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 6px;
    }

    .summary-value {
        color: #071b4d;
        font-size: 30px;
        font-weight: 950;
        line-height: 1.1;
        margin-bottom: 0;
    }

    .summary-help {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 650;
        margin-top: 6px;
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
    }

    .summary-icon.primary {
        background: #eaf3ff;
        color: #0d6efd;
    }

    .summary-icon.warning {
        background: #fff6dc;
        color: #d99a00;
    }

    .summary-icon.success {
        background: #e7f7ee;
        color: #198754;
    }

    .notification-card {
        border-radius: 20px;
        border: 1px solid #d7e3f7;
        background: #ffffff;
        padding: 18px;
        margin-bottom: 14px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.04);
        transition: 0.18s ease;
    }

    .notification-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.07);
    }

    .notification-card.unread {
        border-color: #b9cbea;
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, 0.10), transparent 36%),
            linear-gradient(180deg, #f8fbff, #ffffff);
    }

    .notification-icon {
        width: 52px;
        height: 52px;
        border-radius: 17px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
    }

    .notification-card.unread .notification-icon {
        background: linear-gradient(145deg, #1f6de2, #0649bd);
        color: #ffffff;
        box-shadow: 0 14px 26px rgba(13, 110, 253, 0.20);
    }

    .notification-title {
        color: #071b4d;
        font-size: 15px;
        font-weight: 950;
        margin-bottom: 5px;
    }

    .notification-message {
        color: #5f719a;
        font-size: 13px;
        font-weight: 650;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .notification-time {
        color: #7b8caf;
        font-size: 12px;
        font-weight: 700;
    }

    .empty-state {
        padding: 58px 16px;
        text-align: center;
        color: #7b8caf;
    }

    .empty-state-icon {
        width: 74px;
        height: 74px;
        border-radius: 25px;
        background: #eaf3ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin: 0 auto 16px;
    }

    @media (max-width: 768px) {
        .page-title-local {
            font-size: 22px;
        }

        .notification-hero-title {
            font-size: 21px;
        }

        .summary-value {
            font-size: 25px;
        }
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <i class="bi bi-bell"></i>
            </div>

            <div>
                <h3 class="page-title-local">Notifikasi</h3>
                <p class="page-subtitle-local">
                    Daftar pemberitahuan terkait laporan kendala, penugasan teknisi, traffic harian, dan backup barang.
                </p>
            </div>
        </div>

        @if ($totalNotifications > 0)
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                @csrf

                <button class="btn btn-primary rounded-3 px-3">
                    <i class="bi bi-check2-all me-1"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="notification-hero mb-4">
        <div class="notification-hero-content">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="notification-hero-icon">
                            <i class="bi bi-bell-fill"></i>
                        </div>

                        <div>
                            <div class="notification-hero-label">Pusat Informasi</div>
                            <div class="notification-hero-title">Update Aktivitas Sistem</div>
                            <p class="notification-hero-subtitle">
                                Notifikasi membantu setiap role mengetahui aktivitas terbaru, mulai dari laporan kendala,
                                penugasan teknisi, update status, permintaan backup barang, sampai traffic harian.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <span class="badge rounded-pill bg-light text-dark">
                            Total: {{ number_format($totalNotifications) }}
                        </span>

                        <span class="badge rounded-pill bg-warning text-dark">
                            Belum Dibaca: {{ number_format($unreadCount) }}
                        </span>

                        <span class="badge rounded-pill bg-success">
                            Dibaca: {{ number_format($readCount) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Total Notifikasi</div>
                        <h4 class="summary-value text-primary">{{ number_format($totalNotifications) }}</h4>
                        <div class="summary-help">Seluruh notifikasi akun</div>
                    </div>

                    <div class="summary-icon primary">
                        <i class="bi bi-bell"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Belum Dibaca</div>
                        <h4 class="summary-value text-warning">{{ number_format($unreadCount) }}</h4>
                        <div class="summary-help">Perlu ditinjau pengguna</div>
                    </div>

                    <div class="summary-icon warning">
                        <i class="bi bi-envelope-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="summary-label">Sudah Dibaca</div>
                        <h4 class="summary-value text-success">{{ number_format($readCount) }}</h4>
                        <div class="summary-help">Notifikasi sudah dibuka</div>
                    </div>

                    <div class="summary-icon success">
                        <i class="bi bi-envelope-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <h5 class="section-title-local">Daftar Notifikasi</h5>
                <p class="section-subtitle-local">
                    Klik tombol buka untuk membaca detail atau menuju halaman terkait.
                </p>
            </div>

            @if ($notifications->count() > 0)
                <div class="text-muted small fw-semibold">
                    Menampilkan
                    <b>{{ $notifications->firstItem() ?? 0 }}</b>
                    sampai
                    <b>{{ $notifications->lastItem() ?? 0 }}</b>
                    dari
                    <b>{{ $notifications->total() }}</b>
                    data
                </div>
            @endif
        </div>

        @forelse ($notifications as $notification)
            @php
                $isUnread = !$notification->is_read;
                $icon = $typeIcon($notification->type ?? null);
                $label = $typeLabel($notification->type ?? null);
                $badgeClass = $typeBadgeClass($notification->type ?? null);
            @endphp

            <div class="notification-card {{ $isUnread ? 'unread' : '' }}">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div class="d-flex gap-3 flex-grow-1">
                        <div class="notification-icon">
                            <i class="bi {{ $icon }}"></i>
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h6 class="notification-title mb-0">
                                    {{ $notification->title }}
                                </h6>

                                <span class="badge rounded-pill {{ $badgeClass }}">
                                    {{ $label }}
                                </span>

                                @if ($isUnread)
                                    <span class="badge rounded-pill bg-primary">
                                        Baru
                                    </span>
                                @endif
                            </div>

                            <p class="notification-message">
                                {{ $notification->message }}
                            </p>

                            <div class="notification-time">
                                <i class="bi bi-clock me-1"></i>
                                {{ $notification->created_at?->format('d M Y H:i') ?? '-' }} WIB
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap justify-content-end">
                        <a href="{{ route('notifications.read', $notification) }}" class="btn btn-sm btn-primary rounded-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Buka
                        </a>

                        <form
                            method="POST"
                            action="{{ route('notifications.destroy', $notification) }}"
                            onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-light border rounded-3">
                                <i class="bi bi-trash me-1"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-bell-slash"></i>
                </div>

                <h5 class="fw-bold mt-3 text-dark">Belum ada notifikasi</h5>
                <p class="mb-0">
                    Notifikasi aktivitas sistem akan muncul di halaman ini.
                </p>
            </div>
        @endforelse

        @if ($notifications->hasPages())
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection