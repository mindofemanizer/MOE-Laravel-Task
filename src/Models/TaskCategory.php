<?php

declare(strict_types=1);

namespace Moe\Task\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskCategory extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $table = 'task_categories';

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'category_id');
    }
}
