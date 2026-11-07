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
        Schema::create('dnd_classes', function (Blueprint $table) {
            $table->id();
            $table->string('index')->unique();
            $table->string('class_name')->unique();
            $table->integer('hit_dice');

            $table->string('spellcasting_ability', 5);
            $table->string('spells_known');
            $table->unsignedTinyInteger('caster_type_mod');

            $table->string('source_url')->nullable();
            $table->timestamps();
        });

        Schema::create('dnd_subclasses', function (Blueprint $table) {
            $table->id();
            $table->string('index')->unique();
            $table->string('subclass_name')->unique();

            $table->foreignId('dnd_class_id')
                ->constrained('dnd_classes')
                ->cascadeOnDelete();

            $table->string('description')->nullable();
            $table->string('subclass_flavor')->nullable();

            $table->string('source_url')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dnd_classes');
        Schema::dropIfExists('dnd_subclasses');
    }
};
