<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extra blocks shown on the website's product detail page:
 * Key Features, Applications, Technical Specifications (grouped) and FAQ.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('features')->nullable()->after('description');        // ["High speed cutting", ...]
            $table->json('applications')->nullable()->after('features');       // ["Sheet metal", ...]
            $table->json('specifications')->nullable()->after('applications'); // [{"group":"General","items":[{"label":"Machine Type","value":"CNC"}]}]
            $table->json('faqs')->nullable()->after('specifications');         // [{"question":"...","answer":"..."}]
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['features', 'applications', 'specifications', 'faqs']);
        });
    }
};
