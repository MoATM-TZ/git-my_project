<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces report_process.php. Users flag resources for moderation; the
 * status column tracks the moderation lifecycle (open | resolved | dismissed).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();

            // One of: copyright, inappropriate, spam, broken-file, other
            $table->string('reason', 40);
            $table->text('details')->nullable();

            // open | resolved | dismissed
            $table->string('status', 20)->default('open')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
