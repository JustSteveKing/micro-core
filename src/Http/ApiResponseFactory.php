<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Http;

use JustSteveKing\StatusCode\Http;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class ApiResponseFactory
{
    public static function make(
        array $data = [],
        int $status = Http::OK,
        array $headers = [],
    ): ResponseInterface {
        $response = new Response(
            status: $status,
        );

        foreach ($headers as $key => $value) {
            $response = $response->withHeader(
                name: $key,
                value: $value,
            );
        }

        $response->getBody()->write(
            string: json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );

        return $response;
    }
}
