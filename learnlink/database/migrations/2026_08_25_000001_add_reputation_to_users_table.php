<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Converts the legacy `migrate_reputation.sql` routine into a first-class
 * migration: every user carries a cached reputation score that is kept in
 * sync with the `reputations` ledger table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('reputation_points')->default(0)->after('password')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['reputation_points']);
            $table->dropColumn('reputation_points');
        });
    }
};
