<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Error\Renderers;

use Crell\ApiProblem\ApiProblem;
use JustSteveKing\Micro\Exceptions\HttpValidationException;
use Slim\Interfaces\ErrorRendererInterface;
use Throwable;

abstract class AbstractProblemRenderer implements ErrorRendererInterface
{
    protected function createApiProblem(Throwable $exception, bool $displayErrorDetails): ApiProblem
    {
        $problem = new ApiProblem(
            title: $exception->getMessage(),
            type: $this->urlForCode(
                code: $exception->getCode(),
            ),
        );

        if ($exception instanceof HttpValidationException) {
            $problem['messages'] = $exception->getMessages();
        }

        if ($displayErrorDetails) {
            $problem['exception'] = [];
            do {
                $problem['exception'][] = $this->formatExceptionFragment(
                    exception: $exception,
                );
            } while ($exception = $exception->getPrevious());
        }

        return $problem;
    }

    protected function urlForCode($code)
    {
        $urls = [
            100 => 'https://tools.ietf.org/html/rfc7231#section-6.2.1',
            101 => 'https://tools.ietf.org/html/rfc7231#section-6.2.2',
            200 => 'https://tools.ietf.org/html/rfc7231#section-6.3.1',
            201 => 'https://tools.ietf.org/html/rfc7231#section-6.3.2',
            202 => 'https://tools.ietf.org/html/rfc7231#section-6.3.3',
            203 => 'https://tools.ietf.org/html/rfc7231#section-6.3.4',
            204 => 'https://tools.ietf.org/html/rfc7231#section-6.3.5',
            205 => 'https://tools.ietf.org/html/rfc7231#section-6.3.6',
            206 => 'https://tools.ietf.org/html/rfc7233#section-4.1',
            300 => 'https://tools.ietf.org/html/rfc7231#section-6.4.1',
            301 => 'https://tools.ietf.org/html/rfc7231#section-6.4.2',
            302 => 'https://tools.ietf.org/html/rfc7231#section-6.4.3',
            303 => 'https://tools.ietf.org/html/rfc7231#section-6.4.4',
            304 => 'https://tools.ietf.org/html/rfc7232#section-4.1',
            305 => 'https://tools.ietf.org/html/rfc7231#section-6.4.5',
            307 => 'https://tools.ietf.org/html/rfc7231#section-6.4.7',
            400 => 'https://tools.ietf.org/html/rfc7231#section-6.5.1',
            401 => 'https://tools.ietf.org/html/rfc7235#section-3.1',
            402 => 'https://tools.ietf.org/html/rfc7231#section-6.5.2',
            403 => 'https://tools.ietf.org/html/rfc7231#section-6.5.3',
            404 => 'https://tools.ietf.org/html/rfc7231#section-6.5.4',
            405 => 'https://tools.ietf.org/html/rfc7231#section-6.5.5',
            406 => 'https://tools.ietf.org/html/rfc7231#section-6.5.6',
            407 => 'https://tools.ietf.org/html/rfc7235#section-3.2',
            408 => 'https://tools.ietf.org/html/rfc7231#section-6.5.7',
            409 => 'https://tools.ietf.org/html/rfc7231#section-6.5.8',
            410 => 'https://tools.ietf.org/html/rfc7231#section-6.5.9',
            411 => 'https://tools.ietf.org/html/rfc7231#section-6.5.10',
            412 => 'https://tools.ietf.org/html/rfc7232#section-4.2',
            413 => 'https://tools.ietf.org/html/rfc7231#section-6.5.11',
            414 => 'https://tools.ietf.org/html/rfc7231#section-6.5.12',
            415 => 'https://tools.ietf.org/html/rfc7231#section-6.5.13',
            416 => 'https://tools.ietf.org/html/rfc7233#section-4.4',
            417 => 'https://tools.ietf.org/html/rfc7231#section-6.5.14',
            426 => 'https://tools.ietf.org/html/rfc7231#section-6.5.15',
            500 => 'https://tools.ietf.org/html/rfc7231#section-6.6.1',
            501 => 'https://tools.ietf.org/html/rfc7231#section-6.6.2',
            502 => 'https://tools.ietf.org/html/rfc7231#section-6.6.3',
            503 => 'https://tools.ietf.org/html/rfc7231#section-6.6.4',
            504 => 'https://tools.ietf.org/html/rfc7231#section-6.6.5',
            505 => 'https://tools.ietf.org/html/rfc7231#section-6.6.6',
        ];

        if (isset($urls[$code])) {
            return $urls[$code];
        }

        return 'https://tools.ietf.org/html/rfc7231';
    }

    protected function formatExceptionFragment(Throwable $exception): array
    {
        return [
            'type' => get_class($exception),
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }
}
