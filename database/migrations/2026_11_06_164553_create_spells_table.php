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
        // Global Compedium of Magic Schools
        // https://www.dnd5eapi.co/api/2014/magic-schools
        Schema::create('magic_schools', function (Blueprint $table) {
            $table->id();
            $table->string('index')->unique();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        // Global Compedium of Spells
        // https://www.dnd5eapi.co/api/2014/spells
        Schema::create('spells', function (Blueprint $table) {
            $table->id();
            $table->string('index')->unique();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->string('higher_level')->nullable();
            $table->string('range')->nullable();
            $table->string('components')->nullable();
            $table->string('material')->nullable();
            $table->boolean('ritual')->default(false);
            $table->string('duration')->nullable();
            $table->boolean('concentration')->default(false);
            $table->string('casting_time')->nullable();
            $table->integer('level')->nullable();
            $table->string('attack_type')->nullable();

            $table->foreignId('magic_schools_id')
                ->nullable()
                ->constrained('magic_schools')
                ->cascadeOnDelete();

            $table->string('url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magic_schools');
        Schema::dropIfExists('spells');
    }
};
