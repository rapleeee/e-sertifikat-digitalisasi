<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreSertifikatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['integer', 'exists:siswas,id'],
            'jenis_sertifikat' => ['required', 'string', 'max:150'],
            'judul_sertifikat' => ['required', 'string', 'max:255'],
            'tanggal_diraih' => ['required', 'date', 'before_or_equal:today'],
            'foto_sertifikat' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:10240'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $ids = $this->input('siswa_ids', []);

        if (is_array($ids)) {
            $ids = array_values(array_unique(array_filter($ids, fn ($value) => $value !== null && $value !== '')));
        }

        $this->merge([
            'siswa_ids' => $ids,
            'jenis_sertifikat' => $this->jenis_sertifikat ? trim($this->jenis_sertifikat) : null,
            'judul_sertifikat' => $this->judul_sertifikat ? trim($this->judul_sertifikat) : null,
        ]);
    }
}
