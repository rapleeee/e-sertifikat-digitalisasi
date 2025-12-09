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
    public function index(): View
    {
        $siswas = Siswa::withCount('sertifikats')
            ->orderBy('nama')
            ->paginate(10);

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
            'foto_sertifikat' => 'required|image|mimes:jpg,jpeg,png|max:2048'
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
        $request->validate([
            'foto_sertifikat' => ['required', 'array'],
            'foto_sertifikat.*' => ['file', 'image', 'mimes:jpeg,jpg,png,webp,JPEG,JPG', 'max:2048'],
            'jenis_sertifikat' => ['required', 'string', 'max:100'],
            'judul_sertifikat' => ['required', 'string', 'max:255'],
            'tanggal_diraih' => ['required', 'date', 'before_or_equal:today'],
        ], [
            'foto_sertifikat.required' => 'Silakan pilih minimal satu file sertifikat.',
            'foto_sertifikat.*.max' => 'Setiap file sertifikat maksimal 2MB. File yang melebihi batas tidak akan diunggah.',
            'foto_sertifikat.*.mimes' => 'Format file harus JPG/JPEG/PNG.',
        ]);

        $jenis = $request->string('jenis_sertifikat')->toString();
        $judul = $request->string('judul_sertifikat')->toString();
        $tanggal = Carbon::parse($request->input('tanggal_diraih'))->toDateString();

        if (!$request->hasFile('foto_sertifikat') || !is_array($request->file('foto_sertifikat'))) {
            return back()
                ->withInput()
                ->with('error', 'Tidak ada file yang terdeteksi. Pastikan kamu sudah memilih atau menyeret berkas ke area upload.');
        }

        $files = $request->file('foto_sertifikat');
        $ok = $skipped = [];

        try {
            DB::transaction(function () use ($files, $jenis, $judul, $tanggal, &$ok, &$skipped) {
                foreach ($files as $file) {
                    $nis = trim(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    if ($nis === '') {
                        $skipped[] = "Nama file {$file->getClientOriginalName()} tidak valid";
                        continue;
                    }

                    $siswa = Siswa::where('nis', $nis)->first();
                    if (!$siswa) {
                        $skipped[] = "{$nis} (siswa tidak ditemukan)";
                        continue;
                    }

                    $ext = strtolower($file->getClientOriginalExtension());
                    $filename = "{$nis}-" . now()->format('YmdHis') . "-" . Str::random(4) . ".{$ext}";
                    $path = $file->storeAs('sertifikat_photos', $filename, 'public');

                    Sertifikat::create([
                        'nis' => $siswa->nis,
                        'jenis_sertifikat' => $jenis,
                        'judul_sertifikat' => $judul,
                        'tanggal_diraih' => $tanggal,
                        'foto_sertifikat' => $path
                    ]);

                    $ok[] = "{$nis} → {$filename}";
                }
            });
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengunggah sertifikat: ' . $e->getMessage());
        }

        if (count($ok) === 0) {
            return back()
                ->withInput()
                ->with('error', 'Tidak ada sertifikat yang berhasil diunggah. Pastikan nama file sama persis dengan NIS siswa.')
                ->with('skipped', $skipped);
        }

        $msg = "Upload massal selesai. Berhasil: " . count($ok);
        if ($skipped) {
            $msg .= ". Dilewati: " . count($skipped);
        }

        return redirect()->route('dashboard')
            ->with('success', $msg)
            ->with('skipped', $skipped);
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
