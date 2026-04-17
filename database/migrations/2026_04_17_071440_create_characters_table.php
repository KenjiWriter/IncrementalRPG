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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('level')->default(1);
            $table->unsignedBigInteger('experience')->default(0);
            $table->unsignedBigInteger('gold')->default(0);
            $table->integer('hp')->default(100);
            $table->integer('max_hp')->default(100);
            $table->integer('mana')->default(50);
            $table->integer('max_mana')->default(50);
            $table->integer('attack')->default(10);
            $table->integer('defense')->default(10);
            $table->integer('speed')->default(10);
            $table->integer('luck')->default(5);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
