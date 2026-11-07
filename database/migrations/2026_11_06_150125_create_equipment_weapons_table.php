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
        // D&D Global Compedium of equipment and weapons
        // https://www.dnd5eapi.co/api/2014/equipment
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('index')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('special')->nullable();
            $table->text('contents')->nullable();
            $table->text('properties')->nullable();

            $table->timestamps();
        });

        // Additional weapon attributes for items
        Schema::create('weapon_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            $table->string('weapon_category');
            $table->string('weapon_range');

            $table->string('index')->unique();
            $table->string('name');

            $table->boolean('is_magical')->default(false);
            $table->boolean('requires_attunement')->default(false);
            $table->integer('has_charges')->nullable();
            $table->boolean('is_consumable')->default(false);

            $table->timestamps();
        });

        Schema::create('weapons_damage_types', function (Blueprint $table) {
            $table->id();

            $table->foreignId('weapon_attribute_id')
                ->constrained('weapon_attributes')
                ->cascadeOnDelete();

            $table->foreignId('damage_type_id')
                ->constrained('damage_types')
                ->cascadeOnDelete();

            $table->string('damage_dice')->nullable();

            $table->foreignId('alternative_damage_type_id')
                ->constrained('damage_types')
                ->cascadeOnDelete();

            $table->string('alternative_damage_dice')->nullable();

            $table->timestamps();
        });

        // ----------------
        // Weapon properties for items
        // https://www.dnd5eapi.co/api/2014/weapon-properties
        Schema::create('weapon_properties', function (Blueprint $table) {
            $table->id();
            $table->string('index')->unique();
            $table->string('name');
            $table->longText('desc')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        // Properties of the weapon, such as thrown, versatile, finesse, etc
        Schema::create('equipment_weapon_properties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equipment_id')
                ->constrained('equipments')
                ->cascadeOnDelete();

            $table->foreignId('weapon_property_id')
                ->constrained('weapon_properties')
                ->cascadeOnDelete();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
        Schema::dropIfExists('weapon_attributes');
        Schema::dropIfExists('weapons_damage_types');
        Schema::dropIfExists('equipment_weapon_properties');

        // -----------------
        Schema::dropIfExists('weapon_properties');
    }
};
