<?php

declare(strict_types=1);

namespace Moe\Task\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Moe\Task\Models\Task;

trait Assignable
{
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function createTask(array $data): Task
    {
        return app(\Moe\Task\Services\TaskService::class)
            ->create(array_merge($data, [
                'taskable_type' => $this->getMorphClass(),
                'taskable_id' => $this->getKey(),
            ]));
    }

    public function pendingTasks()
    {
        return $this->tasks()->pending()->orderBy('due_date')->get();
    }
}
