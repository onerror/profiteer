<?php

use ApiControllers\MainPageDataController;
use Bramus\Router\Router;
use FrontControllers\MainPageController;
use Registry\Registry;
use Repositories\CustomerRepository;
use Repositories\ExhangeRatesRepository;
use Repositories\OrderRepository;

$template404Path = __DIR__ . '/../src/templates/404.php';

$router = new Router();
$testDb = Registry::get(Registry::DB);
// set static routes
$router->set404(function () use ($template404Path) {
    header('HTTP/1.1 404 Not Found');
    require($template404Path);
});
$router->get('/', function () {
    MainPageController::mainPage();
});
$router->get('/', MainPageController::class . '@get');
$router->get('/api/', MainPageController::class . '@get');
$router->get('/api/{start}/{end}', function ($start, $end) use ($testDb) {
    MainPageDataController::get(
        (new DateTimeImmutable())->setTimestamp((int)$start),
        (new DateTimeImmutable())->setTimestamp((int)$end)
    );
});
$router->run();