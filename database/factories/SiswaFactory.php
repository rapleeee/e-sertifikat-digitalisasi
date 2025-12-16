<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        return [
            'nis' => strtoupper($this->faker->unique()->bothify('NIS####')),
            'nama' => $this->faker->name(),
            'status' => 'aktif',
        ];
    }
}

