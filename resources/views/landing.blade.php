<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EParking - Sistem Penanganan Kendala Parkir</title>

    <meta name="description" content="EParking adalah sistem informasi berbasis web untuk pelaporan, monitoring, dan penanganan kendala operasional parkir secara digital.">
    <meta name="keywords" content="EParking, Elite Parkir, Sistem Penanganan Kendala Parkir, Pelaporan Kendala Parkir, Monitoring Parkir, Aplikasi Parkir">
    <meta name="author" content="ELITE Parkir">

    <meta property="og:title" content="EParking - Sistem Penanganan Kendala Parkir">
    <meta property="og:description" content="Sistem informasi berbasis web untuk pelaporan, monitoring, dan penanganan kendala operasional parkir.">
    <meta property="og:type" content="website">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.18), transparent 35%),
                linear-gradient(135deg, #f8fbff 0%, #eef5ff 45%, #ffffff 100%);
            font-family: Arial, sans-serif;
            color: #071b4d;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 70px 0;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: #eaf3ff;
            color: #0d6efd;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 56px;
            line-height: 1.05;
            font-weight: 900;
            letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .hero-title span {
            color: #0d6efd;
        }

        .hero-desc {
            color: #5f719a;
            font-size: 18px;
            line-height: 1.7;
            max-width: 650px;
            margin-bottom: 32px;
        }

        .feature-card {
            border: 1px solid #d7e3f7;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(12px);
            padding: 22px;
            height: 100%;
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.06);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 14px;
        }

        .system-card {
            border-radius: 30px;
            border: 1px solid #d7e3f7;
            background: #ffffff;
            box-shadow: 0 28px 70px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .system-card-header {
            background: linear-gradient(135deg, #1f6de2, #0649bd);
            color: white;
            padding: 26px;
        }

        .mock-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-bottom: 1px solid #edf3fc;
        }

        .mock-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: #eaf3ff;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-main {
            padding: 13px 22px;
            border-radius: 14px;
            font-weight: 800;
        }

        .footer-note {
            color: #7b8caf;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="brand-badge">
                    <i class="bi bi-p-square-fill"></i>
                    EParking System
                </div>

                <h1 class="hero-title">
                    Sistem Penanganan Kendala Parkir <span>Berbasis Web</span>
                </h1>

                <p class="hero-desc">
                    EParking membantu proses pelaporan, monitoring, penugasan teknisi,
                    permintaan backup, traffic harian, dan rekap operasional parkir
                    menjadi lebih cepat, rapi, dan terdokumentasi secara digital.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-main">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Masuk Sistem
                    </a>

                </div>

                <div class="footer-note">
                    Sistem internal untuk mendukung operasional parkir secara digital dan terkontrol.
                </div>
            </div>

            <div class="col-lg-5">
                <div class="system-card">
                    <div class="system-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white text-primary rounded-4 d-flex align-items-center justify-content-center"
                                 style="width: 52px; height: 52px;">
                                <i class="bi bi-speedometer2 fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Dashboard EParking</h5>
                                <div class="small opacity-75">Monitoring Kendala Operasional</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-2">
                        <div class="mock-row">
                            <div class="mock-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Pelaporan Kendala</div>
                                <div class="text-muted small">Input dan monitoring laporan parkir.</div>
                            </div>
                        </div>

                        <div class="mock-row">
                            <div class="mock-icon">
                                <i class="bi bi-tools"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Penanganan Teknisi</div>
                                <div class="text-muted small">Assign dan update status penanganan.</div>
                            </div>
                        </div>

                        <div class="mock-row">
                            <div class="mock-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Permintaan Backup</div>
                                <div class="text-muted small">Approval dan pengelolaan stok barang.</div>
                            </div>
                        </div>

                        <div class="mock-row border-0">
                            <div class="mock-icon">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Laporan Rekap</div>
                                <div class="text-muted small">Export dan evaluasi operasional.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="fitur" class="row g-3 mt-5">
            <div class="col-md-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h6 class="fw-bold">Pelaporan Kendala</h6>
                    <p class="text-muted small mb-0">
                        Petugas dapat melaporkan kendala operasional parkir secara digital.
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <h6 class="fw-bold">Traffic Harian</h6>
                    <p class="text-muted small mb-0">
                        Pencatatan kendaraan masuk, keluar, transaksi, dan pendapatan harian.
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <h6 class="fw-bold">Penugasan Teknisi</h6>
                    <p class="text-muted small mb-0">
                        Manajer dapat menugaskan teknisi dan memantau progress pekerjaan.
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h6 class="fw-bold">Rekap Operasional</h6>
                    <p class="text-muted small mb-0">
                        Rekap laporan dan export data untuk kebutuhan evaluasi operasional.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>