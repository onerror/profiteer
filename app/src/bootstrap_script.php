<?php

use Registry\Registry;
use Telegram\TelegramPublisher;
use Views\ErrorPageView;

$configFile = __DIR__ . '/../configs/config.json';

$strJsonFileContents = file_get_contents($configFile);
$configs = json_decode($strJsonFileContents, true);
// logger init
$log = new Monolog\Logger($configs['logger']['title']);
$log->pushHandler(new Monolog\Handler\StreamHandler("php://stdout", Monolog\Logger::ERROR));

try {
    // create db connection
    $testDb = new PDO(
        $configs['db']['dsn'],
        $configs['db']['username'],
        $configs['db']['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
    
    $telegramPublisher = new TelegramPublisher(
        $configs['telegram']['api_token'], $configs['telegram']['chat_id']
    );
    
    $appConfig = new \AppConfigs\AppConfig(
        $configs['app']['rate_change_for_small_alarm'],
        $configs['app']['rate_change_for_big_alarm'],
        $configs['app']['margin_min_threshold']
    );
    
    Registry::set(Registry::LOGGER, $log);
    Registry::set(Registry::DB, $testDb);
    Registry::set(Registry::TELEGRAM, $telegramPublisher);
    Registry::set(Registry::APP, $appConfig);

} catch (Throwable $e) {
    $log->error($e->getMessage(), $e->getTrace());
} finally {
    unset($testDb);
}

