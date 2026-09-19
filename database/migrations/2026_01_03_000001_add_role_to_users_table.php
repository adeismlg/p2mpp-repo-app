<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan sistem role (admin / editor / guest) menggantikan
     * kolom is_admin yang sederhana.
     *
     * - admin  : akses penuh (termasuk kelola kategori & staff)
     * - editor : staf yang boleh kelola dokumen, berita, program
     * - guest  : user biasa yang mendaftar sendiri (TIDAK bisa masuk /admin)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('guest')->after('email');
        });

        // Migrasi otomatis: siapapun yang tadinya is_admin = true jadi 'admin'
        if (Schema::hasColumn('users', 'is_admin')) {
            DB::table('users')->where('is_admin', true)->update(['role' => 'admin']);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });

        DB::table('users')->where('role', 'admin')->update(['is_admin' => true]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
