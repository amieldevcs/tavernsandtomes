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
        // Lore Entries for the Campaign, this is where we store the worldbuilding and lore for the campaign
         Schema::create('lore', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('cascade');
            $table->string('lore_title');
            $table->longText('lore_entry');
            $table->boolean('is_dm_only')->default(false);
        });

        // Session table: this is where we store actual session entries and data
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('session_id')->primary()->unique();
            $table->smallInteger('session_number');
            $table->date('session_date');
            $table->string('location_id');
            $table->string('loots')->nullable();
            $table->string('npcs')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('session_summary')->nullable();

            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('cascade');
        });

        // Session Participants table: this is where we store the participants for each session, this is where we store 
        // the characters that participated in each session
        Schema::create('session_participants', function (Blueprint $table) {
            $table->id();

            $table->string('session_id');
            $table->foreign('session_id')->references('session_id')->on('sessions')->onDelete('cascade');

            $table->foreignId('character_id')
                ->constrained('characters')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['session_id', 'character_id']);
        });

        
        // Session Logs table: this is where we store the logs for each session, this is where we store the notes for each session
        Schema::create('session_logs', function (Blueprint $table) {
            $table->id();

            $table->string('session_id');
            $table->foreign('session_id')->references('session_id')->on('sessions')->onDelete('cascade');

            $table->text('log_entry');
            $table->string('log_type')->default('general');
            $table->datetime('logged_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_logs');
        Schema::dropIfExists('session_participants');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('lore');
    }
};
