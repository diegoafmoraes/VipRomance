<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            if (!Schema::hasColumn('photos', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('path');
            }
            if (!Schema::hasColumn('photos', 'is_private')) {
                // se você já tem is_private, remove este bloco
                $table->boolean('is_private')->default(false)->after('is_primary');
            }
        });

        // copia is_profile -> is_primary (se existir)
        if (Schema::hasColumn('photos', 'is_profile')) {
            DB::statement("UPDATE photos SET is_primary = is_profile");
        }

        // derruba is_profile (se existir)
        Schema::table('photos', function (Blueprint $table) {
            if (Schema::hasColumn('photos', 'is_profile')) {
                $table->dropColumn('is_profile');
            }
        });
    }

    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            if (!Schema::hasColumn('photos', 'is_profile')) {
                $table->boolean('is_profile')->default(false)->after('path');
            }
        });

        if (Schema::hasColumn('photos', 'is_primary')) {
            DB::statement("UPDATE photos SET is_profile = is_primary");
        }

        Schema::table('photos', function (Blueprint $table) {
            if (Schema::hasColumn('photos', 'is_primary')) {
                $table->dropColumn('is_primary');
            }
            // se você já tinha is_private antes, ajuste aqui
            if (Schema::hasColumn('photos', 'is_private')) {
                $table->dropColumn('is_private');
            }
        });
    }
};
