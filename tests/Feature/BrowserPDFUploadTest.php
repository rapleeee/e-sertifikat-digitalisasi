<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BrowserPDFUploadTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    #[Test]
    public function testUploadManyFilesWithPdfAndImage()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin3@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create multiple students
        Siswa::create(['nis' => '252610101', 'nama' => 'Student 1', 'status' => 'aktif']);
        Siswa::create(['nis' => '252610102', 'nama' => 'Student 2', 'status' => 'aktif']);
        Siswa::create(['nis' => '252610103', 'nama' => 'Student 3', 'status' => 'aktif']);

        $files = [
            UploadedFile::fake()->create('252610101.jpg', 200, 'image/jpeg'),
            UploadedFile::fake()->create('252610102.pdf', 300, 'application/pdf'),
            UploadedFile::fake()->create('252610103.png', 150, 'image/png'),
        ];

        $response = $this->actingAs($user)
            ->post('/sertifikat/upload-massal', [
                'foto_sertifikat' => $files,
                'jenis_sertifikat' => 'Mixed',
                'judul_sertifikat' => 'Mixed File Types Test',
                'tanggal_diraih' => now()->subDay()->toDateString(),
            ]);

        $response->assertStatus(200);
        $data = $response->json();
        
        dump('Mixed files response:', $data);
        
        // All 3 should succeed
        $this->assertArrayHasKey('success', $data);
        $this->assertEquals(3, Sertifikat::count());
    }

    #[Test]
    public function testPdfWithInvalidNisShowsError()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin4@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create only one student
        Siswa::create(['nis' => '252610201', 'nama' => 'Existing Student', 'status' => 'aktif']);

        // Upload for non-existing NIS
        $pdfFile = UploadedFile::fake()->create('999999999.pdf', 300, 'application/pdf');

        $response = $this->actingAs($user)
            ->post('/sertifikat/upload-massal', [
                'foto_sertifikat' => [$pdfFile],
                'jenis_sertifikat' => 'Test',
                'judul_sertifikat' => 'Invalid NIS Test',
                'tanggal_diraih' => now()->subDay()->toDateString(),
            ]);

        $response->assertStatus(400);
        $data = $response->json();
        
        dump('Invalid NIS response:', $data);
        
        // Should have error
        $this->assertArrayHasKey('error', $data);
        // Check that skipped array contains the error reason
        $this->assertNotEmpty($data['skipped']);
        $this->assertStringContainsString('tidak ditemukan', $data['skipped'][0]);
    }
}
