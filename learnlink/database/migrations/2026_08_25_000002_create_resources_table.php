<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces the flat uploads/ directory + ad-hoc rows used by the legacy
 * upload.php / upload_process.php pair. Files themselves are stored via the
 * Storage facade on the "public" disk under uploads/; this table holds
 * metadata and moderation state.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // One of: notes, slides, past-papers, assignments, books, other
            $table->string('category')->default('other')->index();

            // File metadata (stored on the "public" disk)
            $table->string('file_path');          // e.g. uploads/AbCdEf123.pdf
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);

            // Engagement counters
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('views_count')->default(0);

            // Moderation state: published | pending | removed
            $table->string('status', 20)->default('published')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
