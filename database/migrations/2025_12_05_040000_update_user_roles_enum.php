<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Perlu MySQL / MariaDB. Untuk engine lain, sesuaikan secara manual jika diperlukan.
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('super_admin', 'admin', 'guru', 'staf', 'yayasan', 'perusahaan') NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('admin', 'perusahaan') NOT NULL
        ");
    }
};

