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
        Schema::table('cosmetics', function (Blueprint $table) {
            // Relacionamento com o bundle (caso pertença a um bundle)
            $table->unsignedBigInteger('bundle_id')->nullable()->after('type');
            $table->foreign('bundle_id')->references('id')->on('cosmetics')->onDelete('set null');

            // Preço original (para promoções)
            $table->integer('regular_price')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cosmetics', function (Blueprint $table) {
            $table->dropForeign(['bundle_id']);
            $table->dropColumn(['bundle_id', 'type', 'regular_price']);
        });
    }
};
