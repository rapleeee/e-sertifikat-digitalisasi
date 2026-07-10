@php
    $isPdf = $sertifikat->foto_sertifikat && str_ends_with(strtolower($sertifikat->foto_sertifikat), '.pdf');
    $fileUrl = $sertifikat->foto_sertifikat ? asset('storage/' . $sertifikat->foto_sertifikat) : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat #{{ $sertifikat->id }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', system-ui, -apple-system, sans-serif;
            background: #f3f4f6;
            color: #111827;
            padding: 1.5rem;
            min-height: 100vh;
        }
        .wrapper { max-width: 860px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem; }

        /* Verified banner */
        .verified-banner {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: #dcfce7;
            border: 2px solid #16a34a;
            border-radius: .85rem;
        }
        .verified-icon {
            width: 2.5rem; height: 2.5rem;
            border-radius: 50%;
            background: #16a34a;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem; font-weight: 700;
            flex-shrink: 0;
        }
        .verified-title { font-size: .95rem; font-weight: 700; color: #15803d; }
        .verified-sub   { font-size: .73rem; color: #166534; margin-top: .15rem; }

        /* Certificate display — original, no overlay */
        .cert-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 1.1rem;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,.08);
        }
        .cert-card img       { width: 100%; height: auto; display: block; }
        .cert-card .pdf-embed { width: 100%; height: 640px; border: none; display: block; }

        /* No file state */
        .no-file {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #6b7280;
        }
        .no-file p { font-size: .9rem; font-weight: 500; }

        /* Info section */
        .info-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 1.1rem;
            padding: 1.25rem 1.5rem;
        }
        .info-card-title {
            font-size: .78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .12em;
            color: #374151; margin-bottom: .85rem;
            padding-bottom: .6rem; border-bottom: 1px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .75rem 1.5rem;
            font-size: .8rem;
        }
        .info-label { font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; color: #9ca3af; margin-bottom: .15rem; }
        .info-value { font-weight: 500; color: #111827; }

        /* Footer note */
        .footer-note {
            text-align: center;
            font-size: .68rem;
            color: #9ca3af;
            padding-bottom: .5rem;
        }

        @media (max-width: 600px) {
            body { padding: 1rem; }
            .cert-card .pdf-embed { height: 420px; }
            .info-grid { grid-template-columns: 1fr; }
            .verified-banner { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ── Verified banner ── --}}
    <div class="verified-banner">
        <div class="verified-icon">✓</div>
        <div>
            <div class="verified-title">Sertifikat Terverifikasi</div>
            <div class="verified-sub">
                Sertifikat ini terdaftar dan valid dalam sistem {{ config('app.name') }}.
                ID: #{{ $sertifikat->id }}
            </div>
        </div>
    </div>

    {{-- ── Original certificate — NO QR overlay ── --}}
    <div class="cert-card">
        @if ($fileUrl)
            @if ($isPdf)
                <embed src="{{ $fileUrl }}" type="application/pdf" class="pdf-embed">
            @else
                <img src="{{ $fileUrl }}" alt="Sertifikat {{ $sertifikat->judul_sertifikat }}">
            @endif
        @else
            <div class="no-file">
                <p>File sertifikat tidak tersedia.</p>
                <p style="margin-top:.5rem; font-size:.78rem;">Hubungi admin sekolah untuk mendapatkan salinan file sertifikat.</p>
            </div>
        @endif
    </div>

    {{-- ── Info card ── --}}
    <div class="info-card">
        <div class="info-card-title">Informasi Sertifikat</div>
        <div class="info-grid">
            <div>
                <div class="info-label">Nama siswa</div>
                <div class="info-value">{{ $sertifikat->siswa->nama ?? '-' }}</div>
            </div>
            <div>
                <div class="info-label">NIS</div>
                <div class="info-value">{{ $sertifikat->siswa->nis ?? '-' }}</div>
            </div>
            <div>
                <div class="info-label">Judul sertifikat</div>
                <div class="info-value">{{ $sertifikat->judul_sertifikat }}</div>
            </div>
            <div>
                <div class="info-label">Jenis</div>
                <div class="info-value">{{ $sertifikat->jenis_sertifikat }}</div>
            </div>
            <div>
                <div class="info-label">Tanggal diraih</div>
                <div class="info-value">
                    {{ $sertifikat->tanggal_diraih
                        ? \Carbon\Carbon::parse($sertifikat->tanggal_diraih)->translatedFormat('d F Y')
                        : '-' }}
                </div>
            </div>
            <div>
                <div class="info-label">ID sertifikat</div>
                <div class="info-value">#{{ $sertifikat->id }}</div>
            </div>
        </div>
    </div>

    <div class="footer-note">
        Halaman Resmi Verifikasi Sertifikat &mdash; SMK Informatika Pesat
    </div>

</div>
</body>
</html>
