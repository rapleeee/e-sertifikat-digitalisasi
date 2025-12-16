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
        $connection = DB::connection()->getName();
        
        if ($connection === 'mysql' || $connection === 'mariadb') {
            // MySQL/MariaDB supports ENUM
            DB::statement("
                ALTER TABLE users
                MODIFY COLUMN role ENUM('super_admin', 'admin', 'guru', 'staf', 'yayasan', 'perusahaan') NOT NULL
            ");
        } else {
            // SQLite doesn't support ENUM, use string with check constraint
            // For testing purposes, this is safe enough
            DB::statement("
                ALTER TABLE users RENAME TO users_old
            ");
            
            DB::statement("
                CREATE TABLE users (
                    id INTEGER PRIMARY KEY,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    email_verified_at DATETIME,
                    password TEXT NOT NULL,
                    remember_token TEXT,
                    role TEXT NOT NULL DEFAULT 'admin',
                    created_at DATETIME,
                    updated_at DATETIME,
                    CHECK (role IN ('super_admin', 'admin', 'guru', 'staf', 'yayasan', 'perusahaan'))
                )
            ");
            
            DB::statement("
                INSERT INTO users (id, name, email, email_verified_at, password, remember_token, role, created_at, updated_at)
                SELECT id, name, email, email_verified_at, password, remember_token, role, created_at, updated_at FROM users_old
            ");
            
            DB::statement("DROP TABLE users_old");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = DB::connection()->getName();
        
        if ($connection === 'mysql' || $connection === 'mariadb') {
            DB::statement("
                ALTER TABLE users
                MODIFY COLUMN role ENUM('admin', 'perusahaan') NOT NULL
            ");
        } else {
            // For SQLite, we don't revert as it's complex, tests will handle this
            DB::statement("
                ALTER TABLE users RENAME TO users_old
            ");
            
            DB::statement("
                CREATE TABLE users (
                    id INTEGER PRIMARY KEY,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    email_verified_at DATETIME,
                    password TEXT NOT NULL,
                    remember_token TEXT,
                    role TEXT NOT NULL DEFAULT 'admin',
                    created_at DATETIME,
                    updated_at DATETIME,
                    CHECK (role IN ('admin', 'perusahaan'))
                )
            ");
            
            DB::statement("
                INSERT INTO users (id, name, email, email_verified_at, password, remember_token, role, created_at, updated_at)
                SELECT id, name, email, email_verified_at, password, remember_token, role, created_at, updated_at FROM users_old
            ");
            
            DB::statement("DROP TABLE users_old");
        }
    }
};
