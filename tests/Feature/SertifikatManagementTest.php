<?php

namespace Tests\Feature;

use App\Models\Sertifikat;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SertifikatManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        return $user;
    }

    public function test_admin_can_create_single_certificate_with_photo(): void
    {
        Storage::fake('public');
        $this->actingAsAdmin();

        $siswa = Siswa::factory()->create();
        $file = UploadedFile::fake()->image('sertifikat.jpg');

        $response = $this->post(route('sertifikat.store'), [
            'siswa_id' => $siswa->id,
            'jenis_sertifikat' => 'Kompetensi',
            'judul_sertifikat' => 'Juara 1 Kompetisi UI/UX',
            'tanggal_diraih' => now()->toDateString(),
            'foto_sertifikat' => $file,
            'redirect' => 'dashboard',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $sertifikat = Sertifikat::first();

        $this->assertNotNull($sertifikat);
        $this->assertSame($siswa->nis, $sertifikat->nis);
        $this->assertSame('Kompetensi', $sertifikat->jenis_sertifikat);
        Storage::disk('public')->assertExists($sertifikat->foto_sertifikat);
    }

    public function test_admin_can_bulk_create_certificates(): void
    {
        $this->actingAsAdmin();

        $siswas = Siswa::factory()->count(3)->create();

        $response = $this->post(route('sertifikat.bulk-store'), [
            'siswa_ids' => $siswas->pluck('id')->all(),
            'jenis_sertifikat' => 'Prestasi',
            'judul_sertifikat' => 'Sertifikat Webinar Nasional',
            'tanggal_diraih' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertEquals(3, Sertifikat::count());
        $this->assertTrue(
            Sertifikat::whereIn('nis', $siswas->pluck('nis'))->count() === 3
        );
    }

    public function test_admin_can_update_certificate_and_replace_photo(): void
    {
        Storage::fake('public');
        $this->actingAsAdmin();

        $siswa = Siswa::factory()->create();
        $oldPath = 'sertifikat_photos/lama.jpg';
        Storage::disk('public')->put($oldPath, 'old-content');

        $sertifikat = Sertifikat::factory()
            ->forSiswa($siswa)
            ->create([
                'jenis_sertifikat' => 'Kompetensi',
                'judul_sertifikat' => 'Sertifikat Lama',
                'tanggal_diraih' => now()->subYear()->toDateString(),
                'foto_sertifikat' => $oldPath,
            ]);

        $newFile = UploadedFile::fake()->image('baru.png');

        $response = $this->put(route('sertifikat.update', $sertifikat), [
            'siswa_id' => $siswa->id,
            'jenis_sertifikat' => 'BNSP',
            'judul_sertifikat' => 'Sertifikat Terbaru',
            'tanggal_diraih' => now()->toDateString(),
            'foto_sertifikat' => $newFile,
            'redirect' => 'dashboard',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $sertifikat->refresh();
        $this->assertSame('BNSP', $sertifikat->jenis_sertifikat);
        $this->assertSame('Sertifikat Terbaru', $sertifikat->judul_sertifikat);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($sertifikat->foto_sertifikat);
    }
}
