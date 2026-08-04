<?php

declare(strict_types=1);

namespace Moe\Task\Exceptions;

use RuntimeException;

class TaskException extends RuntimeException
{
    public static function invalidTransition(string $current, string $target): self
    {
        return new self("Cannot transition task from [{$current}] to [{$target}].");
    }

    public static function alreadyCompleted(): self
    {
        return new self('Task is already completed.');
    }

    public static function dependencyNotMet(): self
    {
        return new self('Cannot start task: dependencies are not completed.');
    }
}
