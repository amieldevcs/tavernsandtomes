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
        Schema::create('character_ability_scores', function (Blueprint $table) {
            $table->id();

            $table->integer('character_id')->unsigned();
            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');

            $table->unsignedTinyInteger('strength')->default(10);
            $table->unsignedTinyInteger('dexterity')->default(10);
            $table->unsignedTinyInteger('constitution')->default(10);
            $table->unsignedTinyInteger('intelligence')->default(10);
            $table->unsignedTinyInteger('wisdom')->default(10);
            $table->unsignedTinyInteger('charisma')->default(10);

            $table->timestamps();
        });

        Schema::create('character_skills', function (Blueprint $table) {
            $table->id();

            $table->integer('character_id')->unsigned();
            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');

            $table->string('skill');

            // 0 = Not proficient
            // 1 = Proficient
            // 2 = Expertise
            $table->unsignedTinyInteger('proficiency_level')->default(0);

            $table->timestamps();

            $table->unique(['character_id', 'skill']);
        });

        // Saving Throw
        Schema::create('character_saving_throws', function (Blueprint $table) {
            $table->id();

            $table->integer('character_id')->unsigned();
            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');

            $table->string('ability');

            $table->boolean('is_proficient')->default(false);

            $table->timestamps();

            $table->unique(['character_id', 'ability']);
        });

        // Schema::create('character_features', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('character_id')
        //         ->constrained('characters')
        //         ->cascadeOnDelete();

        //     $table->foreignId('feature_id')
        //         ->constrained('features')
        //         ->restrictOnDelete();

        //     $table->boolean('is_active')->default(true);

        //     $table->timestamps();

        //     $table->unique(['character_id', 'feature_id']);
        // });






        // Schema::create('character_details', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('character_id')
        //         ->unique()
        //         ->constrained('characters')
        //         ->cascadeOnDelete();

        //     $table->foreignId('species_id')->nullable()
        //         ->constrained('species')
        //         ->nullOnDelete();

        //     $table->foreignId('background_id')->nullable()
        //         ->constrained('backgrounds')
        //         ->nullOnDelete();

        //     $table->string('alignment')->nullable();

        //     $table->boolean('inspiration')->default(false);

        //     $table->timestamps();
        // });

        // Schema::create('character_classes', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('character_id')
        //         ->constrained('characters')
        //         ->cascadeOnDelete();

        //     $table->foreignId('class_id')
        //         ->constrained('classes')
        //         ->restrictOnDelete();

        //     $table->foreignId('subclass_id')->nullable()
        //         ->constrained('subclasses')
        //         ->nullOnDelete();

        //     $table->unsignedTinyInteger('level')->default(1);

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_ability_scores');
        Schema::dropIfExists('character_skills');
        Schema::dropIfExists('character_saving_throws');
        // Schema::dropIfExists('character_features');
        // Schema::dropIfExists('character_details');
        // Schema::dropIfExists('character_classes');
    }
};
