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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();

            // Slot determines where the item can be equipped in the paperdoll
            $table->enum('slot', ['head', 'chest', 'feet', 'weapon', 'off_hand', 'ring', 'accessory']);

            // Item rarity tier
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->default('common');

            // Attribute bonuses granted when equipped
            $table->integer('str_bonus')->default(0);
            $table->integer('dex_bonus')->default(0);
            $table->integer('int_bonus')->default(0);
            $table->integer('vit_bonus')->default(0);

            // Derived stat bonuses granted when equipped
            $table->integer('attack_bonus')->default(0);
            $table->integer('defense_bonus')->default(0);
            $table->integer('hp_bonus')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
