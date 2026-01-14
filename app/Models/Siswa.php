<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'jenis_kelamin',
        'kelas',
        'jurusan',
        'angkatan',
        'status',
        'eligibilitas',
        'catatan_eligibilitas',
    ];

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class, 'nis', 'nis');
    }
}
