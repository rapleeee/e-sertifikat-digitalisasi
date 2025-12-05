<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSertifikatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'integer', 'exists:siswas,id'],
            'jenis_sertifikat' => ['required', 'string', 'max:150'],
            'judul_sertifikat' => ['required', 'string', 'max:255'],
            'tanggal_diraih' => ['required', 'date', 'before_or_equal:today'],
            'foto_sertifikat' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'hapus_foto' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'jenis_sertifikat' => $this->jenis_sertifikat ? trim($this->jenis_sertifikat) : null,
            'judul_sertifikat' => $this->judul_sertifikat ? trim($this->judul_sertifikat) : null,
            'hapus_foto' => filter_var($this->hapus_foto, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE),
        ]);
    }
}
