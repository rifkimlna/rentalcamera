<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sintaks ALTER ... MODIFY hanya ada di MySQL
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'customer') NOT NULL DEFAULT 'customer'");

            // Recreate index (MySQL path)
            DB::statement("ALTER TABLE users DROP INDEX users_role_status_index");
            DB::statement("ALTER TABLE users ADD INDEX users_role_status_index (role, status)");

            return;
        }

        // Jalur portabel (sqlite dll.): pastikan index ada
        $this->ensureRoleStatusIndex();
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'customer', 'driver') NOT NULL DEFAULT 'customer'");

            DB::statement("ALTER TABLE users DROP INDEX users_role_status_index");
            DB::statement("ALTER TABLE users ADD INDEX users_role_status_index (role, status)");

            return;
        }

        $this->ensureRoleStatusIndex();
    }

    private function ensureRoleStatusIndex(): void
    {
        $indexes = collect(Schema::getIndexes('users'))->pluck('name');

        if (!$indexes->contains('users_role_status_index')) {
            Schema::table('users', function ($table) {
                $table->index(['role', 'status'], 'users_role_status_index');
            });
        }
    }
};
