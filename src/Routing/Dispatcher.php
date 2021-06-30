<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Routing;

use Psr\Http\Message\UriFactoryInterface;
use Slim\Interfaces\DispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Routing\RoutingResults;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Dispatcher implements DispatcherInterface
{
    private array $allowedMethodsByUri = [];

    public function __construct(
        private RouteCollectorInterface $routeCollector,
        private UriFactoryInterface $uriFactory,
    ) {}

    /**
     * @inheritDoc
     */
    public function dispatch(string $method, string $uri): RoutingResults
    {
        $routes = new RouteCollection();

        foreach ($this->routeCollector->getRoutes() as $route) {
            $this->allowedMethodsByUri[$route->getPattern()] = $route->getMethods();

            $routes->add(
                name: $route->getIdentifier(),
                route: (new Route(
                    path: $route->getPattern(),
                ))->setMethods(
                    methods: $route->getMethods(),
                ),
            );
        }

        $parsedUri = $this->uriFactory->createUri(
            uri: $uri,
        );
        $requestContext = new RequestContext(
            baseUrl: '',
            method: $method,
            host: $parsedUri->getHost(),
            scheme: $parsedUri->getScheme(),
            httpPort: $parsedUri->getPort() ?? 80,
            httpsPort: $parsedUri->getPort() ?? 443,
            path: $parsedUri->getPath(),
            queryString: $parsedUri->getQuery(),
        );

        $matcher = new UrlMatcher(
            routes: $routes,
            context: $requestContext,
        );

        try {
            $match = $matcher->match(
                pathinfo: $uri,
            );
            $identifier = '';
            $arguments = [];

            foreach ($match as $key => $value) {

                switch ($key) {
                    case '_route':
                        $identifier = $value;
                        break;

                    case '_controller':
                        // Do Nothing
                        break;

                    default:
                        $arguments[$key] = $value;
                        break;
                }
            }

            return new RoutingResults(
                dispatcher: $this,
                method: $method,
                uri: $uri,
                routeStatus: RoutingResults::FOUND,
                routeIdentifier: $identifier,
                routeArguments: $arguments,
            );
        } catch (ResourceNotFoundException $e) {
            return new RoutingResults(
                dispatcher: $this,
                method: $method,
                uri: $uri,
                routeStatus: RoutingResults::NOT_FOUND,
            );
        } catch (MethodNotAllowedException $e) {
            $this->allowedMethodsByUri[$uri] = $e->getAllowedMethods();
            return new RoutingResults(
                dispatcher: $this,
                method: $method,
                uri: $uri,
                routeStatus: RoutingResults::METHOD_NOT_ALLOWED
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getAllowedMethods(string $uri): array
    {
        return $this->allowedMethodsByUri[$uri] ?? [];
    }
}
