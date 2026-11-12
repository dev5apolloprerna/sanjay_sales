<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds meta_title / meta_description / meta_keywords to categories, subcategories,
 * products and blogs. Blogs already have meta_title + meta_description, so only
 * missing columns are added.
 */
return new class extends Migration
{
    private array $tables = ['categories', 'subcategories', 'products', 'blogs'];

    public function up(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) use ($name) {
                if (! Schema::hasColumn($name, 'meta_title')) {
                    $table->string('meta_title')->nullable();
                }
                if (! Schema::hasColumn($name, 'meta_description')) {
                    $table->text('meta_description')->nullable();
                }
                if (! Schema::hasColumn($name, 'meta_keywords')) {
                    $table->string('meta_keywords', 500)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) use ($name) {
                $table->dropColumn('meta_keywords');
                if ($name !== 'blogs') {
                    $table->dropColumn(['meta_title', 'meta_description']);
                }
            });
        }
    }
};
