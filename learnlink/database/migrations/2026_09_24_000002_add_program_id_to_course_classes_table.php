<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_classes', function (Blueprint $table): void {
            $table->foreignId('program_id')->nullable()->after('lecturer_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('course_classes', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('program_id');
        });
    }
};
