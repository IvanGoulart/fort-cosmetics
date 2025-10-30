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
        Schema::create('cosmetics', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('rarity')->nullable();
            $table->string('image')->nullable();
            $table->integer('price')->default(0);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_shop')->default(false);
            $table->date('release_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cosmetics');
    }
};
