@extends('layouts.app')

@section('title', 'Notifikasi | Sistem Penanganan Kendala Parkir')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Notifikasi</h3>
            <p class="text-muted mb-0">Daftar pemberitahuan terkait aktivitas sistem Anda.</p>
        </div>

        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button class="btn btn-primary rounded-3">
                <i class="bi bi-check2-all me-1"></i>
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <div class="page-card p-4">
        @forelse ($notifications as $notification)
            <div class="border rounded-4 p-3 mb-3 {{ !$notification->is_read ? 'bg-primary bg-opacity-10 border-primary' : 'bg-white' }}">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div class="d-flex gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center {{ !$notification->is_read ? 'bg-primary text-white' : 'bg-light text-primary' }}"
                             style="width: 48px; height: 48px; min-width: 48px;">
                            @if ($notification->type === 'report')
                                <i class="bi bi-exclamation-triangle"></i>
                            @elseif ($notification->type === 'report_assignment')
                                <i class="bi bi-clipboard-check"></i>
                            @elseif ($notification->type === 'report_update')
                                <i class="bi bi-tools"></i>
                            @elseif ($notification->type === 'backup_request')
                                <i class="bi bi-box-seam"></i>
                            @elseif ($notification->type === 'traffic')
                                <i class="bi bi-bar-chart-line"></i>
                            @else
                                <i class="bi bi-bell"></i>
                            @endif
                        </div>

                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h6 class="fw-bold mb-0">{{ $notification->title }}</h6>

                                @if (!$notification->is_read)
                                    <span class="badge bg-primary">Baru</span>
                                @endif
                            </div>

                            <p class="text-muted mb-2">
                                {{ $notification->message }}
                            </p>

                            <div class="small text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $notification->created_at?->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap justify-content-end">
                        <a href="{{ route('notifications.read', $notification) }}" class="btn btn-sm btn-primary rounded-3">
                            Buka
                        </a>

                        <form method="POST" action="{{ route('notifications.destroy', $notification) }}" onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-light border rounded-3">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-bell-slash" style="font-size: 54px;"></i>
                <h5 class="fw-bold mt-3">Belum ada notifikasi</h5>
                <p class="mb-0">Notifikasi aktivitas sistem akan muncul di halaman ini.</p>
            </div>
        @endforelse

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection