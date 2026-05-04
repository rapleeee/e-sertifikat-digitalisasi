<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelulusanFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_graduation_page_can_be_rendered(): void
    {
        $this->withoutVite();

        $response = $this->get(route('kelulusan.index'));

        $response->assertOk();
        $response->assertSee('Cek Kelulusan');
    }

    public function test_graduation_search_is_locked_before_announcement_time(): void
    {
        AppSetting::setValue('kelulusan_pengumuman_dibuka_pada', now()->addDay()->format('Y-m-d H:i:s'));

        $response = $this->getJson(route('kelulusan.search.api', [
            'type' => 'nis',
            'query' => 'NIS001',
        ]));

        $response->assertStatus(403);
    }

    public function test_graduation_search_returns_graduated_student_after_open_time(): void
    {
        AppSetting::setValue('kelulusan_pengumuman_dibuka_pada', now()->subMinute()->format('Y-m-d H:i:s'));

        Siswa::factory()->create([
            'nis' => 'NIS001',
            'nama' => 'Siswa Lulus',
            'kelas' => 'XII RPL 1',
            'jurusan' => 'RPL',
            'status' => 'lulus',
        ]);

        Siswa::factory()->create([
            'nis' => 'NIS002',
            'nama' => 'Siswa Aktif',
            'kelas' => 'XII RPL 1',
            'status' => 'aktif',
        ]);

        $response = $this->getJson(route('kelulusan.search.api', [
            'type' => 'nis',
            'query' => 'NIS001',
        ]));

        $response->assertOk();
        $response->assertJsonPath('results.0.nama', 'Siswa Lulus');
        $response->assertJsonCount(1, 'results');
    }

    public function test_admin_can_update_graduation_announcement_time(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('kelulusan.settings.update'), [
                'announcement_date' => '2026-05-05',
                'announcement_time' => '10:00',
                'graduation_note' => 'Ambil SKL di TU dengan membawa bukti bebas administrasi.',
            ]);

        $response->assertRedirect(route('kelulusan.settings'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('app_settings', [
            'key' => 'kelulusan_pengumuman_dibuka_pada',
            'value' => '2026-05-05 10:00:00',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'kelulusan_catatan_pengambilan_skl',
            'value' => 'Ambil SKL di TU dengan membawa bukti bebas administrasi.',
        ]);
    }
}
