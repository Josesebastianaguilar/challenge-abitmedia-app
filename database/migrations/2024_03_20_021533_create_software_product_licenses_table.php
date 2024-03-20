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
        Schema::create('software_product_licenses', function (Blueprint $table) {
            $table->string('serial')->unique()->length(100)->nullable(false)->primary();
            $table->string('name')->min(3)->max(100)->nullable(false);
            $table->string('software_product_sku')->length(10)->nullable(false);
            $table->string('software_product_slug')->min(3)->max(100)->nullable(false);
            $table->timestamps();
            //Constrains
            $table->foreign('software_product_sku')->references('sku')->on('software_products');
            $table->foreign('software_product_slug')->references('slug')->on('operative_systems');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software_product_licenses');
    }
};
