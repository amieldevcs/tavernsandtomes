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
            $table->string('dungeon_master_id');
            $table->dateTime('campaign_start_date');
            $table->dateTime('campaign_end_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
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
