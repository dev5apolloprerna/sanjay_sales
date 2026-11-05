<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** PDF catalogues listed on the website's /download page. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file');                 // catalogues/abc.pdf
            $table->string('image')->nullable();    // optional cover image
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogues');
    }
};
