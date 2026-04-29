<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SertifikatResource extends JsonResource
{
    /**
     * Transform the resource into an array matching the API contract.
     */
    public function toArray(Request $request): array
    {
        return [
            'nomor_sertifikat'    => $this->nomor_sertifikat,
            'nama_sertifikat'     => $this->nama_sertifikat,
            'penerbit'            => $this->penerbit,
            'tanggal_terbit'      => $this->tanggal_terbit?->format('Y-m-d'),
            'tanggal_kadaluarsa'  => $this->tanggal_kadaluarsa?->format('Y-m-d'),
            'url_sertifikat'      => $this->url_sertifikat,
            'kategori'            => $this->kategori,
        ];
    }
}
