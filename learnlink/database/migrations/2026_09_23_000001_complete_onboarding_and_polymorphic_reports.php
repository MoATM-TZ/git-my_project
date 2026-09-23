<?php

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'student')->orderBy('id')->chunkById(200, function ($users): void {
            foreach ($users as $user) {
                if (DB::table('student_profiles')->where('user_id', $user->id)->exists()) {
                    continue;
                }
                DB::table('student_profiles')->insert([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'registration_number' => 'PENDING-'.$user->id,
                    'year_of_study' => 1,
                    'phone_number' => 'PENDING',
                    'department' => 'Unassigned',
                    'program_level' => 'Degree',
                    'program_name' => 'Unassigned',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->string('reportable_type')->nullable();
            $table->unsignedBigInteger('reportable_id')->nullable();
            $table->foreignId('actioned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->index(['reportable_type', 'reportable_id']);
            $table->index(['status', 'created_at']);
        });

        DB::table('reports')->whereNotNull('resource_id')->update([
            'reportable_type' => Resource::class,
        ]);
        DB::table('reports')->whereNotNull('resource_id')->update([
            'reportable_id' => DB::raw('resource_id'),
        ]);

        Schema::table('reports', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('resource_id');
            $table->string('reportable_type')->nullable(false)->change();
            $table->unsignedBigInteger('reportable_id')->nullable(false)->change();
        });

        Schema::table('lecturer_profiles', function (Blueprint $table): void {
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lecturer_profiles', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn('verified_at');
        });

        // Reports on comments cannot be represented by the legacy resource-only schema.
        // Refuse destructive rollback if any such report exists.
        if (DB::table('reports')->where('reportable_type', '!=', Resource::class)->exists()) {
            throw new RuntimeException('Cannot roll back polymorphic reports while comment reports exist.');
        }

        Schema::table('reports', function (Blueprint $table): void {
            $table->foreignId('resource_id')->nullable()->constrained()->cascadeOnDelete();
        });
        DB::table('reports')->where('reportable_type', Resource::class)->update([
            'resource_id' => DB::raw('reportable_id'),
        ]);
        Schema::table('reports', function (Blueprint $table): void {
            $table->unsignedBigInteger('resource_id')->nullable(false)->change();
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['reportable_type', 'reportable_id']);
            $table->dropForeign(['actioned_by']);
            $table->dropColumn(['reportable_type', 'reportable_id', 'actioned_by']);
        });
    }
};
