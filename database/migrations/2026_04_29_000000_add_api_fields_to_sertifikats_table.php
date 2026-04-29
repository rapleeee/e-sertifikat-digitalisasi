<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->string('nomor_sertifikat')->nullable()->after('nis');
            $table->string('nama_sertifikat')->nullable()->after('nomor_sertifikat');
            $table->string('penerbit')->nullable()->after('nama_sertifikat');
            $table->date('tanggal_terbit')->nullable()->after('penerbit');
            $table->date('tanggal_kadaluarsa')->nullable()->after('tanggal_terbit');
            $table->string('url_sertifikat')->nullable()->after('tanggal_kadaluarsa');
            $table->string('kategori')->nullable()->after('url_sertifikat');

            $table->index('nis', 'sertifikats_nis_api_index');
        });
    }

    public function down(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->dropIndex('sertifikats_nis_api_index');
            $table->dropColumn([
                'nomor_sertifikat',
                'nama_sertifikat',
                'penerbit',
                'tanggal_terbit',
                'tanggal_kadaluarsa',
                'url_sertifikat',
                'kategori',
            ]);
        });
    }
};
