<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $filterJenisKelamin = $request->input('jenis_kelamin');
        $filterKelas = $request->input('kelas');
        $filterJurusan = $request->input('jurusan');
        $filterAngkatan = $request->input('angkatan');
        $filterStatus = $request->input('status');

        $siswas = Siswa::query()
            ->withCount('sertifikats')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $inner) use ($search) {
                    $inner->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('nis', 'LIKE', "%{$search}%");
                });
            })
            ->when($filterJenisKelamin, function (Builder $query) use ($filterJenisKelamin) {
                $query->where('jenis_kelamin', $filterJenisKelamin);
            })
            ->when($filterKelas, function (Builder $query) use ($filterKelas) {
                $query->where('kelas', $filterKelas);
            })
            ->when($filterJurusan, function (Builder $query) use ($filterJurusan) {
                $query->where('jurusan', $filterJurusan);
            })
            ->when($filterAngkatan, function (Builder $query) use ($filterAngkatan) {
                $query->where('angkatan', $filterAngkatan);
            })
            ->when($filterStatus, function (Builder $query) use ($filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

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

        $jenisKelaminOptions = Siswa::query()
            ->select('jenis_kelamin')
            ->whereNotNull('jenis_kelamin')
            ->where('jenis_kelamin', '<>', '')
            ->distinct()
            ->orderBy('jenis_kelamin')
            ->pluck('jenis_kelamin');

        $angkatanOptions = Siswa::query()
            ->select('angkatan')
            ->whereNotNull('angkatan')
            ->where('angkatan', '<>', '')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        return view('siswa.index', [
            'siswas' => $siswas,
            'search' => $search,
            'filterJenisKelamin' => $filterJenisKelamin,
            'filterKelas' => $filterKelas,
            'filterJurusan' => $filterJurusan,
            'filterAngkatan' => $filterAngkatan,
            'filterStatus' => $filterStatus,
            'kelasOptions' => $kelasOptions,
            'jurusanOptions' => $jurusanOptions,
            'jenisKelaminOptions' => $jenisKelaminOptions,
            'angkatanOptions' => $angkatanOptions,
        ]);
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        Siswa::create($request->validated());

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('sertifikats');

        $logs = $siswa->activityLogs()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('siswa.detail', compact('siswa', 'logs'));
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $siswa->update($request->validated());

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $nama = $siswa->nama;

        DB::transaction(function () use ($siswa) {
            $siswa->sertifikats()->each(function ($sertifikat) {
                if ($sertifikat->foto_sertifikat) {
                    Storage::disk('public')->delete($sertifikat->foto_sertifikat);
                }
            });
            $siswa->delete();
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', "Data siswa {$nama} beserta sertifikatnya berhasil dihapus.");
    }

    public function importForm()
    {
        return view('sertifikat.import');
    }


    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        try {
            $import = new \App\Imports\SertifikatImport;
            Excel::import($import, $request->file('file'));

            $importedData = $import->data ?? collect();

            if ($importedData instanceof \Illuminate\Support\Collection) {
                $importedData = $importedData->toArray();
            }

            $request->session()->put('imported_sertifikats', $importedData);

            // Simpan informasi error per baris jika ada
            if (method_exists($import, 'failures')) {
                $failures = $import->failures();
                if ($failures && count($failures)) {
                    $request->session()->put('import_failures', $failures->toArray());
                } else {
                    $request->session()->forget('import_failures');
                }
            }

            return redirect()->route('sertifikat.preview')->with('success', 'File Excel berhasil diunggah. Silakan tinjau data sebelum diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
    }

    public function previewImport(Request $request)
    {
        return view('sertifikat.preview');
    }

    public function confirmImport(Request $request)
    {
        $sertifikats = $request->input('sertifikats');

        if (empty($sertifikats)) {
            return redirect()->route('sertifikat.import.form')->with('error', 'Tidak ada data untuk diimpor.');
        }

        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($sertifikats, &$created, &$updated) {
            foreach ($sertifikats as $data) {
                $nis = isset($data['nis']) ? trim((string) $data['nis']) : null;
                $nama = isset($data['nama']) ? trim((string) $data['nama']) : null;
                $nisn = isset($data['nisn']) ? trim((string) $data['nisn']) : null;
                $jenisKelamin = isset($data['jenis_kelamin']) ? trim((string) $data['jenis_kelamin']) : null;
                $kelas = isset($data['kelas']) ? trim((string) $data['kelas']) : null;
                $jurusan = isset($data['jurusan']) ? trim((string) $data['jurusan']) : null;

                if (!$nis || !$nama) {
                    continue;
                }

                $siswa = Siswa::updateOrCreate(
                    ['nis' => $nis],
                    [
                        'nama' => $nama,
                        'nisn' => $nisn,
                        'jenis_kelamin' => $jenisKelamin,
                        'kelas' => $kelas,
                        'jurusan' => $jurusan,
                    ]
                );

                $siswa->wasRecentlyCreated ? $created++ : $updated++;
            }
        });

        $request->session()->forget('imported_sertifikats');

        $message = 'Semua data berhasil diproses.';
        if ($created > 0) {
            $message = "{$created} siswa baru ditambahkan.";
        }
        if ($updated > 0) {
            $message .= " {$updated} siswa diperbarui.";
        }

        return redirect()->route('siswa.index')->with('success', trim($message));
    }

    public function search(Request $request)
    {
        $query = $request->get('search');
        
        $siswas = Siswa::where('nama', 'LIKE', "%{$query}%")
                      ->orWhere('nis', 'LIKE', "%{$query}%")
                      ->orderBy('nama')
                      ->paginate(15);

        return view('siswa.index', compact('siswas'))->with('search', $query);
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);

        if (empty($ids)) {
            return redirect()
                ->route('siswa.index')
                ->with('error', 'Tidak ada data siswa yang dipilih.');
        }

        $deletedCount = 0;

        DB::transaction(function () use ($ids, &$deletedCount) {
            $siswas = Siswa::with('sertifikats')->whereIn('id', $ids)->get();

            foreach ($siswas as $siswa) {
                $siswa->sertifikats->each(function ($sertifikat) {
                    if ($sertifikat->foto_sertifikat) {
                        Storage::disk('public')->delete($sertifikat->foto_sertifikat);
                    }
                });

                $siswa->delete();
                $deletedCount++;
            }
        });

        if ($deletedCount === 0) {
            return redirect()
                ->route('siswa.index')
                ->with('error', 'Tidak ada data siswa yang dihapus.');
        }

        return redirect()
            ->route('siswa.index')
            ->with('success', "{$deletedCount} data siswa beserta sertifikatnya berhasil dihapus.");
    }

    public function bulkPromote(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        $kelasBaru = trim((string) $request->input('kelas_baru', ''));

        if (empty($ids) || $kelasBaru === '') {
            return redirect()
                ->route('siswa.index')
                ->with('error', 'Pilih minimal satu siswa dan kelas tujuan.');
        }

        $updatedCount = 0;

        DB::transaction(function () use ($ids, $kelasBaru, &$updatedCount) {
            $siswas = Siswa::whereIn('id', $ids)->get();

            foreach ($siswas as $siswa) {
                $siswa->update([
                    'kelas' => $kelasBaru,
                ]);
                $updatedCount++;
            }
        });

        if ($updatedCount === 0) {
            return redirect()
                ->route('siswa.index')
                ->with('error', 'Tidak ada data siswa yang diperbarui.');
        }

        return redirect()
            ->route('siswa.index')
            ->with('success', "Kelas berhasil diubah ke {$kelasBaru} untuk {$updatedCount} siswa.");
    }

}
