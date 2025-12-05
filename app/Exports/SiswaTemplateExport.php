<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        // Satu baris contoh kosong agar semua kolom terlihat jelas di Excel
        return [
            [
                'NISN' => '',
                'NIS' => '',
                'Nama' => '',
                'Jenis Kelamin' => '',
                'Kelas' => '',
                'Jurusan' => '',
            ],
        ];
    }

    /**
     * Header kolom yang harus diisi pada file Excel.
     */
    public function headings(): array
    {
        return [
            'NISN',
            'NIS',
            'Nama',
            'Jenis Kelamin',
            'Kelas',
            'Jurusan',
        ];
    }
}
