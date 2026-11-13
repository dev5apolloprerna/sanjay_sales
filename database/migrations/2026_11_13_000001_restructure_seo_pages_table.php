<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * New SEO Setup fields: Page Name, Meta Title, Meta Keyword, Meta Description,
 * Head (scripts/tags injected in <head>) and Body (scripts injected at start of <body>).
 *
 * Removes canonical_url / robots / og_* and the SEO rows for "Our Clients" and
 * "Products (listing)", which no longer have SEO.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_pages', function (Blueprint $table) {
            $table->string('page_name')->nullable()->after('page_key');
            $table->longText('head')->nullable()->after('meta_description');
            $table->longText('body')->nullable()->after('head');
        });

        Schema::table('seo_pages', function (Blueprint $table) {
            $table->dropColumn(['canonical_url', 'robots', 'og_title', 'og_description', 'og_image']);
        });

        DB::table('seo_pages')->whereIn('page_key', ['clients', 'products'])->delete();

        $names = [
            'home' => 'Home', 'about' => 'About Us', 'contact' => 'Contact Us', 'blogs' => 'Blog',
            'gallery' => 'Photo Gallery', 'videos' => 'Video Gallery', 'download' => 'Download',
        ];
        foreach ($names as $key => $name) {
            DB::table('seo_pages')->where('page_key', $key)->whereNull('page_name')->update(['page_name' => $name]);
        }
    }

    public function down(): void
    {
        Schema::table('seo_pages', function (Blueprint $table) {
            $table->string('canonical_url')->nullable();
            $table->string('robots', 50)->default('index, follow');
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
        });

        Schema::table('seo_pages', function (Blueprint $table) {
            $table->dropColumn(['page_name', 'head', 'body']);
        });
    }
};
