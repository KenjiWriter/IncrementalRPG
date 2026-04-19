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
        Schema::create('character_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            // Equipment state — null slot means item is in the bag (not equipped)
            $table->boolean('is_equipped')->default(false);
            $table->string('slot', 20)->nullable();

            $table->timestamps();

            // Prevent duplicate items in the same character's bag (soft; duplicates
            // are allowed via loot, but equip logic is enforced at the app layer)
            $table->index(['character_id', 'is_equipped', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_item');
    }
};
