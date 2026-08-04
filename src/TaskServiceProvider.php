<?php

declare(strict_types=1);

namespace Moe\Task;

use Illuminate\Support\ServiceProvider;
use Moe\Task\Services\TaskService;
use Moe\Task\Services\TaskAssignmentService;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/moe-task.php', 'moe-task');

        $this->app->singleton(TaskAssignmentService::class);
        $this->app->singleton(TaskService::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/moe-task.php' => config_path('moe-task.php'),
            ], 'moe-task-config');

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
