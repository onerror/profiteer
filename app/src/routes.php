<?php

use ApiControllers\DashboardDataController;
use Bramus\Router\Router;
use FrontControllers\DashboardController;
use Registry\Registry;
use Repositories\CustomerRepository;
use Repositories\DashboardRepository;
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
    DashboardController::mainPage();
});
$router->get('/api/dashboard_data', DashboardDataController::class . '@get');
$router->get('/api/dashboard_data/{start}/{end}', function ($start, $end) use ($testDb) {
    DashboardDataController::get(
        (new DateTimeImmutable())->setTimestamp((int)$start),
        (new DateTimeImmutable())->setTimestamp((int)$end),
        new DashboardRepository($testDb),
        new OrderRepository($testDb),
        new CustomerRepository($testDb)
    );
});
$router->run();