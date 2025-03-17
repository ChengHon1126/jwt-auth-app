<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
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
        // 設置多態關聯的映射
        Relation::morphMap([
            'lessons' => \App\Models\LessonPlans::class,
            'works' => \App\Models\Work::class,
            // 其他多態映射...
        ]);
    }
}
