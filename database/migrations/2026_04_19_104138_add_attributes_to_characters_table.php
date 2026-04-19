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
        Schema::table('characters', function (Blueprint $table) {
            // Primary RPG attributes — drive all derived stat calculations
            $table->integer('strength')->default(10)->after('luck');
            $table->integer('dexterity')->default(10)->after('strength');
            $table->integer('intelligence')->default(10)->after('dexterity');
            $table->integer('vitality')->default(10)->after('intelligence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn(['strength', 'dexterity', 'intelligence', 'vitality']);
        });
    }
};
