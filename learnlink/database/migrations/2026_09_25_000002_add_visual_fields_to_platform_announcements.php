<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('platform_announcements', function (Blueprint $table): void {
            $table->string('banner_image')->nullable();
            $table->string('display_type')->default('card');
            $table->string('action_label')->nullable();
            $table->string('action_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('platform_announcements', function (Blueprint $table): void {
            $table->dropColumn(['banner_image', 'display_type', 'action_label', 'action_url']);
        });
    }
};
