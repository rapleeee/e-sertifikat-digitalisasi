<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nisn')->nullable()->after('nis');
            $table->string('jenis_kelamin', 20)->nullable()->after('nama');
            $table->string('kelas', 50)->nullable()->after('jenis_kelamin');
            $table->string('jurusan', 100)->nullable()->after('kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['nisn', 'jenis_kelamin', 'kelas', 'jurusan']);
        });
    }
};

