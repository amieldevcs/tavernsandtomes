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
         // Campaign table: only saves the name and the ruleset we're using
        Schema::create('campaigns', function (Blueprint $table) {
            $table->ulid('campaign_id')->primary();
            $table->string('campaign_name')->index();
            $table->string('ruleset')->default('2014');
            $table->string('created_by');
            $table->integer('assigned_dungeon_master');
            $table->dateTime('campaign_start_date');
            $table->dateTime('campaign_end_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
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

            // Campaign specific stuff
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_story_writer')->default(false);
            
            $table->string('role',20);


            $table->timestamps();
        });

        // Quest table
        Schema::create('quests', function (Blueprint $table) {
            $table->id();

            // Each quest belongs to a campaign
            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('cascade');

            $table->string('title')->index();

            $table->longText('description');

            $table->string('status')->default('active');

            $table->string('priority')->default('normal');

            $table->string('type')->default('side');

            $table->boolean('is_dm_only')->default(false);

            $table->timestamps();
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
            $table->longText('notes')->nullable(); // personal notes that does not have any effect on the story
            $table->longText('session_summary')->nullable(); // Summary of what happened during the session
            $table->longText('ai_summary')->nullable(); // AI summary of the session

            $table->timestamps();

            $table->unique(['campaign_id', 'session_number']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quests');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('campaign_members');
        Schema::dropIfExists('game_sessions');
    }
};
