# Micro Core

A collection of core components to be used within the [Micro boilerplate](https://github.com/JustSteveKing/micro) for slim framework.

The aim of this package is to provide a consistent way to start the SlimPHP project, allowing you to implement the concrete implementations based off of Interfaces provided by the core.

### Interfaces

The main interface is our `KernelContract`, this is the entrypoint to the application. It is where Slim is built and where we add our container.

It is recommended here that you do something like this:

```php
use JustSteveKing\Micro\Contracts\KernelContract;
use JustSteveKing\Micro\Kernel;
use Psr\Container\ContainerInterface;

return [
    KernelContract::class => function (ContainerInterface $container) {
        return Kernel::boot(
            basePath: __DIR__ . '/../', // this is the path to the root of your project.
            container: $container, // this will be the build container.
        );
    },
];
```
In the example above we are adding an entry into our container for the Kernel, using the default provided kernel and passing in the base options. You can easily override this `Kernel` class yourself - just ensure it implements the same Interface.
