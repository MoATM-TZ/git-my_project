<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Report;
use App\Models\Resource;
use App\Models\PlatformAnnouncement;
use App\Policies\CommentPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ResourcePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Resource::class, ResourcePolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Report::class, ReportPolicy::class);
        View::composer('layouts.app', function ($view): void {
            $view->with('urgentAnnouncement', PlatformAnnouncement::where('display_type', 'alert_bar')->latest()->first());
        });
    }
}
