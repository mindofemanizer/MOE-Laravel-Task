<?php

declare(strict_types=1);

namespace MOE\Task\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'tenant_id',
        'taskable_type',
        'taskable_id',
        'parent_id',
        'category_id',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'assigned_by',
        'due_date',
        'started_at',
        'completed_at',
        'cancelled_at',
        'estimated_hours',
        'actual_hours',
        'sort_order',
        'metadata',
    ];

    protected $attributes = [
        'status' => 'open',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'estimated_hours' => 'float',
        'actual_hours' => 'float',
        'sort_order' => 'integer',
        'metadata' => 'array',
    ];

    protected $table = 'tasks';

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function dependencies(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id')->whereNotNull('parent_id');
    }

    public function isOpen(): bool { return $this->status === 'open'; }
    public function isInProgress(): bool { return $this->status === 'in_progress'; }
    public function isReview(): bool { return $this->status === 'review'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function isOverdue(): bool
    {
        return $this->due_date && Carbon::now()->gt($this->due_date)
            && ! in_array($this->status, ['completed', 'cancelled']);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'review']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeByAssignee($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }
}
