<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Error\Renderers;

use Throwable;

class JsonProblemRenderer extends AbstractProblemRenderer
{
    /**
     * @inheritDoc
     */
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $problem = $this->createApiProblem(
            exception: $exception,
            displayErrorDetails: $displayErrorDetails,
        );
        return $problem->asJson(
            pretty: true,
        );
    }
}
