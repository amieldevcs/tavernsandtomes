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
            $table->string('campaign_id')->primary()->unique();
            $table->string('campaign_name')->index();
            $table->string('ruleset');
            $table->string('dungeon_master_id');
            $table->string('campaign_start_date');
            $table->string('campaign_end_date')->nullable();
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


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quests');
        Schema::dropIfExists('campaigns');
    }
};
