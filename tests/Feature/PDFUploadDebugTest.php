<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PDFUploadDebugTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    #[Test]
    public function uploadPdfFileAndCheckResponse()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Siswa::create(['nis' => '252610002', 'nama' => 'Test Student', 'status' => 'aktif']);

        $pdfFile = UploadedFile::fake()->create('252610002.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post('/sertifikat/upload-massal', [
            'foto_sertifikat' => [$pdfFile],
            'jenis_sertifikat' => 'Kompetensi',
            'judul_sertifikat' => 'Test PDF Upload',
            'tanggal_diraih' => now()->subDay()->toDateString(),
        ]);

        // Check response structure
        $response->assertStatus(200);
        $data = $response->json();
        
        dump('Response:', $data);
        
        // Should have success field
        $this->assertArrayHasKey('success', $data);
        
        // Check database
        $this->assertDatabaseHas('sertifikats', ['nis' => '252610002']);
        $this->assertEquals(1, Sertifikat::count());
    }

    #[Test]
    public function uploadPdfWithMultiplePagesTooBig()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Siswa::create(['nis' => '252610003', 'nama' => 'Test Student 2', 'status' => 'aktif']);

        // Simulate large PDF
        $largePdf = UploadedFile::fake()->create('252610003.pdf', 50000, 'application/pdf');

        $response = $this->actingAs($user)->post('/sertifikat/upload-massal', [
            'foto_sertifikat' => [$largePdf],
            'jenis_sertifikat' => 'BNSP',
            'judul_sertifikat' => 'Large PDF Test',
            'tanggal_diraih' => now()->subDay()->toDateString(),
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        
        dump('Large PDF Response:', $data);
        
        // Should succeed even with large file
        $this->assertArrayHasKey('success', $data);
        $this->assertEquals(1, Sertifikat::count());
    }
}
