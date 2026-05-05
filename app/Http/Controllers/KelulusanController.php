<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelulusanController extends Controller
{
    private const TIMEZONE = 'Asia/Jakarta';

    private const SETTING_KEY = 'kelulusan_pengumuman_dibuka_pada';

    private const NOTE_KEY = 'kelulusan_catatan_pengambilan_skl';

    private const DEFAULT_OPEN_AT = '2026-05-05 10:00:00';

    private const DEFAULT_NOTE = 'Pengambilan Surat Keterangan Lulus dapat dilakukan mulai tanggal 8 Mei 2026 di ruang TU. Silakan membawa bukti bebas administrasi dari bagian keuangan.';

    public function index(): View
    {
        $announcementAt = $this->announcementAt();

        return view('kelulusan.index', [
            'announcementAt' => $announcementAt,
            'canOpen' => now()->gte($announcementAt),
            'graduationNote' => $this->graduationNote(),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $announcementAt = $this->announcementAt();

        if (now()->lt($announcementAt)) {
            return response()->json([
                'message' => 'Pengumuman kelulusan belum dibuka.',
                'opens_at' => $announcementAt->toIso8601String(),
            ], 403);
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:nis,nama'],
            'query' => ['required', 'string', 'min:2', 'max:255'],
        ]);

        $queryText = trim($validated['query']);

        $query = Siswa::query()
            ->whereIn('status', ['lulus', 'tunda_lulus']);

        if ($validated['type'] === 'nis') {
            $query->where('nis', $queryText);
        } else {
            $query->where('nama', 'like', "%{$queryText}%");
        }

        $results = $query
            ->orderBy('nama')
            ->limit(10)
            ->get(['id', 'nama', 'nis', 'kelas', 'jurusan', 'status'])
            ->map(fn (Siswa $siswa) => [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'status' => $siswa->status,
                'keterangan' => $siswa->status === 'tunda_lulus'
                    ? "Kelulusan Anda {$siswa->nama} ditunda. Silakan datang ke sekolah menyelesaikan Ujian Pesat Method."
                    : null,
            ])
            ->values();

        return response()->json([
            'results' => $results,
        ]);
    }

    public function settings(): View
    {
        $announcementAt = $this->announcementAt();

        return view('kelulusan.settings', [
            'announcementAt' => $announcementAt,
            'graduationNote' => $this->graduationNote(),
            'totalLulus' => Siswa::query()->where('status', 'lulus')->count(),
            'totalTundaLulus' => Siswa::query()->where('status', 'tunda_lulus')->count(),
            'totalKelas12' => Siswa::query()->where('kelas', 'like', '%XII%')->count(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'announcement_date' => ['required', 'date_format:Y-m-d'],
            'announcement_time' => ['required', 'date_format:H:i'],
            'graduation_note' => ['required', 'string', 'max:1000'],
        ]);

        $announcementAt = Carbon::createFromFormat(
            'Y-m-d H:i',
            "{$validated['announcement_date']} {$validated['announcement_time']}",
            self::TIMEZONE
        );

        AppSetting::setValue(self::SETTING_KEY, $announcementAt->format('Y-m-d H:i:s'));
        AppSetting::setValue(self::NOTE_KEY, trim($validated['graduation_note']));

        return redirect()
            ->route('kelulusan.settings')
            ->with('success', 'Pengaturan cek kelulusan berhasil diperbarui.');
    }

    private function announcementAt(): Carbon
    {
        $value = AppSetting::valueFor(self::SETTING_KEY, self::DEFAULT_OPEN_AT);

        return Carbon::parse($value ?: self::DEFAULT_OPEN_AT, self::TIMEZONE);
    }

    private function graduationNote(): string
    {
        return AppSetting::valueFor(self::NOTE_KEY, self::DEFAULT_NOTE) ?: self::DEFAULT_NOTE;
    }
}
