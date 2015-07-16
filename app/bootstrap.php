<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use MoveElevator\Supervisord\Monitor\XmlRpc\Parser;
use MoveElevator\Supervisord\Monitor\XmlRpc\Client;
use MoveElevator\Supervisord\Monitor\XmlRpc\Converter;

$app = new Application();

$app['debug'] = true;

$app['config_path'] = __DIR__ . '/config.yml';

$app['xmlrpc.parser'] = $app->share(function ($app) {
    return new Parser($app['config_path']);
});

$app['xmlrpc.client'] = $app->share(function ($app) {
    return new Client($app['xmlrpc.parser']);
});

$app['xmlrpc.converter'] = $app->share(function () {
    return new Converter();
});

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../resources/views',
]);

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig', [
        'servers' => $app['xmlrpc.converter']->getServers($app['xmlrpc.parser']->getServers())
    ]);
});

$app->get('/{id}', function ($id) use ($app) {
    return $app['twig']->render('show.twig', [
        'server' => $app['xmlrpc.converter']->getServer($app['xmlrpc.parser']->getServer($id)),
        'processes' => $app['xmlrpc.converter']->getProcesses($app['xmlrpc.client']->getProcesses($id)),
    ]);
});

return $app;
