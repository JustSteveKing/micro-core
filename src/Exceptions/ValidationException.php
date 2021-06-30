<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Exceptions;

use RuntimeException;

final class ValidationException extends RuntimeException
{
    private array $messages;

    public static function withMessages(array $messages): ValidationException
    {
        $exception = new ValidationException(
            message: "Validation Failure.",
        );

        $exception->messages = $messages;

        return $exception;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
