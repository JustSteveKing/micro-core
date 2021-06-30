<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

class HttpValidationException extends HttpBadRequestException
{
    private array $messages;

    private static function fromValidationException(
        ServerRequestInterface $request,
        ValidationException $exception,
    ): HttpBadRequestException {
        $error = new self(
            request: $request,
            message: $exception->getMessage(),
        );

        $error->messages = $exception->getMessages();

        return $error;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
