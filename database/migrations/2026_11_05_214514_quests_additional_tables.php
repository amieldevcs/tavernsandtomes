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
        // Quest Logs
        Schema::create('quest_objectives', function (Blueprint $table) {
            $table->id();

            $table->integer('quest_id')->unsigned();
            $table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');

            $table->string('description');

            $table->boolean('is_completed')->default(false);

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });

        Schema::create('quest_assignments', function (Blueprint $table) {
            $table->id();

            $table->integer('quest_id')->unsigned();
            $table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');

            $table->integer('character_id')->unsigned();
            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['quest_id', 'character_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_objectives');
        Schema::dropIfExists('quest_assignments');
    }
};
