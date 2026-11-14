<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Head / Body script fields (same as Admin > SEO Setup) for categories, subcategories, products and blogs.
 *   head – tags/scripts injected inside <head>
 *   body – scripts injected at the start of <body>
 */
return new class extends Migration
{
    private array $tables = ['categories', 'subcategories', 'products', 'blogs'];

    public function up(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) use ($name) {
                if (! Schema::hasColumn($name, 'head')) {
                    $table->longText('head')->nullable()->after('meta_keywords');
                }
                if (! Schema::hasColumn($name, 'body')) {
                    $table->longText('body')->nullable()->after('head');
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropColumn(['head', 'body']);
            });
        }
    }
};
