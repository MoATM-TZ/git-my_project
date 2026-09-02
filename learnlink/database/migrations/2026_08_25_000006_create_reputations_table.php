<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reputation ledger — the auditable counterpart of the cached
 * users.reputation_points column introduced by the legacy
 * migrate_reputation.sql. Every score change is recorded here with its
 * reason and, when applicable, the resource that triggered it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reputations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->nullable()->constrained()->cascadeOnDelete();

            // Positive or negative point delta applied to the user.
            $table->integer('points');

            // One of: resource_uploaded, upvote_received, downvote_received,
            //         comment_posted, manual_adjustment
            $table->string('reason', 40)->index();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reputations');
    }
};
