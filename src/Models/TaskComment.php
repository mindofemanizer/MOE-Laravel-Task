<?php

declare(strict_types=1);

namespace Moe\Task\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'user_type',
        'body',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $table = 'task_comments';

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
