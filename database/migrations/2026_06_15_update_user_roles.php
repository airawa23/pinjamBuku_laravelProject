<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum menjadi varchar untuk update data
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255)");

        // Update existing data
        DB::table('users')->where('role', 'user')->update(['role' => 'renter']);
        DB::table('users')->where('role', 'admin')->update(['role' => 'owner']);

        // Ubah kembali ke enum dengan nilai baru
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner', 'renter') NOT NULL DEFAULT 'renter'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ubah enum menjadi varchar untuk update data
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255)");

        // Update data kembali
        DB::table('users')->where('role', 'renter')->update(['role' => 'user']);
        DB::table('users')->where('role', 'owner')->update(['role' => 'admin']);

        // Ubah kembali ke enum dengan nilai lama
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
