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
        Schema::create('feats', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('character_feats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('character_id')
                ->constrained('characters')
                ->cascadeOnDelete();

            $table->foreignId('feat_id')
                ->constrained('feats')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique(['character_id', 'feat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feats');
        Schema::dropIfExists('character_feats');
    }
};
