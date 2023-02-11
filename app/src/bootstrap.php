<?php

use Registry\Registry;
use Views\ErrorPageView;

$loggerConfig = ['title' => 'name', 'file_name' => 'test.log'];
$testDbConfig = [
    'dsn' => 'mysql:dbname=test;host=mysql',
    'username' => 'pyastolov',
    'password' => '123456',
    'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
];

// logger init
$log = new Monolog\Logger($loggerConfig['title']);
$log->pushHandler(new Monolog\Handler\StreamHandler($loggerConfig['file_name'], Monolog\Logger::ERROR));

try {
    // create db connection
    $testDb = new PDO(
        $testDbConfig['dsn'],
        $testDbConfig['username'],
        $testDbConfig['password'],
        $testDbConfig['options']
    );

    Registry::set(Registry::LOGGER, $log);
    Registry::set(Registry::DB, $testDb);
    
    require(__DIR__ . '/routes.php');
} catch (Throwable $e) {
    $log->error($e->getMessage(), $e->getTrace());
    ErrorPageView::render(['error' => 'An error happened, sorry ' . $e->getMessage()]);
} finally {
    unset($testDb);
}

