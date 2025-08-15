<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shopify_products', function (Blueprint $table) {
            $table->text('image_alt')->nullable()->change();
            $table->text('image_url')->nullable()->change();
            $table->double('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shopify_products', function (Blueprint $table) {
            $table->string('image_alt', 255)->nullable()->change();
            $table->string('image_url', 255)->nullable()->change();
            $table->double('price')->nullable();
        });
    }
};
