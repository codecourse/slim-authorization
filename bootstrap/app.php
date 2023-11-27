<?php

use Dotenv\Dotenv;
use App\Models\User;
use Slim\Views\Twig;
use App\Authorisation\Gate;
use App\Exceptions\Handler;
use Slim\Factory\AppFactory;
use Slim\Views\TwigExtension;
use Slim\Psr7\Factory\UriFactory;
use Dotenv\Exception\InvalidPathException;
use App\Authorisation\Exceptions\GateAuthorisationException;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv(__DIR__ . '/../'))->load();
} catch (InvalidPathException $e) {
    //
}

$container = new DI\Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('settings', function () {
    return [
        'app' => [
            'name' => getenv('APP_NAME')
        ]
    ];
});

$container->set('user', function () {
    return new User(1);
});

$container->set('gate', function ($container) {
    return new Gate(function () use ($container) {
        return $container->get('user');
    });
});

$container->set('view', function ($container) use ($app) {
    $twig = new Twig(__DIR__ . '/../resources/views', [
        'cache' => false
    ]);

    $twig->addExtension(
        new TwigExtension(
            $app->getRouteCollector()->getRouteParser(),
            (new UriFactory)->createFromGlobals($_SERVER),
            '/'
        )
    );

    return $twig;
});

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setDefaultErrorHandler(
    new Handler(
        $container->get('view'),
        $app->getResponseFactory()
    )
);

require_once __DIR__ . '/authorisation.php';
require_once __DIR__ . '/../routes/web.php';
