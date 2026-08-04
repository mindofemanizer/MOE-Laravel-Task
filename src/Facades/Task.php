<?php

declare(strict_types=1);

namespace Moe\Task\Facades;

use Illuminate\Support\Facades\Facade;
use Moe\Task\Services\TaskService;

/**
 * @method static \Moe\Task\Models\Task create(array $data)
 * @method static \Moe\Task\Models\Task start(\Moe\Task\Models\Task $task)
 * @method static \Moe\Task\Models\Task submitForReview(\Moe\Task\Models\Task $task)
 * @method static \Moe\Task\Models\Task complete(\Moe\Task\Models\Task $task, ?float $actualHours = null)
 * @method static \Moe\Task\Models\Task cancel(\Moe\Task\Models\Task $task, ?string $reason = null)
 * @method static \Moe\Task\Models\Task assign(\Moe\Task\Models\Task $task, int $userId, ?int $assignedBy = null)
 * @method static \Moe\Task\Models\Task reassign(\Moe\Task\Models\Task $task, int $newUserId, ?int $assignedBy = null)
 * @method static \Moe\Task\Models\Task addComment(\Moe\Task\Models\Task $task, array $data)
 * @method static \Moe\Task\Models\Task createSubtasks(\Moe\Task\Models\Task $parent, array $subtasks)
 * @method static int markOverdueTasks()
 */
class Task extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TaskService::class;
    }
}
