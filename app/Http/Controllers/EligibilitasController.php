<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class EligibilitasController extends Controller
{
    /**
     * Display daftar siswa eligibilitas dengan grafik statistik
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filterEligibilitas = $request->input('eligibilitas', '');

        // Query siswa kelas 12 saja
        $query = Siswa::where('kelas', 'like', '%XII%');

        // Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter eligibilitas
        if ($filterEligibilitas) {
            $query->where('eligibilitas', $filterEligibilitas);
        }

        $siswas = $query->orderBy('nama')->paginate(15);

        // Statistik untuk grafik
        $totalSiswaKelas12 = Siswa::where('kelas', 'like', '%XII%')->count();
        $totalEligible = Siswa::where('kelas', 'like', '%XII%')
                              ->where('eligibilitas', 'eligible')
                              ->count();
        $totalTidakEligible = Siswa::where('kelas', 'like', '%XII%')
                                    ->where('eligibilitas', 'tidak_eligible')
                                    ->count();
        $totalBelumDitentukan = $totalSiswaKelas12 - $totalEligible - $totalTidakEligible;

        $persentaseEligible = $totalSiswaKelas12 > 0 
            ? round(($totalEligible / $totalSiswaKelas12) * 100, 2) 
            : 0;

        return view('eligibilitas.index', [
            'siswas' => $siswas,
            'search' => $search,
            'filterEligibilitas' => $filterEligibilitas,
            'totalSiswaKelas12' => $totalSiswaKelas12,
            'totalEligible' => $totalEligible,
            'totalTidakEligible' => $totalTidakEligible,
            'totalBelumDitentukan' => $totalBelumDitentukan,
            'persentaseEligible' => $persentaseEligible,
        ]);
    }

    /**
     * Show form untuk update status eligibilitas
     */
    public function edit($id)
    {
        $siswa = Siswa::where('kelas', 'like', '%XII%')->findOrFail($id);

        return view('eligibilitas.update', [
            'siswa' => $siswa,
        ]);
    }

    /**
     * Update status eligibilitas siswa
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::where('kelas', 'like', '%XII%')->findOrFail($id);

        $validated = $request->validate([
            'eligibilitas' => 'required|in:eligible,tidak_eligible',
            'catatan_eligibilitas' => 'nullable|string|max:500',
        ]);

        $siswa->update($validated);

        return redirect()->route('eligibilitas.index')
                       ->with('success', 'Status eligibilitas siswa berhasil diperbarui.');
    }

    /**
     * Display halaman bulk update eligibilitas
     */
    public function bulkIndex(Request $request)
    {
        $search = $request->input('search', '');
        $filterEligibilitas = $request->input('eligibilitas', '');
        $perPage = $request->input('per_page', 20);
        
        // Validasi per_page hanya terima nilai tertentu
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        // Query siswa kelas 12 saja
        $query = Siswa::where('kelas', 'like', '%XII%');

        // Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter eligibilitas
        if ($filterEligibilitas === 'null') {
            $query->whereNull('eligibilitas');
        } elseif ($filterEligibilitas) {
            $query->where('eligibilitas', $filterEligibilitas);
        }

        $siswas = $query->orderBy('nama')->paginate($perPage);

        return view('eligibilitas.bulk', [
            'siswas' => $siswas,
            'search' => $search,
            'filterEligibilitas' => $filterEligibilitas,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Bulk update status eligibilitas
     */
    public function bulkUpdate(Request $request)
    {
        $siswaIds = $request->input('siswa_ids', []);
        $bulkStatus = $request->input('bulk_status', '');
        
        // Individual status updates
        foreach ($request->except(['_token', '_method', 'siswa_ids', 'bulk_status']) as $key => $value) {
            if (strpos($key, 'status_') === 0 && $value) {
                $siswaId = str_replace('status_', '', $key);
                $siswa = Siswa::where('kelas', 'like', '%XII%')->find($siswaId);
                if ($siswa) {
                    $siswa->update(['eligibilitas' => $value]);
                }
            }
        }

        // Bulk status update if siswa_ids selected
        if (!empty($siswaIds) && $bulkStatus) {
            Siswa::where('kelas', 'like', '%XII%')
                 ->whereIn('id', $siswaIds)
                 ->update(['eligibilitas' => $bulkStatus]);
        }

        return redirect()->route('eligibilitas.bulk-index')
                       ->with('success', 'Status eligibilitas berhasil diperbarui.');
    }

    /**
     * Individual update status via AJAX
     */
    public function individualUpdate(Request $request)
    {
        $siswaId = $request->input('siswa_id');
        $status = $request->input('status');

        $siswa = Siswa::where('kelas', 'like', '%XII%')->find($siswaId);
        
        if ($siswa) {
            $siswa->update(['eligibilitas' => $status]);
            return response()->json(['success' => true, 'message' => 'Status updated']);
        }

        return response()->json(['success' => false, 'message' => 'Siswa not found'], 404);
    }

    /**
     * Public search API untuk pencarian eligibilitas
     */
    public function publicSearch(Request $request)
    {
        $searchType = $request->input('type', 'nama');
        $query = $request->input('query', '');

        if (empty($query)) {
            return response()->json(['results' => []]);
        }

        $siswas = Siswa::where('kelas', 'like', '%XII%');

        if ($searchType === 'nis') {
            $siswas = $siswas->where('nis', 'like', "%{$query}%");
        } else {
            $siswas = $siswas->where('nama', 'like', "%{$query}%");
        }

        $results = $siswas->select('id', 'nama', 'nis', 'kelas', 'jurusan', 'eligibilitas', 'catatan_eligibilitas')
                         ->orderBy('nama')
                         ->limit(20)
                         ->get()
                         ->toArray();

        return response()->json(['results' => $results]);
    }
}