<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces vote_process.php. A user may hold at most one vote per resource:
 * +1 (up) or -1 (down). The composite unique index enforces this at the
 * database level, so toggling is an upsert-or-delete in the controller.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            // 1 = upvote, -1 = downvote
            $table->tinyInteger('value');
            $table->timestamps();

            $table->unique(['user_id', 'resource_id']);
            $table->index(['resource_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
