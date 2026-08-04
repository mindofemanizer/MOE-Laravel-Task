# MOE Laravel Task

Package manajemen task/workflow untuk Laravel â€” assignment, prioritas, subtask, komentar.

## Persyaratan

- PHP `^8.2`
- Laravel `^11 | ^12 | ^13`

## Instalasi

```bash
composer require moe/laravel-task:dev-main
php artisan vendor:publish --provider="Moe\\Task\\TaskServiceProvider" --tag="moe-task-config"
php artisan migrate
```

## Mulai Cepat

### 1. Buat task

```php
use Moe\Task\Facades\Task;

$task = Task::create([
    'title' => 'Buat Draft Akta Jual Beli',
    'priority' => 'high',
    'due_date' => now()->addDays(3),
    'tenant_id' => 1,
]);
```

### 2. Workflow status

```php
Task::start($task);              // open -> in_progress
Task::submitForReview($task);    // in_progress -> review
Task::complete($task, 4.5);     // review -> completed
Task::cancel($task);             // open/in_progress -> cancelled
```

### 3. Assign

```php
Task::assign($task, 5, 1);      // assign ke user 5 oleh user 1
Task::reassign($task, 10, 1);   // reassign ke user 10
```

### 4. Komentar

```php
Task::addComment($task, [
    'user_id' => 1,
    'user_type' => 'App\\Models\\User',
    'body' => 'Dokumen sudah lengkap, lanjut review',
]);
```

### 5. Subtask

```php
Task::createSubtasks($task, [
    ['title' => 'Cek Sertifikat', 'priority' => 'high'],
    ['title' => 'Verifikasi Identitas', 'priority' => 'medium'],
]);
```

### 6. Cek overdue

```php
Task::overdue()->count();           // task lewat deadline
Task::markOverdueTasks();           // auto-mark
```

## Status Task

```
open â†’ in_progress â†’ review â†’ completed
  â†“         â†“
cancelled cancelled
```

## Assignable (Trait)

```php
use Moe\Task\Traits\Assignable;

class Document extends Model
{
    use Assignable;

    // $document->tasks()          -> MorphMany (taskable)
    // $document->createTask([])   -> Task
    // $document->pendingTasks()   -> Collection
}
```

## Prioritas

`low` â†’ `medium` â†’ `high` â†’ `urgent`

## Konfigurasi

```php
// config/moe-task.php
return [
    'default_status' => 'open',
    'priorities' => ['low', 'medium', 'high', 'urgent'],
    'statuses' => ['open', 'in_progress', 'review', 'completed', 'cancelled'],
    'categories' => ['enabled' => true],
    'dependencies' => ['enabled' => true, 'max_depth' => 5],
    'comments' => ['enabled' => true],
    'overdue' => ['check_hours' => 1, 'auto_mark' => true],
];
```

## Testing

```bash
composer test
```

## Lisensi

MIT Â© MOE (MindOfEmanizer)
