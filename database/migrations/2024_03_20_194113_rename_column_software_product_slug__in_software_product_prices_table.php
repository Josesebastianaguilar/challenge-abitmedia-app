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
        Schema::table('software_product_prices', function (Blueprint $table) {
            $table->renameColumn('software_product_slug', 'operative_system_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software_product_prices', function (Blueprint $table) {
            $table->renameColumn('software_product_slug', 'operative_system_slug');
        });
    }
};