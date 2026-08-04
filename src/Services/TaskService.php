<?php

declare(strict_types=1);

namespace Moe\Task\Services;

use Moe\Task\Models\Task;

class TaskService
{
    public function __construct(
        protected TaskAssignmentService $assignmentService,
    ) {}

    public function create(array $data): Task
    {
        $data['status'] ??= 'open';
        $data['priority'] ??= 'medium';

        return Task::create($data);
    }

    public function start(Task $task): Task
    {
        $task->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return $task->fresh();
    }

    public function submitForReview(Task $task): Task
    {
        $task->update(['status' => 'review']);

        return $task->fresh();
    }

    public function complete(Task $task, ?float $actualHours = null): Task
    {
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
            'actual_hours' => $actualHours ?? $task->actual_hours,
        ]);

        return $task->fresh();
    }

    public function cancel(Task $task, ?string $reason = null): Task
    {
        $task->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return $task->fresh();
    }

    public function assign(Task $task, int $userId, ?int $assignedBy = null): Task
    {
        $this->assignmentService->assign($task, $userId, $assignedBy);

        return $task->fresh();
    }

    public function reassign(Task $task, int $newUserId, ?int $assignedBy = null): Task
    {
        $this->assignmentService->reassign($task, $newUserId, $assignedBy);

        return $task->fresh();
    }

    public function addComment(Task $task, array $data): Task
    {
        $task->comments()->create($data);

        return $task->fresh(['comments']);
    }

    public function createSubtasks(Task $parent, array $subtasks): Task
    {
        foreach ($subtasks as $data) {
            $data['parent_id'] = $parent->id;
            $data['tenant_id'] = $parent->tenant_id;
            $data['taskable_type'] = $parent->taskable_type;
            $data['taskable_id'] = $parent->taskable_id;
            Task::create($data);
        }

        return $parent->fresh(['subtasks']);
    }

    public function markOverdueTasks(): int
    {
        return Task::where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->update(['status' => 'overdue']);
    }
}
