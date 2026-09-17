<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->enum('role', ['lecturer', 'student', 'admin'])->default('student')->index()->after('email'));

        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('full_name'); $table->string('registration_number')->unique(); $table->unsignedTinyInteger('year_of_study');
            $table->string('phone_number', 30); $table->string('department');
            $table->enum('program_level', ['Certificate', 'Diploma', 'Degree', 'Masters', 'PhD']); $table->string('program_name'); $table->timestamps();
        });
        Schema::create('lecturer_profiles', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('full_name'); $table->string('department'); $table->string('phone_number', 30)->nullable(); $table->timestamps();
        });
        Schema::create('course_classes', function (Blueprint $table) {
            $table->id(); $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('course_title'); $table->string('course_code'); $table->string('department');
            $table->string('academic_year', 20); $table->string('semester', 30); $table->boolean('is_archived')->default(false); $table->timestamps();
            $table->index(['lecturer_id', 'academic_year', 'semester']);
        });
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id(); $table->foreignId('course_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete(); $table->timestamps();
            $table->unique(['course_class_id', 'student_id']);
        });
        Schema::create('announcements', function (Blueprint $table) {
            $table->id(); $table->foreignId('course_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete(); $table->string('title'); $table->text('body'); $table->timestamps();
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->foreignId('course_class_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->string('file_type', 100)->nullable()->after('mime_type');
            $table->unsignedInteger('downloads_count')->default(0)->after('download_count');
        });
    }
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) { $table->dropConstrainedForeignId('course_class_id'); $table->dropColumn(['file_type', 'downloads_count']); });
        Schema::dropIfExists('announcements'); Schema::dropIfExists('class_enrollments'); Schema::dropIfExists('course_classes');
        Schema::dropIfExists('lecturer_profiles'); Schema::dropIfExists('student_profiles');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};
