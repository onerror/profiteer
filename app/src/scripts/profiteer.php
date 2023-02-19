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

if ($currentRates->areDifferentFrom($lastRates)) {
    $ratesRepository->storeRates(
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
        ) . " sums per $ and diff=" . $currentRates->getBuyRateDifferenceTo(
            $lastRates
        ) . " with vector=" . $currentRates->getBuyRateVector()
    );
} elseif (abs($currentRates->getBuyRateDifferenceTo($lastRates)) >= $appThresholds->getSmallThreshold()) {
    $telegramHandler->publish(
        "Buy rate USD slightly changed from " . $lastRates->getBuyRate(
        ) . " sums per $ to current " . $currentRates->getBuyRate(
        ) . " sums per $ and diff=" . $currentRates->getBuyRateDifferenceTo(
            $lastRates
        ) . " with vector=" . $currentRates->getBuyRateVector()
    );
}
if (abs($currentRates->getSellRateDifferenceTo($lastRates)) >= $appThresholds->getBigThreshold()) {
    $telegramHandler->publish(
        "Sell rate USD LEAPED from " . $lastRates->getSellRate(
        ) . " sums per $ to current " . $currentRates->getSellRate(
        ) . " sums per $ and diff=" . $currentRates->getSellRateDifferenceTo(
            $lastRates
        ) . " with vector=" . $currentRates->getSellRateVector()
    );
} elseif (abs($currentRates->getSellRateDifferenceTo($lastRates)) >= $appThresholds->getSmallThreshold()) {
    $telegramHandler->publish(
        "Sell rate USD slightly changed from " . $lastRates->getSellRate(
        ) . " sums per $ to current " . $currentRates->getSellRate(
        ) . " sums per $ and diff=" . $currentRates->getSellRateDifferenceTo(
            $lastRates
        ) . " with vector=" . $currentRates->getSellRateVector()
    );
}
if (($currentRates->getSellRate() - $currentRates->getBuyRate()) < $appThresholds->getMarginMinThreshold()) {
    $telegramHandler->publish(
        "USD threshold is very small, the rate is stable: Sell=" . $currentRates->getSellRate(
        ) . " sums per $ and buy=" . $currentRates->getBuyRate(
        ) . " sums per $ and diff=" . ($currentRates->getSellRate() - $currentRates->getBuyRate(
            )) . " is less that threshold=" . $appThresholds->getMarginMinThreshold()
    );
}

$now = date("His");//or date("H:i:s")
$start = '110501';//or '11:05:01'
$end = '1701000';//or '11:10:00'
if (date('D') == 'Sun' && $now >= $start && $now <= $end) {
    $telegramHandler->publish(
        "Hello! It's Sunday in Tashkent, and I'm still alive. Just a message I write every Sunday for you. Have a great day!\n" .
        "The USB buy=" . $currentRates->getBuyRate() . " and sell=" . $currentRates->getSellRate()
    );
}

die();
