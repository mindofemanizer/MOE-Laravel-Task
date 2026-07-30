<?php

declare(strict_types=1);

namespace MOE\Task\Facades;

use Illuminate\Support\Facades\Facade;
use MOE\Task\Services\TaskService;

/**
 * @method static \MOE\Task\Models\Task create(array $data)
 * @method static \MOE\Task\Models\Task start(\MOE\Task\Models\Task $task)
 * @method static \MOE\Task\Models\Task submitForReview(\MOE\Task\Models\Task $task)
 * @method static \MOE\Task\Models\Task complete(\MOE\Task\Models\Task $task, ?float $actualHours = null)
 * @method static \MOE\Task\Models\Task cancel(\MOE\Task\Models\Task $task, ?string $reason = null)
 * @method static \MOE\Task\Models\Task assign(\MOE\Task\Models\Task $task, int $userId, ?int $assignedBy = null)
 * @method static \MOE\Task\Models\Task reassign(\MOE\Task\Models\Task $task, int $newUserId, ?int $assignedBy = null)
 * @method static \MOE\Task\Models\Task addComment(\MOE\Task\Models\Task $task, array $data)
 * @method static \MOE\Task\Models\Task createSubtasks(\MOE\Task\Models\Task $parent, array $subtasks)
 * @method static int markOverdueTasks()
 */
class Task extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TaskService::class;
    }
}
