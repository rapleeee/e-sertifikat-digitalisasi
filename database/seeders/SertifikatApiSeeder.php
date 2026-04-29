<?php

namespace Database\Seeders;

use App\Models\Sertifikat;
use Illuminate\Database\Seeder;

class SertifikatApiSeeder extends Seeder
{
    /**
     * Sample certificate records for testing the GET /api/sertifikat endpoint.
     *
     * NIS values used:
     *   - 12345678  (two certificates)
     *   - 87654321  (one certificate)
     *   - 11223344  (one certificate, no expiry / no URL)
     */
    public function run(): void
    {
        $records = [
            [
                'nis'                 => '12345678',
                'nomor_sertifikat'    => 'CERT-2024-001',
                'nama_sertifikat'     => 'Sertifikat Kompetensi Teknik Komputer',
                'penerbit'            => 'BNSP',
                'tanggal_terbit'      => '2024-06-01',
                'tanggal_kadaluarsa'  => '2027-06-01',
                'url_sertifikat'      => 'https://sertifikat.smkpesat.id/download/cert-001.pdf',
                'kategori'            => 'Kompetensi',
                // legacy web fields kept intact
                'jenis_sertifikat'    => 'Kompetensi',
                'judul_sertifikat'    => 'Sertifikat Kompetensi Teknik Komputer',
                'tanggal_diraih'      => '2024-06-01',
            ],
            [
                'nis'                 => '12345678',
                'nomor_sertifikat'    => 'CERT-2024-002',
                'nama_sertifikat'     => 'Sertifikat Keahlian Jaringan Komputer',
                'penerbit'            => 'BNSP',
                'tanggal_terbit'      => '2024-09-15',
                'tanggal_kadaluarsa'  => '2027-09-15',
                'url_sertifikat'      => 'https://sertifikat.smkpesat.id/download/cert-002.pdf',
                'kategori'            => 'Kompetensi',
                'jenis_sertifikat'    => 'Kompetensi',
                'judul_sertifikat'    => 'Sertifikat Keahlian Jaringan Komputer',
                'tanggal_diraih'      => '2024-09-15',
            ],
            [
                'nis'                 => '87654321',
                'nomor_sertifikat'    => 'CERT-2024-003',
                'nama_sertifikat'     => 'Sertifikat Desain Grafis',
                'penerbit'            => 'LSP Grafika',
                'tanggal_terbit'      => '2024-03-20',
                'tanggal_kadaluarsa'  => '2027-03-20',
                'url_sertifikat'      => 'https://sertifikat.smkpesat.id/download/cert-003.pdf',
                'kategori'            => 'Kompetensi',
                'jenis_sertifikat'    => 'Kompetensi',
                'judul_sertifikat'    => 'Sertifikat Desain Grafis',
                'tanggal_diraih'      => '2024-03-20',
            ],
            [
                'nis'                 => '11223344',
                'nomor_sertifikat'    => 'CERT-2023-010',
                'nama_sertifikat'     => 'Sertifikat Prestasi Olimpiade TIK',
                'penerbit'            => null,
                'tanggal_terbit'      => '2023-11-05',
                'tanggal_kadaluarsa'  => null,
                'url_sertifikat'      => null,
                'kategori'            => 'Prestasi',
                'jenis_sertifikat'    => 'Prestasi',
                'judul_sertifikat'    => 'Sertifikat Prestasi Olimpiade TIK',
                'tanggal_diraih'      => '2023-11-05',
            ],
        ];

        foreach ($records as $record) {
            Sertifikat::firstOrCreate(
                ['nomor_sertifikat' => $record['nomor_sertifikat']],
                $record
            );
        }
    }
}
