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
            $table->enum('eligibilitas', ['eligible', 'tidak_eligible'])->nullable()->after('status');
            $table->text('catatan_eligibilitas')->nullable()->after('eligibilitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['eligibilitas', 'catatan_eligibilitas']);
        });
    }
};
