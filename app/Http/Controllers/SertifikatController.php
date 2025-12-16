<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkStoreSertifikatRequest;
use App\Http\Requests\StoreSertifikatRequest;
use App\Http\Requests\UpdateSertifikatRequest;
use App\Exports\SiswaTemplateExport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Sertifikat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SertifikatController extends Controller
{
    public function index(Request $request): View
    {
        // Get filter parameters
        $search = $request->get('search', '');
        $jurusan = $request->get('jurusan', '');
        $angkatan = $request->get('angkatan', '');
        $kelas = $request->get('kelas', '');
        $status = $request->get('status', '');

        // Build query with filters
        $query = Siswa::withCount('sertifikats')->orderBy('nama');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nis', 'like', '%' . $search . '%')
                    ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        if ($jurusan) {
            $query->where('jurusan', $jurusan);
        }

        if ($angkatan) {
            $query->where('angkatan', $angkatan);
        }

        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $siswas = $query->paginate(10)->appends($request->query());

        // Sertifikat 12 bulan terakhir (untuk analitik sederhana)
        $sertifikatByMonth = Sertifikat::query()
            ->whereNotNull('tanggal_diraih')
            ->where('tanggal_diraih', '>=', now()->subYear()->startOfMonth())
            ->get()
            ->groupBy(function (Sertifikat $s) {
                return \Carbon\Carbon::parse($s->tanggal_diraih)->format('Y-m');
            })
            ->map(fn ($items) => $items->count())
            ->sortKeys();

        // Distribusi sertifikat per jurusan
        $sertifikatByJurusan = Sertifikat::with('siswa')
            ->get()
            ->groupBy(function (Sertifikat $s) {
                return $s->siswa?->jurusan ?: 'Tidak diisi';
            })
            ->map(fn ($items) => $items->count())
            ->sortDesc();

        $chartMonths = $sertifikatByMonth->keys()->map(function ($ym) {
            return \Carbon\Carbon::createFromFormat('Y-m', $ym)->translatedFormat('M Y');
        });

        $sertifikats = Sertifikat::with('siswa')
            ->orderByDesc('tanggal_diraih')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $totalSertifikasi = Sertifikat::count();
        $totalSiswa = Siswa::count();
        $totalAdminAktif = User::where('role', 'admin')->count();
        $totalSertifikatBulanIni = Sertifikat::whereMonth('tanggal_diraih', now()->month)
            ->whereYear('tanggal_diraih', now()->year)
            ->count();

        // Get unique values for filter dropdowns
        $jurusans = Siswa::whereNotNull('jurusan')
            ->distinct()
            ->pluck('jurusan')
            ->sort()
            ->values();

        $angkatans = Siswa::whereNotNull('angkatan')
            ->distinct()
            ->pluck('angkatan')
            ->sort()
            ->values();

        $kelases = Siswa::whereNotNull('kelas')
            ->distinct()
            ->pluck('kelas')
            ->sort()
            ->values();

        $statuses = Siswa::whereNotNull('status')
            ->distinct()
            ->pluck('status')
            ->sort()
            ->values();

        return view('dashboard', [
            'siswas' => $siswas,
            'sertifikats' => $sertifikats,
            'totalSertifikasi' => $totalSertifikasi,
            'totalSiswa' => $totalSiswa,
            'totalSertifikatBulanIni' => $totalSertifikatBulanIni,
            'totalAdminAktif' => $totalAdminAktif,
            'chartMonths' => $chartMonths,
            'chartValues' => $sertifikatByMonth->values(),
            'jurusanLabels' => $sertifikatByJurusan->keys(),
            'jurusanValues' => $sertifikatByJurusan->values(),
            'filters' => [
                'search' => $search,
                'jurusan' => $jurusan,
                'angkatan' => $angkatan,
                'kelas' => $kelas,
                'status' => $status,
            ],
            'filterOptions' => [
                'jurusans' => $jurusans,
                'angkatans' => $angkatans,
                'kelases' => $kelases,
                'statuses' => $statuses,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $siswas = Siswa::orderBy('nama')->get(['id', 'nama', 'nis', 'kelas', 'jurusan']);

        $kelasOptions = Siswa::query()
            ->select('kelas')
            ->whereNotNull('kelas')
            ->where('kelas', '<>', '')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $jurusanOptions = Siswa::query()
            ->select('jurusan')
            ->whereNotNull('jurusan')
            ->where('jurusan', '<>', '')
            ->distinct()
            ->orderBy('jurusan')
            ->pluck('jurusan');
        $prefilledSiswa = null;

        if ($request->filled('siswa_id')) {
            $prefilledSiswa = $siswas->firstWhere('id', (int) $request->input('siswa_id'));
        }

        if ($request->filled('nis')) {
            $prefilledSiswa = $siswas->firstWhere('nis', $request->input('nis'));
        }

        return view('sertifikat.create', compact('siswas', 'prefilledSiswa', 'kelasOptions', 'jurusanOptions'));
    }

    public function store(StoreSertifikatRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $siswa = Siswa::findOrFail($data['siswa_id']);

        $payload = [
            'nis' => $siswa->nis,
            'jenis_sertifikat' => $data['jenis_sertifikat'],
            'judul_sertifikat' => $data['judul_sertifikat'],
            'tanggal_diraih' => Carbon::parse($data['tanggal_diraih'])->toDateString(),
        ];

        if ($request->hasFile('foto_sertifikat')) {
            $payload['foto_sertifikat'] = $request->file('foto_sertifikat')->store('sertifikat_photos', 'public');
        }

        DB::transaction(fn () => Sertifikat::create($payload));

        $redirect = $request->input('redirect', 'detail');
        $route = $redirect === 'dashboard'
            ? route('dashboard')
            : route('siswa.show', $siswa);

        return redirect($route)->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function bulkStore(BulkStoreSertifikatRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $siswas = Siswa::whereIn('id', $data['siswa_ids'])->get();
            $tanggal = Carbon::parse($data['tanggal_diraih'])->toDateString();

            if ($siswas->isEmpty()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Siswa tidak ditemukan. Silakan periksa kembali pilihan siswa.');
            }

            $created = 0;
            $fotoPath = null;

            if ($request->hasFile('foto_sertifikat')) {
                $fotoPath = $request->file('foto_sertifikat')->store('sertifikat_photos', 'public');
            }

            DB::transaction(function () use ($siswas, $data, $tanggal, $fotoPath, &$created) {
                foreach ($siswas as $siswa) {
                    $payload = [
                        'nis' => $siswa->nis,
                        'jenis_sertifikat' => $data['jenis_sertifikat'],
                        'judul_sertifikat' => $data['judul_sertifikat'],
                        'tanggal_diraih' => $tanggal,
                    ];

                    if ($fotoPath) {
                        $payload['foto_sertifikat'] = $fotoPath;
                    }

                    Sertifikat::create($payload);
                    $created++;
                }
            });

            return redirect()
                ->route('dashboard')
                ->with('success', "Sertifikat berhasil ditambahkan untuk {$created} siswa.");
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan sertifikat untuk multi siswa. Silakan coba lagi atau hubungi admin. (' . $e->getMessage() . ')');
        }
    }

    public function storePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'sertifikat_id' => 'required|exists:sertifikats,id',
            'foto_sertifikat' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240'
        ]);

        $sertifikat = Sertifikat::findOrFail($request->sertifikat_id);

        $fotoPath = $request->file('foto_sertifikat')->store('sertifikat_photos', 'public');

        try {
            DB::transaction(function () use ($sertifikat, $fotoPath) {
                if ($sertifikat->foto_sertifikat) {
                    Storage::disk('public')->delete($sertifikat->foto_sertifikat);
                }

                $sertifikat->update(['foto_sertifikat' => $fotoPath]);
            });

            return redirect()->route('dashboard')->with('success', 'Foto sertifikat berhasil diperbarui!');
        } catch (\Exception $e) {
            Storage::disk('public')->delete($fotoPath);
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // 🔹 FITUR UPLOAD MASSAL FOTO BERDASARKAN NIS
    public function uploadMassal(Request $request)
    {
        // Check for validation errors first - return JSON instead of redirect
        try {
            $request->validate([
                'foto_sertifikat' => ['required', 'array'],
                'foto_sertifikat.*' => ['file', 'mimes:jpeg,jpg,png,pdf', 'max:10240'], // 10MB per file
                'jenis_sertifikat' => ['required', 'string', 'max:100'],
                'judul_sertifikat' => ['required', 'string', 'max:255'],
                'tanggal_diraih' => ['required', 'date', 'before_or_equal:today'],
            ], [
                'foto_sertifikat.required' => 'Silakan pilih minimal satu file sertifikat.',
                'foto_sertifikat.*.max' => 'File melebihi batas 10MB per file.',
                'foto_sertifikat.*.mimes' => 'Hanya format JPG/JPEG/PNG/PDF yang diterima.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors as JSON for fetch API
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'error' => implode(', ', $e->validator->errors()->all()),
                    'success' => false,
                    'skipped' => $e->validator->errors()->all()
                ], 422);
            }
            throw $e;
        }

        $jenis = $request->string('jenis_sertifikat')->toString();
        $judul = $request->string('judul_sertifikat')->toString();
        $tanggal = Carbon::parse($request->input('tanggal_diraih'))->toDateString();

        if (!$request->hasFile('foto_sertifikat') || !is_array($request->file('foto_sertifikat'))) {
            return response()->json([
                'error' => 'Tidak ada file yang terdeteksi. Pastikan Anda sudah memilih atau menyeret berkas ke area upload.',
                'success' => false,
                'skipped' => []
            ], 400);
        }

        $files = $request->file('foto_sertifikat');
        $ok = [];
        $skipped = [];

        // Process files WITHOUT transaction - allow partial success
        foreach ($files as $index => $file) {
            try {
                \Log::info("Processing file $index: " . $file->getClientOriginalName());
                
                // Extract NIS from filename (without extension)
                $nis = trim(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                \Log::info("Extracted NIS: '$nis' from file: {$file->getClientOriginalName()}");
                
                if ($nis === '') {
                    $skipped[] = "{$file->getClientOriginalName()} - Nama file tidak valid (NIS kosong)";
                    \Log::warning("File {$file->getClientOriginalName()} - NIS tidak valid");
                    continue;
                }

                // Find matching siswa
                $siswa = Siswa::where('nis', $nis)->first();
                if (!$siswa) {
                    $skipped[] = "{$file->getClientOriginalName()} - NIS '$nis' tidak ditemukan di database";
                    \Log::warning("Siswa with NIS '$nis' not found");
                    continue;
                }

                \Log::info("Found siswa: {$siswa->id} - {$siswa->nama}");

                // Validate file
                $ext = strtolower($file->getClientOriginalExtension());
                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();
                
                \Log::info("File details - Extension: $ext, MIME: $mimeType, Size: " . ($fileSize / 1024 / 1024) . "MB");
                
                // Check if file is readable
                if (!$file->isReadable()) {
                    $skipped[] = "{$file->getClientOriginalName()} - File tidak dapat dibaca";
                    \Log::error("File {$file->getClientOriginalName()} is not readable");
                    continue;
                }
                
                $filename = "{$nis}-" . now()->format('YmdHis') . "-" . Str::random(4) . ".{$ext}";
                \Log::info("Generated filename: $filename");
                
                // Store file
                try {
                    $path = $file->storeAs('sertifikat_photos', $filename, 'public');
                    if (!$path) {
                        $skipped[] = "{$file->getClientOriginalName()} - Gagal menyimpan file";
                        \Log::error("Failed to store file {$file->getClientOriginalName()}: storeAs returned null");
                        continue;
                    }
                    \Log::info("File stored at: $path");
                } catch (\Exception $storageError) {
                    $skipped[] = "{$file->getClientOriginalName()} - Error storage: " . $storageError->getMessage();
                    \Log::error("Storage error for {$file->getClientOriginalName()}: " . $storageError->getMessage());
                    continue;
                }

                // Create sertifikat record
                try {
                    DB::transaction(function () use ($siswa, $jenis, $judul, $tanggal, $path) {
                        Sertifikat::create([
                            'nis' => $siswa->nis,
                            'jenis_sertifikat' => $jenis,
                            'judul_sertifikat' => $judul,
                            'tanggal_diraih' => $tanggal,
                            'foto_sertifikat' => $path
                        ]);
                    });

                    $ok[] = "{$nis} - {$filename}";
                    \Log::info("✓ Sertifikat berhasil dibuat untuk NIS: $nis");
                } catch (\Throwable $dbError) {
                    \Log::error("Database error for file {$file->getClientOriginalName()}: " . $dbError->getMessage(), [
                        'nis' => $nis,
                        'trace' => $dbError->getTraceAsString()
                    ]);
                    $skipped[] = "{$file->getClientOriginalName()} - Database error: " . $dbError->getMessage();
                }
                
            } catch (\Throwable $fileError) {
                \Log::error("Error processing file $index: " . $fileError->getMessage(), [
                    'file' => $file->getClientOriginalName(),
                    'trace' => $fileError->getTraceAsString()
                ]);
                $skipped[] = "{$file->getClientOriginalName()} - Error: " . $fileError->getMessage();
            }
        }

        // Build response messages
        if (count($ok) === 0) {
            \Log::error("Upload failed: No files succeeded", ['skipped' => $skipped]);
            return response()->json([
                'error' => 'Semua file gagal diunggah. Pastikan nama file sesuai dengan NIS siswa (contoh: 252610002.pdf).',
                'skipped' => $skipped,
                'success' => false
            ], 400);
        }

        $msg = "✓ Upload berhasil! " . count($ok) . " file(s) diterima.";
        if (count($skipped) > 0) {
            $msg .= " ⚠ " . count($skipped) . " file(s) gagal.";
        }
        
        \Log::info("Upload complete. Success: " . count($ok) . ", Skipped: " . count($skipped));

        return response()->json([
            'success' => $msg,
            'skipped' => $skipped,
            'ok_count' => count($ok),
            'skipped_count' => count($skipped)
        ], 200);
    }
    // ===== Tambahan dari controller lama (Edit, Update, Destroy) =====
    public function edit(Sertifikat $sertifikat): View
    {
        $sertifikat->load('siswa');
        $siswas = Siswa::orderBy('nama')->get(['id', 'nama', 'nis']);

        return view('sertifikat.edit', compact('sertifikat', 'siswas'));
    }

    public function update(UpdateSertifikatRequest $request, Sertifikat $sertifikat): RedirectResponse
    {
        $data = $request->validated();
        $siswa = Siswa::findOrFail($data['siswa_id']);

        $payload = [
            'nis' => $siswa->nis,
            'jenis_sertifikat' => $data['jenis_sertifikat'],
            'judul_sertifikat' => $data['judul_sertifikat'],
            'tanggal_diraih' => Carbon::parse($data['tanggal_diraih'])->toDateString(),
        ];

        DB::transaction(function () use ($request, $sertifikat, $payload) {
            $updateData = $payload;

            if ($request->boolean('hapus_foto') && $sertifikat->foto_sertifikat) {
                Storage::disk('public')->delete($sertifikat->foto_sertifikat);
                $updateData['foto_sertifikat'] = null;
            }

            if ($request->hasFile('foto_sertifikat')) {
                if ($sertifikat->foto_sertifikat) {
                    Storage::disk('public')->delete($sertifikat->foto_sertifikat);
                }
                $updateData['foto_sertifikat'] = $request->file('foto_sertifikat')->store('sertifikat_photos', 'public');
            }

            $sertifikat->update($updateData);
        });

        $redirect = $request->input('redirect', 'detail');
        $route = $redirect === 'dashboard'
            ? route('dashboard')
            : route('siswa.show', $siswa);

        return redirect($route)->with('success', 'Data sertifikat berhasil diperbarui.');
    }

    public function destroy(Sertifikat $sertifikat): RedirectResponse
    {
        DB::transaction(function () use ($sertifikat) {
            if ($sertifikat->foto_sertifikat) {
                Storage::disk('public')->delete($sertifikat->foto_sertifikat);
            }

            $sertifikat->delete();
        });

        return redirect()->back()->with('success', 'Data sertifikat berhasil dihapus!');
    }

    // ====== Search & API ======
    public function search(): View
    {
        return view('sertifikat.search');
    }

    /**
     * Proses pencarian sertifikat (untuk form biasa)
     */
    public function doSearch(Request $request): View
    {
        $request->validate([
            'search_type' => 'required|in:nama,nis',
            'search_term' => 'required|string|max:255',
        ]);

        $query = Sertifikat::with('siswa');

        if ($request->search_type === 'nama') {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama', 'LIKE', "%{$request->search_term}%");
            });
        } else {
            // Cari berdasarkan NIS yang tersimpan di tabel sertifikats
            $query->where('nis', 'LIKE', "%{$request->search_term}%");
        }

        $results = $query->orderBy('tanggal_diraih', 'desc')->get();

        return view('sertifikat.results', [
            'results' => $results,
            'searchTerm' => $request->search_term,
            'searchType' => $request->search_type,
        ]);
    }

    /**
     * API pencarian sertifikat (untuk AJAX)
     */
    public function searchApi(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:nama,nis',
                'term' => 'required|string|max:255',
            ]);

            $query = Sertifikat::with('siswa');

            if ($request->type === 'nama') {
                $query->whereHas('siswa', function ($q) use ($request) {
                    $q->where('nama', 'LIKE', '%' . $request->term . '%');
                });
            } else {
                // Cari berdasarkan NIS yang tersimpan di tabel sertifikats
                $query->where('nis', 'LIKE', '%' . $request->term . '%');
            }

            $results = $query->orderBy('tanggal_diraih', 'desc')->limit(50)->get();

            $formattedResults = $results->map(function ($sertifikat) {
                return [
                    'id' => $sertifikat->id,
                    'jenis_sertifikat' => $sertifikat->jenis_sertifikat,
                    'judul_sertifikat' => $sertifikat->judul_sertifikat,
                    'tanggal_diraih' => $sertifikat->tanggal_diraih,
                    'foto_sertifikat' => $sertifikat->foto_sertifikat,
                    'nama_siswa' => $sertifikat->siswa ? $sertifikat->siswa->nama : 'N/A',
                    'nis' => $sertifikat->siswa ? $sertifikat->siswa->nis : 'N/A',
                    'kelas' => $sertifikat->siswa?->kelas,
                    'jurusan' => $sertifikat->siswa?->jurusan,
                ];
            });

            $hasSiswaTanpaSertifikat = false;
            $jumlahSiswaTanpaSertifikat = 0;

            if ($formattedResults->isEmpty()) {
                $siswaQuery = Siswa::query();

                if ($request->type === 'nama') {
                    $siswaQuery->where('nama', 'LIKE', '%' . $request->term . '%');
                } else {
                    $siswaQuery->where('nis', 'LIKE', '%' . $request->term . '%');
                }

                $jumlahSiswaTanpaSertifikat = $siswaQuery->count();
                $hasSiswaTanpaSertifikat = $jumlahSiswaTanpaSertifikat > 0;
            }

            return response()->json([
                'success' => true,
                'count' => $formattedResults->count(),
                'data' => $formattedResults,
                'meta' => [
                    'has_siswa_tanpa_sertifikat' => $hasSiswaTanpaSertifikat,
                    'jumlah_siswa_tanpa_sertifikat' => $jumlahSiswaTanpaSertifikat,
                    'siswa_tanpa_sertifikat' => [],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function publicShow(Sertifikat $sertifikat): JsonResponse
    {
        $sertifikat->load('siswa');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $sertifikat->id,
                'jenis_sertifikat' => $sertifikat->jenis_sertifikat,
                'judul_sertifikat' => $sertifikat->judul_sertifikat,
                'tanggal_diraih' => $sertifikat->tanggal_diraih,
                'foto_sertifikat' => $sertifikat->foto_sertifikat,
                'nama' => $sertifikat->siswa?->nama ?? 'N/A',
                'nis' => $sertifikat->siswa?->nis ?? 'N/A',
            ],
        ]);
    }

    public function show(Request $request, Sertifikat $sertifikat)
    {
        $sertifikat->load('siswa');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $sertifikat->id,
                    'jenis_sertifikat' => $sertifikat->jenis_sertifikat,
                    'judul_sertifikat' => $sertifikat->judul_sertifikat,
                    'tanggal_diraih' => $sertifikat->tanggal_diraih,
                    'foto_sertifikat' => $sertifikat->foto_sertifikat,
                    'nama' => $sertifikat->siswa?->nama ?? 'N/A',
                    'nis' => $sertifikat->siswa?->nis ?? 'N/A',
                ]
            ]);
        }

        return view('sertifikat.show', compact('sertifikat'));
    }

    public function card(Sertifikat $sertifikat)
    {
        $sertifikat->load('siswa');

        return view('sertifikat.card', compact('sertifikat'));
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate(['identifier' => 'required|string']);

        $sertifikat = Sertifikat::with('siswa')
            ->where('id', $request->identifier)
            ->orWhere('nis', $request->identifier)
            ->first();

        if (!$sertifikat) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat valid',
            'data' => [
                'id' => $sertifikat->id,
                'nis' => $sertifikat->nis,
                'jenis_sertifikat' => $sertifikat->jenis_sertifikat,
                'judul_sertifikat' => $sertifikat->judul_sertifikat,
                'tanggal_diraih' => $sertifikat->tanggal_diraih,
                'foto_sertifikat' => $sertifikat->foto_sertifikat,
                'nama' => $sertifikat->siswa?->nama ?? null,
            ],
        ]);
    }

    // ====== Import Excel ======

    public function downloadTemplate()
    {
        try {
            return Excel::download(new SiswaTemplateExport(), 'template-import-siswa.xlsx');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal mendownload template: ' . $e->getMessage());
        }
    }

    
    

}
