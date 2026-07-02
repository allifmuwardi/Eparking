<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akses Ditolak | Sistem Penanganan Kendala Parkir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #eff6ff, #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .error-card {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 28px;
            padding: 42px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .error-icon {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background: #fee2e2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            margin: 0 auto 22px;
        }

        .error-code {
            font-size: 72px;
            font-weight: 900;
            color: #0d6efd;
            line-height: 1;
        }

        .btn-primary {
            border-radius: 14px;
            padding: 12px 18px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <div class="error-code">403</div>

        <h3 class="fw-bold mt-3">Akses Ditolak</h3>

        <p class="text-muted mt-2">
            Anda tidak memiliki hak akses untuk membuka halaman ini.
            Silakan kembali ke dashboard sesuai role akun Anda.
        </p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
            <i class="bi bi-speedometer2 me-1"></i>
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>