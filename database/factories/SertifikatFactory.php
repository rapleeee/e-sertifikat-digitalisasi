<?php

namespace Database\Factories;

use App\Models\Sertifikat;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sertifikat>
 */
class SertifikatFactory extends Factory
{
    protected $model = Sertifikat::class;

    public function definition(): array
    {
        $siswa = Siswa::factory()->create();

        return [
            'nis' => $siswa->nis,
            'jenis_sertifikat' => $this->faker->randomElement(['Kompetensi', 'BNSP', 'Prestasi', 'Internasional']),
            'judul_sertifikat' => $this->faker->sentence(3),
            'tanggal_diraih' => $this->faker->date(),
            'foto_sertifikat' => null,
        ];
    }

    public function forSiswa(Siswa $siswa): static
    {
        return $this->state(fn () => [
            'nis' => $siswa->nis,
        ]);
    }
}

