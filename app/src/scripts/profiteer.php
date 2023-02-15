<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/bootstrap_script.php';

use GuzzleHttp\Client;
use Registry\Registry;
use Repositories\RatesRepository;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\TelegramPublisher;

$kapitalBankURL = 'https://kapitalbank.uz/ru/services/exchange-rates/';

$client = new Client();
$response = $client->get($kapitalBankURL);

$body = $response->getBody();
$html = $body->getContents();

$crawler = new Crawler($html);
$buyUSDValue = $crawler->filter('div.item-usd div.item-rate-buy span.item-value')->each(
    function (Crawler $node, $i) {
        return $node->text();
    }
);
$buyUSDVector = $crawler->filter('div.item-usd div.item-rate-buy span.item-value-vector')->each(
    function (Crawler $node, $i) {
        return $node->text();
    }
);
$sellUSDValue = $crawler->filter('div.item-usd div.item-rate-sale span.item-value')->each(
    function (Crawler $node, $i) {
        return $node->text();
    }
);
$sellUSDVector = $crawler->filter('div.item-usd div.item-rate-sale span.item-value-vector')->each(
    function (Crawler $node, $i) {
        return $node->text();
    }
);

$data = [
    'usd_buy' => $buyUSDValue[0],
    'usd_sell' => $sellUSDValue[0],
    'usd_buy_vector' => $buyUSDVector[0],
    'usd_sell_vector' => $sellUSDVector[0],
];

$ratesRepository = new RatesRepository(Registry::get(Registry::DB));
$ratesRepository->addRates(
    $buyUSDValue[0] * 100,
    $sellUSDValue[0] * 100,
    $buyUSDVector[0] * 100,
    $sellUSDVector[0] * 100
);

/**
 * @var TelegramPublisher $telegramHandler
 */
$telegramHandler = Registry::get(Registry::TELEGRAM);

$response = $telegramHandler->publish("Hello from PHP!\n USD Buying for =" . $data['usd_buy']);

echo("Done");
echo($response);
die();
