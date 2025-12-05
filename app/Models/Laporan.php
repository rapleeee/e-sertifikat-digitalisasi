<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'nis',
        'subject',
        'status',
    ];

    public function messages()
    {
        return $this->hasMany(LaporanMessage::class);
    }
}

