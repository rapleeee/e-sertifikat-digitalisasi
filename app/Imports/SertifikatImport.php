<?php

namespace App\Imports;

use App\Models\Sertifikat;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Collection;

class SertifikatImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public $data;

    public function collection(Collection $rows)
    {
        $this->data = $rows
            ->map(function ($row) {
                $nis = isset($row['nis']) ? trim((string) $row['nis']) : null;
                $nama = isset($row['nama']) ? trim((string) $row['nama']) : null;

                if (!$nis || !$nama) {
                    return null;
                }

                return [
                    'nisn' => isset($row['nisn']) ? trim((string) $row['nisn']) : null,
                    'nis' => $nis,
                    'nama' => $nama,
                    'jenis_kelamin' => isset($row['jenis_kelamin']) ? trim((string) $row['jenis_kelamin']) : null,
                    'kelas' => isset($row['kelas']) ? trim((string) $row['kelas']) : null,
                    'jurusan' => isset($row['jurusan']) ? trim((string) $row['jurusan']) : null,
                ];
            })
            ->filter()
            ->values();
    }

    public function rules(): array
    {
        return [
            'nis' => 'required',
            'nama' => 'required',
            'nisn' => 'nullable',
            'jenis_kelamin' => 'nullable',
            'kelas' => 'nullable',
            'jurusan' => 'nullable',
        ];
    }
}
