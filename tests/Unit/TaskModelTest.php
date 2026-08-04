<?php

declare(strict_types=1);

use Moe\Task\Models\Task;
use Moe\Task\Models\TaskCategory;
use Moe\Task\Tests\TestCase;

uses(TestCase::class);

it('creates task', function () {
    $task = Task::create([
        'title' => 'Buat Akta Jual Beli',
        'priority' => 'high',
    ]);

    expect($task->title)->toBe('Buat Akta Jual Beli');
    expect($task->status)->toBe('open');
    expect($task->priority)->toBe('high');
});

it('creates task with category', function () {
    $category = TaskCategory::create(['name' => 'Akta', 'slug' => 'akta']);
    $task = Task::create([
        'title' => 'Review Akta Hibah',
        'category_id' => $category->id,
    ]);

    expect($task->category->name)->toBe('Akta');
});

it('creates subtasks', function () {
    $parent = Task::create(['title' => 'Proses Akta']);
    $child = Task::create(['title' => 'Cek Sertifikat', 'parent_id' => $parent->id]);

    expect($parent->subtasks)->toHaveCount(1);
    expect($child->parent->id)->toBe($parent->id);
});

it('scopes pending tasks', function () {
    Task::create(['title' => 'Open Task', 'status' => 'open']);
    Task::create(['title' => 'Done Task', 'status' => 'completed']);

    expect(Task::pending()->count())->toBe(1);
});

it('scopes by assignee', function () {
    Task::create(['title' => 'My Task', 'assigned_to' => 1]);
    Task::create(['title' => 'Other Task', 'assigned_to' => 2]);

    expect(Task::byAssignee(1)->count())->toBe(1);
});

it('checks if task is overdue', function () {
    $task = Task::create([
        'title' => 'Urgent',
        'due_date' => now()->subDay(),
        'status' => 'open',
    ]);

    expect($task->isOverdue())->toBeTrue();
});
