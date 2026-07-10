@php
    $isPdf = $sertifikat->foto_sertifikat && str_ends_with(strtolower($sertifikat->foto_sertifikat), '.pdf');
    $fileUrl = $sertifikat->foto_sertifikat ? asset('storage/' . $sertifikat->foto_sertifikat) : null;
    $verifyUrl = route('sertifikat.verifikasi', $sertifikat);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Sertifikat #{{ $sertifikat->id }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', system-ui, -apple-system, sans-serif;
            background: #e5e7eb;
            color: #111827;
            padding: 1.5rem;
        }
        .wrapper { max-width: 860px; margin: 0 auto; }

        /* Card shell */
        .card {
            background: #fff;
            border-radius: 1.25rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(15,23,42,.12);
            overflow: hidden;
        }

        /* Header */
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .brand { display: flex; align-items: center; gap: .75rem; }
        .brand-icon {
            width: 2.75rem; height: 2.75rem;
            border-radius: .75rem;
            background: #f97316;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 1.1rem;
            flex-shrink: 0;
        }
        .brand-name { font-size: .9rem; font-weight: 600; color: #111827; }
        .brand-sub  { font-size: .72rem; color: #6b7280; }
        .badge {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .35rem .85rem; border-radius: 999px;
            font-size: .68rem; font-weight: 600;
            border: 1px solid #fee2e2; background: #fef2f2; color: #b91c1c;
            white-space: nowrap;
        }

        /* Certificate container with overlay */
        .cert-container { position: relative; display: block; background: #f3f4f6; }
        .cert-container img  { width: 100%; height: auto; display: block; }
        .cert-container .pdf-embed { width: 100%; height: 640px; border: none; display: block; }

        /* QR stamp overlay — bottom-right corner */
        .qr-stamp {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.97);
            border: 2.5px solid #111827;
            padding: 10px 10px 7px;
            box-shadow: 4px 4px 0 #111827;
            z-index: 20;
            pointer-events: none;
        }
        .qr-stamp-label {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            text-align: center;
            color: #374151;
            margin-top: 5px;
        }

        /* No-file fallback */
        .card-body {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            gap: 1.5rem;
            padding: 1.5rem 1.75rem;
        }
        .section-title {
            font-size: .78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .12em;
            color: #6b7280; margin-bottom: .5rem;
        }
        .cert-title { font-size: 1.1rem; font-weight: 600; color: #111827; margin-bottom: .25rem; }
        .cert-sub   { font-size: .78rem; color: #6b7280; margin-bottom: 1rem; }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .65rem 1.25rem;
            font-size: .8rem;
        }
        .info-label { font-size: .68rem; text-transform: uppercase; letter-spacing: .12em; color: #9ca3af; margin-bottom: .1rem; }
        .info-value { font-weight: 500; color: #111827; }
        .qr-box {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: .6rem; padding: .75rem;
            border-radius: .9rem; border: 1px dashed #d1d5db; background: #f9fafb;
        }
        .qr-box-label { font-size: .68rem; font-weight: 500; color: #4b5563; text-align: center; }

        /* Footer */
        .card-footer {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: .85rem 1.75rem;
            border-top: 1px solid #e5e7eb;
            font-size: .68rem; color: #6b7280;
        }

        /* Actions */
        .actions { display: flex; justify-content: flex-end; gap: .5rem; padding: .85rem 1.75rem 1.25rem; }
        .btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .5rem .95rem; border-radius: 999px;
            font-size: .75rem; font-weight: 500; cursor: pointer;
            border: 1px solid transparent;
            background: #f97316; color: #fff;
        }
        .btn-secondary { background: #fff; color: #4b5563; border-color: #d1d5db; }

        @media (max-width: 640px) {
            body { padding: 1rem; }
            .card-body { grid-template-columns: 1fr; }
            .card-footer { flex-direction: column; align-items: flex-start; }
        }
        @page { margin: 0; size: auto; }
        @media print {
            body { background: #fff; padding: 0; margin: 0; }
            .no-print { display: none !important; }
            /* Sembunyikan chrome card saat cetak — hanya canvas yang terprint */
            .card-header, .card-footer {
                display: none !important;
            }
            .card { box-shadow: none; border-radius: 0; border: none; background: transparent; }
            .wrapper { max-width: none; margin: 0; padding: 0; }
            /* Setiap wrapper div = satu lembar kertas */
            #pdf-render-container > div {
                page-break-after: always;
                break-after: page;
                page-break-inside: avoid;
                break-inside: avoid;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                margin: 0;
                padding: 0;
            }
            #pdf-render-container > div:last-child {
                page-break-after: avoid;
                break-after: avoid;
            }
            #pdf-render-container canvas {
                max-width: 100% !important;
                max-height: 100vh !important;
                width: auto !important;
                height: auto !important;
            }
            .pdf-loading-msg { display: none !important; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        {{-- Header --}}
        <div class="card-header">
            <div class="brand">
                <div class="brand-icon">{{ strtoupper(substr(config('app.name', 'SMK'), 0, 2)) }}</div>
                <div>
                    <div class="brand-name">{{ config('app.name', 'Sertifikat Digital SMK') }}</div>
                    <div class="brand-sub">{{ $sertifikat->judul_sertifikat }}</div>
                </div>
            </div>
            <span class="badge">SERTIFIKAT #{{ $sertifikat->id }}</span>
        </div>

        @if ($fileUrl)
            @if ($isPdf)
                {{-- ── PDF: render via PDF.js sebagai canvas, QR ditanam langsung di canvas ── --}}
                <div id="pdf-render-container" class="cert-container" style="background:#f3f4f6; min-height: 300px;">
                    <div id="pdf-loading" class="pdf-loading-msg" style="padding:3rem; text-align:center; color:#6b7280; font-size:.9rem;">
                        Memuat sertifikat...
                    </div>
                </div>
            @else
                {{-- ── Image: CSS QR stamp overlay ── --}}
                <div class="cert-container">
                    <img src="{{ $fileUrl }}" alt="Sertifikat {{ $sertifikat->judul_sertifikat }}">
                    <div class="qr-stamp">
                        <div id="qrcode"></div>
                        <div class="qr-stamp-label">Scan verifikasi</div>
                    </div>
                </div>
            @endif
        @else
            {{-- ── No file: info card + QR side-by-side ── --}}
            <div class="card-body">
                <div>
                    <div class="section-title">Data sertifikat</div>
                    <div class="cert-title">{{ $sertifikat->judul_sertifikat }}</div>
                    <div class="cert-sub">Jenis: <strong>{{ $sertifikat->jenis_sertifikat }}</strong></div>
                    <div class="info-grid">
                        <div><div class="info-label">Nama siswa</div><div class="info-value">{{ $sertifikat->siswa->nama ?? '-' }}</div></div>
                        <div><div class="info-label">NIS</div><div class="info-value">{{ $sertifikat->siswa->nis ?? '-' }}</div></div>
                        <div><div class="info-label">Kelas</div><div class="info-value">{{ $sertifikat->siswa->kelas ?? '-' }}</div></div>
                        <div><div class="info-label">Jurusan</div><div class="info-value">{{ $sertifikat->siswa->jurusan ?? '-' }}</div></div>
                        <div><div class="info-label">Tanggal diraih</div><div class="info-value">{{ $sertifikat->tanggal_diraih ? \Carbon\Carbon::parse($sertifikat->tanggal_diraih)->translatedFormat('d F Y') : '-' }}</div></div>
                        <div><div class="info-label">ID sertifikat</div><div class="info-value">#{{ $sertifikat->id }}</div></div>
                    </div>
                </div>
                <div>
                    <div class="section-title">QR Verifikasi</div>
                    <div class="qr-box">
                        <div id="qrcode"></div>
                        <div class="qr-box-label">Pindai untuk memeriksa keaslian sertifikat</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Footer --}}
        <div class="card-footer">
            <span>Dicetak dari sistem sertifikat digital sekolah.</span>
            <span>Tanggal cetak: {{ now()->format('d/m/Y H:i') }}</span>
        </div>

        {{-- Actions --}}
        <div class="actions no-print">
            <button class="btn btn-secondary" onclick="window.close()">✕ Tutup</button>
            <button class="btn" id="printBtn" onclick="window.print()">🖨 Cetak / Simpan sebagai PDF</button>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@if ($fileUrl && $isPdf)
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
(function () {
    const PDF_URL   = @json($fileUrl);
    const VERIFY_URL = @json($verifyUrl);

    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    /* ── Render QR ke off-screen canvas, kembalikan HTMLCanvasElement ── */
    function buildQRCanvas(url) {
        return new Promise(function (resolve) {
            var tmp = document.createElement('div');
            tmp.style.cssText = 'position:fixed;top:-9999px;left:-9999px;';
            document.body.appendChild(tmp);

            new QRCode(tmp, {
                text: url, width: 128, height: 128,
                colorDark: '#111827', colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });

            // QRCode.js bisa async, tunggu sebentar
            setTimeout(function () {
                var c = tmp.querySelector('canvas');
                document.body.removeChild(tmp);
                resolve(c);
            }, 200);
        });
    }

    /* ── Gambar QR stamp di sudut kanan bawah canvas PDF ── */
    function stampQR(pdfCanvas, qrCanvas) {
        var ctx   = pdfCanvas.getContext('2d');
        var W     = pdfCanvas.width;
        var H     = pdfCanvas.height;
        var pad   = 12;
        var qrSz  = Math.round(W * 0.09);   // ~9% lebar halaman
        qrSz      = Math.max(72, Math.min(qrSz, 115));
        var labelH = Math.round(qrSz * 0.10); // lebih kecil
        var boxW  = qrSz + pad * 2;
        var boxH  = qrSz + pad + labelH + pad;
        var bx    = W - boxW - 18;
        var by    = H - boxH - 18;

        // Drop shadow
        ctx.save();
        ctx.shadowColor   = 'rgba(0,0,0,.3)';
        ctx.shadowBlur    = 8;
        ctx.shadowOffsetX = 3;
        ctx.shadowOffsetY = 3;
        ctx.fillStyle = '#fff';
        ctx.fillRect(bx, by, boxW, boxH);
        ctx.restore();

        // Border
        ctx.strokeStyle = '#111827';
        ctx.lineWidth   = Math.max(2, W * 0.003);
        ctx.strokeRect(bx, by, boxW, boxH);

        // QR image
        if (qrCanvas) {
            ctx.drawImage(qrCanvas, bx + pad, by + pad, qrSz, qrSz);
        }

        // Label
        ctx.fillStyle  = '#374151';
        ctx.font       = 'bold ' + labelH + 'px system-ui,sans-serif';
        ctx.textAlign  = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('SCAN VERIFIKASI', bx + boxW / 2, by + qrSz + pad + labelH / 2 + 2);
    }

    /* ── Main render ── */
    async function renderPDF() {
        var container = document.getElementById('pdf-render-container');
        var loading   = document.getElementById('pdf-loading');

        try {
            var qrCanvas = await buildQRCanvas(VERIFY_URL);
            var pdf      = await pdfjsLib.getDocument(PDF_URL).promise;

            loading && loading.remove();

            var printBtn = document.getElementById('printBtn');
            if (printBtn) printBtn.disabled = true;

            for (var i = 1; i <= pdf.numPages; i++) {
                var page     = await pdf.getPage(i);
                var scale    = 1200 / page.getViewport({ scale: 1 }).width; // target 1200px lebar
                var viewport = page.getViewport({ scale: scale });

                var canvas   = document.createElement('canvas');
                canvas.width  = viewport.width;
                canvas.height = viewport.height;
                canvas.style.cssText = 'width:100%;display:block;';

                var wrapper = document.createElement('div');
                wrapper.style.cssText = 'position:relative;display:block;width:100%;';
                wrapper.appendChild(canvas);
                container.appendChild(wrapper);

                await page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise;

                // QR stamp di setiap halaman pertama
                if (i === 1) {
                    stampQR(canvas, qrCanvas);
                }
            }

            if (printBtn) printBtn.disabled = false;

        } catch (err) {
            console.error('PDF.js error:', err);
            if (loading) {
                loading.innerHTML =
                    'Gagal memuat sertifikat. ' +
                    '<a href="' + PDF_URL + '" target="_blank" style="color:#f97316;text-decoration:underline;">Buka PDF langsung →</a>';
            }
        }
    }

    // Tunggu QRCode.js siap
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof QRCode !== 'undefined') {
            renderPDF();
        } else {
            // Retry setelah QRCode.js load
            var qrScript = document.querySelector('script[src*="qrcodejs"]');
            if (qrScript) qrScript.addEventListener('load', renderPDF);
            else renderPDF();
        }
    });
})();
</script>
@else
<script>
    // Image atau no-file: render QR biasa
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('qrcode');
        if (!el || typeof QRCode === 'undefined') return;
        new QRCode(el, {
            text: @json($verifyUrl),
            width: 96, height: 96,
            colorDark: '#111827', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
@endif
</body>
</html>
