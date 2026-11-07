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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->ulid('session_id')->primary();

            
            $table->foreignUlid('campaign_id')->constrained('campaigns', 'campaign_id')->cascadeOnDelete();

            $table->unsignedSmallInteger('session_number');
            $table->date('session_date');
            $table->string('location_id');
            $table->string('loots')->nullable();
            $table->string('npcs')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('session_summary')->nullable();
            $table->longText('ai_summary')->nullable();

            $table->timestamps();

            $table->unique(['campaign_id', 'session_number']);
        });

        // Session Participants table: this is where we store the participants for each session, this is where we store 
        // the characters that participated in each session
        Schema::create('session_participants', function (Blueprint $table) {
            $table->id();

            $table->string('session_id');
            $table->foreign('session_id')->references('session_id')->on('game_sessions')->onDelete('cascade');

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
            $table->foreign('session_id')->references('session_id')->on('game_sessions')->onDelete('cascade');

            $table->text('log_entry');
            $table->string('log_type')->default('general');
            $table->datetime('logged_at')->nullable();
            $table->timestamps();
        });


        Schema::table('campaign_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('campaign_id')
                ->constrained('campaigns', 'campaign_id')
                ->cascadeOnDelete();
            $table->dateTime('joined_at')->nullable();
            $table->dateTime('left_at')->nullable();
            
            $table->string('role',20);


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
        Schema::dropIfExists('game_sessions');
        Schema::dropIfExists('lore');
    }
};
