<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Videos are YouTube-only now. Removes the old upload columns from
 * databases created before this change (does nothing on new installs).
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['video_file', 'thumbnail'] as $column) {
            if (Schema::hasColumn('videos', $column)) {
                Schema::table('videos', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }

    public function down(): void
    {
        // not restored: video uploads were removed from the admin panel
    }
};
