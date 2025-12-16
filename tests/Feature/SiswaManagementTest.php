<?php

namespace Tests\Feature;

use App\Models\Sertifikat;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiswaManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        return $user;
    }

    public function test_admin_can_create_siswa(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'nis' => 'NIS2024',
            'nama' => 'Rafi Ahmad',
            'status' => 'aktif',
        ];

        $response = $this->post(route('siswa.store'), $payload);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswas', $payload);
    }

    public function test_admin_can_update_siswa(): void
    {
        $this->actingAsAdmin();

        $siswa = Siswa::factory()->create([
            'nis' => 'NIS1234',
            'nama' => 'Nama Awal',
        ]);

        $payload = [
            'nis' => 'NIS9999',
            'nama' => 'Nama Terbaru',
            'status' => 'aktif',
        ];

        $response = $this->put(route('siswa.update', $siswa), $payload);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswas', $payload);
    }

    public function test_admin_can_delete_siswa_and_cleanup_certificates(): void
    {
        Storage::fake('public');
        $this->actingAsAdmin();

        $siswa = Siswa::factory()->create();
        $fotoPath = 'sertifikat_photos/test-foto.jpg';

        Storage::disk('public')->put($fotoPath, 'fake-content');

        Sertifikat::factory()
            ->forSiswa($siswa)
            ->create([
                'foto_sertifikat' => $fotoPath,
            ]);

        $response = $this->delete(route('siswa.destroy', $siswa));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('siswas', ['id' => $siswa->id]);
        $this->assertDatabaseMissing('sertifikats', ['foto_sertifikat' => $fotoPath]);
        Storage::disk('public')->assertMissing($fotoPath);
    }
}
