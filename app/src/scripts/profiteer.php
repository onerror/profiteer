<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/bootstrap_script.php';

use AppConfigs\AppConfig;
use GuzzleHttp\Client;
use Registry\Registry;
use Repositories\RatesRepository;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\TelegramPublisher;
use ValueObjects\Rates;

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

$ratesRepository = new RatesRepository(Registry::get(Registry::DB));

$lastRates = $ratesRepository->getLastRates();

$currentRates = new Rates(
    $buyUSDValue[0],
    $sellUSDValue[0],
    $buyUSDVector[0],
    $sellUSDVector[0]
);

if ($currentRates->isDifferentFrom($lastRates)) {
    $ratesRepository->addRates(
        $currentRates
    );
}

/**
 * @var TelegramPublisher $telegramHandler
 */
$telegramHandler = Registry::get(Registry::TELEGRAM);

/**
 * @var AppConfig $appThresholds
 */
$appThresholds = Registry::get(Registry::APP);

if (abs($currentRates->getBuyRateDifferenceTo($lastRates)) >= $appThresholds->getBigThreshold()) {
    $telegramHandler->publish(
        "Buy rate USD LEAPED from " . $lastRates->getBuyRate() . " sums per $ to current " . $currentRates->getBuyRate(
        ) . " sums per $ with vector=" . $currentRates->getBuyRateVector()
    );
} elseif (abs($currentRates->getBuyRateDifferenceTo($lastRates)) <= $appThresholds->getSmallThreshold()) {
    $telegramHandler->publish(
        "Buy rate USD slightly changed from " . $lastRates->getBuyRate(
        ) . " sums per $ to current " . $currentRates->getBuyRate(
        ) . " sums per $ with vector=" . $currentRates->getBuyRateVector()
    );
}
if (($currentRates->getSellRate() - $currentRates->getBuyRate()) > $appThresholds->getMarginMinThreshold()) {
    $telegramHandler->publish(
        "USD threshold is very small, the rate is stable: Sell=" . $currentRates->getSellRate(
        ) . "sums per $ and buy=" . $currentRates->getBuyRate() . " sums per $"
    );
}


$response = $telegramHandler->publish("Hello from PHP!\n USD Buying for =" . $currentRates->getBuyRate());

echo("Done");
echo($response);
die();
