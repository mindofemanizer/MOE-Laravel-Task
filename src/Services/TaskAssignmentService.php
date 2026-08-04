<?php

declare(strict_types=1);

namespace Moe\Task\Services;

use Illuminate\Support\Facades\Log;
use Moe\Task\Models\Task;

class TaskAssignmentService
{
    public function assign(Task $task, int $userId, ?int $assignedBy = null): void
    {
        $task->update([
            'assigned_to' => $userId,
            'assigned_by' => $assignedBy,
        ]);

        Log::info("[MOE Task] Assigned task #{$task->id} to user #{$userId}", [
            'task_id' => $task->id,
            'assigned_to' => $userId,
            'assigned_by' => $assignedBy,
        ]);
    }

    public function reassign(Task $task, int $newUserId, ?int $assignedBy = null): void
    {
        $oldUserId = $task->assigned_to;

        $task->update([
            'assigned_to' => $newUserId,
            'assigned_by' => $assignedBy ?? $task->assigned_by,
        ]);

        Log::info("[MOE Task] Reassigned task #{$task->id} from user #{$oldUserId} to user #{$newUserId}", [
            'task_id' => $task->id,
            'from' => $oldUserId,
            'to' => $newUserId,
            'assigned_by' => $assignedBy,
        ]);
    }

    public function unassign(Task $task): void
    {
        $task->update([
            'assigned_to' => null,
            'assigned_by' => null,
        ]);
    }

    public function loadForUser(int $userId, ?array $statuses = null): iterable
    {
        $query = Task::byAssignee($userId);

        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        return $query->pending()->orderBy('priority')->orderBy('due_date')->get();
    }
}
