<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Sertifikat #{{ $sertifikat->id }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 1.5rem;
            font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #e5e7eb;
            color: #111827;
        }
        .card-wrapper {
            max-width: 720px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
            padding: 1.75rem 2rem;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .brand-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .brand-logo {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background: #f97316;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
        }
        .brand-text-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
        }
        .brand-text-subtitle {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            border: 1px solid #fee2e2;
            background: #fef2f2;
            color: #b91c1c;
        }
        .card-body {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            gap: 1.5rem;
        }
        .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        .certificate-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.35rem;
        }
        .certificate-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 0.75rem 1.25rem;
            font-size: 0.8rem;
        }
        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #9ca3af;
            margin-bottom: 0.1rem;
        }
        .info-value {
            font-weight: 500;
            color: #111827;
        }
        .qr-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            padding: 0.75rem;
            border-radius: 0.9rem;
            border: 1px dashed #d1d5db;
            background: #f9fafb;
        }
        .qr-label {
            font-size: 0.7rem;
            font-weight: 500;
            color: #4b5563;
            text-align: center;
        }
        .footer {
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.7rem;
            color: #6b7280;
        }
        .actions {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            background: #f97316;
            color: #fff;
        }
        .btn-secondary {
            background: #fff;
            color: #4b5563;
            border-color: #d1d5db;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            .card {
                padding: 1.25rem 1.25rem 1.5rem;
            }
            .card-body {
                grid-template-columns: minmax(0, 1fr);
            }
            .footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .card-wrapper {
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="card-wrapper">
        <div class="card">
            <div class="card-header">
                <div class="brand-left">
                    <div class="brand-logo">
                        {{ strtoupper(substr(config('app.name', 'SMK'), 0, 2)) }}
                    </div>
                    <div>
                        <div class="brand-text-title">
                            {{ config('app.name', 'Sertifikat Digital SMK') }}
                        </div>
                        <div class="brand-text-subtitle">
                            Panel administrasi sertifikat siswa
                        </div>
                    </div>
                </div>
                <div>
                    <span class="badge">
                        <span>VERIFIKASI RESMI</span>
                        <span>#{{ $sertifikat->id }}</span>
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div>
                    <div class="section-title">Data sertifikat</div>
                    <div class="certificate-title">
                        {{ $sertifikat->judul_sertifikat }}
                    </div>
                    <div class="certificate-subtitle">
                        Jenis: <strong>{{ $sertifikat->jenis_sertifikat }}</strong>
                    </div>

                    <div class="info-grid">
                        <div>
                            <div class="info-label">Nama siswa</div>
                            <div class="info-value">
                                {{ $sertifikat->siswa->nama ?? 'Siswa tidak ditemukan' }}
                            </div>
                        </div>
                        <div>
                            <div class="info-label">NIS</div>
                            <div class="info-value">
                                {{ $sertifikat->siswa->nis ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Kelas</div>
                            <div class="info-value">
                                {{ $sertifikat->siswa->kelas ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Jurusan</div>
                            <div class="info-value">
                                {{ $sertifikat->siswa->jurusan ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="info-label">Tanggal diraih</div>
                            <div class="info-value">
                                {{ $sertifikat->tanggal_diraih ? \Carbon\Carbon::parse($sertifikat->tanggal_diraih)->translatedFormat('d F Y') : '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="info-label">ID sertifikat</div>
                            <div class="info-value">
                                #{{ $sertifikat->id }}
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="section-title">QR verifikasi</div>
                    <div class="qr-box">
                        <div id="qrcode"></div>
                        <div class="qr-label">
                            Pindai QR ini untuk memeriksa keaslian sertifikat
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div>
                    Dicetak dari sistem sertifikat digital sekolah.
                </div>
                <div>
                    Tanggal cetak: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="actions no-print">
                <button class="btn-secondary btn" onclick="window.history.back()">
                    ← Kembali
                </button>
                <button class="btn" onclick="window.print()">
                    🖨 Cetak / Simpan sebagai PDF
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        (function () {
            var el = document.getElementById('qrcode');
            if (!el || typeof QRCode === 'undefined') {
                return;
            }

            var url = @json(route('sertifikat.card', $sertifikat));

            new QRCode(el, {
                text: url,
                width: 96,
                height: 96,
                colorDark: "#111827",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        })();
    </script>
</body>
</html>
