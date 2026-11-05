<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** "Inquire Now" form submissions from the website (contact page / product page). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30);
            $table->string('subject', 200)->nullable();
            $table->text('message');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source', 30)->default('contact'); // contact | product
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
