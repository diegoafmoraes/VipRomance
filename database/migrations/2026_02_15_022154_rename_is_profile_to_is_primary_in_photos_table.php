<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Se já está com is_primary, não faz nada.
        if (Schema::hasColumn('photos', 'is_primary')) {
            return;
        }

        // Se ainda existe is_profile, renomeia via CHANGE (MySQL 5.7).
        if (Schema::hasColumn('photos', 'is_profile')) {
            DB::statement("ALTER TABLE `photos` CHANGE `is_profile` `is_primary` TINYINT(1) NOT NULL DEFAULT 0");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('photos', 'is_profile')) {
            return;
        }

        if (Schema::hasColumn('photos', 'is_primary')) {
            DB::statement("ALTER TABLE `photos` CHANGE `is_primary` `is_profile` TINYINT(1) NOT NULL DEFAULT 0");
        }
    }
};
