<?php

declare(strict_types=1);

use MOE\Task\Facades\Task as TaskFacade;
use MOE\Task\Models\Task;
use MOE\Task\Services\TaskService;
use MOE\Task\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->service = app(TaskService::class);
    $this->task = $this->service->create([
        'title' => 'Buat Draft Akta',
        'priority' => 'high',
    ]);
});

it('resolves task service', function () {
    expect($this->service)->toBeInstanceOf(TaskService::class);
});

it('creates task via service', function () {
    expect($this->task->title)->toBe('Buat Draft Akta');
    expect($this->task->status)->toBe('open');
});

it('starts task', function () {
    $started = $this->service->start($this->task);

    expect($started->status)->toBe('in_progress');
    expect($started->started_at)->not->toBeNull();
});

it('submits for review', function () {
    $this->service->start($this->task);
    $review = $this->service->submitForReview($this->task);

    expect($review->status)->toBe('review');
});

it('completes task', function () {
    $this->service->start($this->task);
    $done = $this->service->complete($this->task, 4.5);

    expect($done->status)->toBe('completed');
    expect((float) $done->actual_hours)->toBe(4.5);
});

it('cancels task', function () {
    $cancelled = $this->service->cancel($this->task);

    expect($cancelled->status)->toBe('cancelled');
});

it('assigns task', function () {
    $assigned = $this->service->assign($this->task, 5, 1);

    expect($assigned->assigned_to)->toBe(5);
    expect($assigned->assigned_by)->toBe(1);
});

it('reassigns task', function () {
    $this->service->assign($this->task, 5, 1);
    $reassigned = $this->service->reassign($this->task, 10, 1);

    expect($reassigned->assigned_to)->toBe(10);
});

it('adds comment to task', function () {
    $commented = $this->service->addComment($this->task, [
        'user_id' => 1,
        'user_type' => 'App\\Models\\User',
        'body' => 'Siap dikerjakan',
    ]);

    expect($commented->comments)->toHaveCount(1);
    expect($commented->comments->first()->body)->toBe('Siap dikerjakan');
});

it('creates subtasks', function () {
    $parent = $this->service->createSubtasks($this->task, [
        ['title' => 'Cek Dokumen', 'priority' => 'high'],
        ['title' => 'Verifikasi Data', 'priority' => 'medium'],
    ]);

    expect($parent->subtasks)->toHaveCount(2);
});

it('facade delegates to service', function () {
    $task = TaskFacade::create(['title' => 'Via Facade']);

    expect($task->title)->toBe('Via Facade');
    expect(TaskFacade::start($task)->status)->toBe('in_progress');
});
