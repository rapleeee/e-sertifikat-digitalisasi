<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\EligibilitasController;
use App\Http\Controllers\KelulusanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sitemap.xml', function () {
    $lastmod = now()->toAtomString();
    $urls = [
        ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'],
        ['loc' => route('pencarian.eligible'), 'changefreq' => 'daily', 'priority' => '0.9'],
        ['loc' => route('kelulusan.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
        ['loc' => route('pencarian.sertifikat'), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['loc' => route('laporan.public.form'), 'changefreq' => 'weekly', 'priority' => '0.6'],
        ['loc' => route('tim.profil'), 'changefreq' => 'monthly', 'priority' => '0.5'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    foreach ($urls as $item) {
        $loc = htmlspecialchars($item['loc'], ENT_QUOTES, 'UTF-8');
        $changefreq = htmlspecialchars($item['changefreq'], ENT_QUOTES, 'UTF-8');
        $priority = htmlspecialchars($item['priority'], ENT_QUOTES, 'UTF-8');

        $xml .= '  <url>' . PHP_EOL;
        $xml .= "    <loc>{$loc}</loc>" . PHP_EOL;
        $xml .= "    <lastmod>{$lastmod}</lastmod>" . PHP_EOL;
        $xml .= "    <changefreq>{$changefreq}</changefreq>" . PHP_EOL;
        $xml .= "    <priority>{$priority}</priority>" . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $host = parse_url(config('app.url'), PHP_URL_HOST) ?: request()->getHost();
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /dashboard\n";
    $content .= "Disallow: /api/\n\n";
    $content .= "Host: {$host}\n";
    $content .= 'Sitemap: ' . url('/sitemap.xml') . "\n";

    return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('robots');

Route::view('/tim-pengembang', 'tim')->name('tim.profil');

Route::get('/pencarian-sertifikat', function () {
    return view('sertiuser.index');
})->name('pencarian.sertifikat');

Route::get('/pencarian-eligible', function () {
    return view('elegibleuser.index');
})->name('pencarian.eligible');

Route::get('/pencarian-eligible/test', function () {
    return view('elegibleuser.test');
})->name('pencarian.eligible.test');

Route::get('/cek-kelulusan', [KelulusanController::class, 'index'])->name('kelulusan.index');

Route::get('/laporan', [LaporanController::class, 'publicForm'])->name('laporan.public.form');
Route::post('/laporan', [LaporanController::class, 'publicStore'])->name('laporan.public.store');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard accessible by all authenticated users
    Route::get('/dashboard', [SertifikatController::class, 'index'])->name('dashboard');

    // Sertifikat & Siswa management - accessible by super_admin, admin, guru
    Route::middleware('check-manage-access')->group(function () {
        Route::get('/tambah-sertifikat', [SertifikatController::class, 'create'])->name('tambah.sertifikat');

        Route::get('/sertifikat/upload', function () {
            return view('sertifikat.upload');
        })->name('sertifikat.upload');

        Route::post('/sertifikat/upload', [SertifikatController::class, 'storePhoto'])->name('sertifikat.upload.post');
        Route::post('/sertifikat/upload-massal', [SertifikatController::class, 'uploadMassal'])->name('sertifikat.upload.massal');

        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('create', [SertifikatController::class, 'create'])->name('create');
            Route::post('store', [SertifikatController::class, 'store'])->name('store');
            Route::post('bulk-store', [SertifikatController::class, 'bulkStore'])->name('bulk-store');
            Route::get('{sertifikat}/detail', [SertifikatController::class, 'show'])->name('show');
            Route::get('{sertifikat}/edit', [SertifikatController::class, 'edit'])->name('edit');
            Route::put('{sertifikat}', [SertifikatController::class, 'update'])->name('update');
            Route::delete('{sertifikat}', [SertifikatController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('', [SiswaController::class, 'index'])->name('index');
            Route::get('create', [SiswaController::class, 'create'])->name('create');
            Route::post('', [SiswaController::class, 'store'])->name('store');
            Route::delete('bulk-destroy', [SiswaController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::post('bulk-promote', [SiswaController::class, 'bulkPromote'])->name('bulk-promote');
            Route::post('bulk-graduate', [SiswaController::class, 'bulkGraduate'])->name('bulk-graduate');
            Route::get('{siswa}', [SiswaController::class, 'show'])->name('show');
            Route::get('{siswa}/edit', [SiswaController::class, 'edit'])->name('edit');
            Route::put('{siswa}', [SiswaController::class, 'update'])->name('update');
            Route::delete('{siswa}', [SiswaController::class, 'destroy'])->name('destroy');
        });

        // Eligibilitas routes
        Route::prefix('eligibilitas')->name('eligibilitas.')->group(function () {
            Route::get('', [EligibilitasController::class, 'index'])->name('index');
            Route::get('update', [EligibilitasController::class, 'bulkIndex'])->name('bulk-index');
            Route::put('update', [EligibilitasController::class, 'bulkUpdate'])->name('bulk-update');
            Route::post('individual-update', [EligibilitasController::class, 'individualUpdate'])->name('individual-update');
            Route::get('{siswa}/edit', [EligibilitasController::class, 'edit'])->name('edit');
            Route::put('{siswa}', [EligibilitasController::class, 'update'])->name('update');
        });

        Route::get('/pengaturan-kelulusan', [KelulusanController::class, 'settings'])->name('kelulusan.settings');
        Route::put('/pengaturan-kelulusan', [KelulusanController::class, 'updateSettings'])->name('kelulusan.settings.update');

        Route::get('/sertifikat/import', [SiswaController::class, 'importForm'])->name('sertifikat.import.form');
        Route::post('/sertifikat/import', [SiswaController::class, 'importExcel'])->name('sertifikat.import.excel');
        Route::get('/sertifikat/preview', [SiswaController::class, 'previewImport'])->name('sertifikat.preview');
        Route::post('/sertifikat/confirm-import', [SiswaController::class, 'confirmImport'])->name('sertifikat.import.confirm');
    });

    // Admin only - manage users & laporan (super_admin & admin only)
    Route::middleware('can:manage-users')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/laporan-admin', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan-admin/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::post('/laporan-admin/{laporan}/reply', [LaporanController::class, 'reply'])->name('laporan.reply');
        Route::patch('/laporan-admin/{laporan}/status', [LaporanController::class, 'updateStatus'])->name('laporan.update-status');

        // Backup Database
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup', [BackupController::class, 'create'])->name('backup.create');
        Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
        Route::get('/backup/{filename}/download', [BackupController::class, 'download'])->name('backup.download');
        Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
    });

    // Profile accessible by all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/sertifikat/import/template', [SertifikatController::class, 'downloadTemplate'])
    ->name('sertifikat.import.template');

// Route baru untuk pencarian
Route::get('/cari-sertifikat', [SertifikatController::class, 'search'])->name('sertifikat.search');
Route::post('/cari-sertifikat', [SertifikatController::class, 'doSearch'])->name('sertifikat.do-search');

// API Routes untuk AJAX
Route::post(
    '/api/sertifikat/search',
    [SertifikatController::class, 'searchApi']
)->name('sertifikat.search.api')->middleware('throttle:30,1');

Route::get(
    '/api/pencarian-eligible',
    [EligibilitasController::class, 'publicSearch']
)->name('eligible.search.api')->middleware('throttle:30,1');

Route::get(
    '/api/kelulusan/search',
    [KelulusanController::class, 'search']
)->name('kelulusan.search.api')->middleware('throttle:30,1');

Route::get(
    '/api/sertifikat/{sertifikat}',
    [SertifikatController::class, 'publicShow']
)->name('sertifikat.public.show')->middleware('throttle:60,1');

Route::get('/sertifikat/{sertifikat}/kartu', [SertifikatController::class, 'card'])->name('sertifikat.card');
Route::post(
    '/api/sertifikat/verify',
    [SertifikatController::class, 'verify']
)->name('sertifikat.verify')->middleware('throttle:60,1');

require __DIR__.'/auth.php';
