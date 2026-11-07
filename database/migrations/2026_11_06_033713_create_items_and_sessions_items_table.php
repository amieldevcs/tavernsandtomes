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
        // D&D Global compedium of items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('index'); //index: cloak-of-protection
            $table->string('name'); //Cloak of Protection
            $table->text('description')->nullable(); // Gain 1 bonus AC and saving throws while wearing this cloak.
            $table->string('rarity'); // Uncommon
            $table->string('equipment_category'); // Wondrous Item
            $table->string('image_url')->nullable(); ///api/images/magic-items/cloak-of-protection.png
            $table->string('source_url')->nullable(); ///api/magic-items/cloak-of-protection
            $table->timestamps();
        });

        // Equipment categories for items
        // https://www.dnd5eapi.co/api/2014/equipment-categories
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('index')->unique(); // wondrous-item
            $table->string('name'); // Wondrous Item
            $table->timestamps();
        });

        

        // Handles variatons among items
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();


            $table->foreignId('variant_id')
                ->constrained('items')
                ->cascadeOnDelete();
            
            $table->timestamps();
        });

        // Session Loot: loots gained during a session
        Schema::create('session_loot', function (Blueprint $table) {
            $table->id();

            $table->string('session_id');
            $table->foreign('session_id')->references('session_id')->on('sessions')->onDelete('cascade');

            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            $table->foreignId('character_id')
                ->nullable()
                ->constrained('characters')
                ->nullOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();
        });

        // Handles character items and inventory
        Schema::create('character_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('character_id')
                ->constrained('characters')
                ->cascadeOnDelete();

            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            $table->boolean('is_equipped')->default(false);

            $table->boolean('is_attuned')->default(false);

            $table->string('placement')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_loot');
        Schema::dropIfExists('character_items');
        Schema::dropIfExists('item_variants');
        Schema::dropIfExists('equipment_categories');
        Schema::dropIfExists('items');
    }
};
