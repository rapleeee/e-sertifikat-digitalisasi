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
        'foto_sertifikat'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }
}
