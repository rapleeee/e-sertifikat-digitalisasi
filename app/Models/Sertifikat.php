<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'nis',
        'jenis_sertifikat',
        'judul_sertifikat',
        'tanggal_diraih',
        'foto_sertifikat',
        // API contract fields
        'nomor_sertifikat',
        'nama_sertifikat',
        'penerbit',
        'tanggal_terbit',
        'tanggal_kadaluarsa',
        'url_sertifikat',
        'kategori',
    ];

    protected $casts = [
        'tanggal_diraih'      => 'date',
        'tanggal_terbit'      => 'date',
        'tanggal_kadaluarsa'  => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }
}
