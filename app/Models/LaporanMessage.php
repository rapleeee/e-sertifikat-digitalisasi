<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'sender_type',
        'sender_id',
        'message',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

