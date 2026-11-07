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

        Schema::create('campaign_members', function (Blueprint $table) {
            $table->id();

            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns')->onDelete('cascade');

            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('role')->default('player');

            $table->unique(['campaign_id', 'user_id']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_members');
    }
};
