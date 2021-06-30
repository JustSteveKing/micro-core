<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Handlers;

use JustSteveKing\Micro\Error\Renderers\JsonProblemRenderer;
use JustSteveKing\Micro\Error\Renderers\XmlProblemRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;

class ProblemDetailHandler extends ErrorHandler
{
    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        null|LoggerInterface $logger = null
    ) {
        parent::__construct($callableResolver, $responseFactory, $logger);

        $this->errorRenderers['application/json'] = JsonProblemRenderer::class;
        $this->errorRenderers['application/xml'] = XmlProblemRenderer::class;
    }

    protected function respond(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(
            code: $this->statusCode,
        );

        if (! is_null($this->contentType) && array_key_exists($this->contentType, $this->errorRenderers)) {
            $contentType = $this->contentType;

            if (stripos($this->contentType, 'json') !== false) {
                $contentType = 'application/problem+json';
            } elseif (stripos($this->contentType, 'xml') !== false) {
                $contentType = 'application/problem+xml';
            }

            $response = $response->withHeader(
                name: 'Content-Type',
                value: $contentType,
            );
        } else {
            $response = $response->withHeader(
                name: 'Content-type',
                value: $this->defaultErrorRendererContentType,
            );
        }

        if ($this->exception instanceof HttpMethodNotAllowedException) {
            $allowedMethods = implode(', ', $this->exception->getAllowedMethods());
            $response = $response->withHeader(
                name: 'Allow',
                value: $allowedMethods,
            );
        }

        $renderer = $this->determineRenderer();
        $body = call_user_func($renderer, $this->exception, $this->displayErrorDetails);
        $response->getBody()->write(
            string: $body,
        );

        return $response;
    }
}
