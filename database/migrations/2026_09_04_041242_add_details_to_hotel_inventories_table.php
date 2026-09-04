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
        Schema::table('hotel_inventories', function (Blueprint $table) {
            $table->string('menu_type')->nullable()->after('category');
            $table->decimal('price', 10, 2)->nullable()->after('description');
            $table->unsignedSmallInteger('people_count')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_inventories', function (Blueprint $table) {
            $table->dropColumn(['menu_type', 'price', 'people_count']);
        });
    }
};
