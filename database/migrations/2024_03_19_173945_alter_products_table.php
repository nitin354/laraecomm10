<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function ($table) {

            $table->text('short_description')->nullable()->after('description');
            $table->text('shiiping_return')->nullable()->after('short_description');
            $table->text('related_products')->nullable()->after('shiiping_return');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function ($table) {
            $table->dropColumn('short_description');
            $table->dropColumn('shiiping_return');
            $table->dropColumn('related_products');
        });
    }
};
