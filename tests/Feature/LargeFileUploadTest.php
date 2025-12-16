<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LargeFileUploadTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    #[Test]
    public function handleValidationErrorAsJson()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Siswa::create(['nis' => '252610001', 'nama' => 'Test Student', 'status' => 'aktif']);

        // Submit without files
        $response = $this->actingAs($user)
            ->post('/sertifikat/upload-massal', [
                'jenis_sertifikat' => 'Test',
                'judul_sertifikat' => 'Test',
                'tanggal_diraih' => now()->toDateString(),
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        // Should return JSON validation error
        $response->assertStatus(422);
        $data = $response->json();
        
        $this->assertArrayHasKey('error', $data);
        $this->assertArrayHasKey('skipped', $data);
        $this->assertFalse($data['success']);
        $this->assertStringContainsString('sertifikat', strtolower($data['error']));
    }

    #[Test]
    public function handleInvalidFileTypeAsJson()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Siswa::create(['nis' => '252610001', 'nama' => 'Test Student', 'status' => 'aktif']);

        // Create a file with wrong mime type
        $wrongFile = UploadedFile::fake()->create('252610001.txt', 100, 'text/plain');

        $response = $this->actingAs($user)
            ->post('/sertifikat/upload-massal', [
                'foto_sertifikat' => [$wrongFile],
                'jenis_sertifikat' => 'Test',
                'judul_sertifikat' => 'Test',
                'tanggal_diraih' => now()->toDateString(),
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        // Should return JSON validation error
        $response->assertStatus(422);
        $data = $response->json();
        
        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('JPG', strtoupper($data['error']));
    }

    #[Test]
    public function handleFileTooLargeAsJson()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin5@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Siswa::create(['nis' => '252610001', 'nama' => 'Test Student', 'status' => 'aktif']);

        // Create a file larger than 10MB (11MB)
        $largeFile = UploadedFile::fake()->create('252610001.pdf', 11264, 'application/pdf'); // 11MB

        $response = $this->actingAs($user)
            ->post('/sertifikat/upload-massal', [
                'foto_sertifikat' => [$largeFile],
                'jenis_sertifikat' => 'Test',
                'judul_sertifikat' => 'Test',
                'tanggal_diraih' => now()->toDateString(),
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        // Should return JSON validation error for file size
        $response->assertStatus(422);
        $data = $response->json();
        
        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('10MB', $data['error']);
    }

    #[Test]
    public function handleMissingFormFieldsAsJson()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin3@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Siswa::create(['nis' => '252610001', 'nama' => 'Test Student', 'status' => 'aktif']);

        $file = UploadedFile::fake()->create('252610001.pdf', 500, 'application/pdf');

        // Submit without required form fields
        $response = $this->actingAs($user)
            ->post('/sertifikat/upload-massal', [
                'foto_sertifikat' => [$file],
                // Missing: jenis_sertifikat, judul_sertifikat, tanggal_diraih
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        // Should return JSON validation error
        $response->assertStatus(422);
        $data = $response->json();
        
        $this->assertArrayHasKey('error', $data);
        $this->assertFalse($data['success']);
    }

    #[Test]
    public function handleMissingFilesAsJson()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin4@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->post('/sertifikat/upload-massal', [
                'jenis_sertifikat' => 'Test',
                'judul_sertifikat' => 'Test',
                'tanggal_diraih' => now()->toDateString(),
                // No files uploaded
            ], ['X-Requested-With' => 'XMLHttpRequest']);

        // Should return JSON error
        $response->assertStatus(422);
        $data = $response->json();
        
        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('sertifikat', strtolower($data['error']));
    }
}
