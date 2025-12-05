<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'max:50', 'unique:siswas,nis'],
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => ['nullable', 'string', 'max:50'],
            'jenis_kelamin' => ['nullable', 'string', 'max:20'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'angkatan' => ['nullable', 'string', 'max:10'],
            'status' => ['nullable', 'string', 'in:aktif,lulus,alumni'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nis' => $this->nis ? trim($this->nis) : null,
            'nama' => $this->nama ? trim($this->nama) : null,
            'nisn' => $this->nisn ? trim($this->nisn) : null,
            'jenis_kelamin' => $this->jenis_kelamin ? trim($this->jenis_kelamin) : null,
            'kelas' => $this->kelas ? trim($this->kelas) : null,
            'jurusan' => $this->jurusan ? trim($this->jurusan) : null,
            'angkatan' => $this->angkatan ? trim($this->angkatan) : null,
            'status' => $this->status ? trim($this->status) : null,
        ]);
    }
}
