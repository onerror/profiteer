<?php

namespace FrontControllers;

use Registry\Registry;
use Repositories\RatesRepository;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\TelegramPublisher;
use ValueObjects\Rates;
use Views\MainPageView;
use GuzzleHttp\Client;

/**
 *
 */
class MainPageController implements FrontController
{
    
    public static function mainPage(): void
    {
        $kapitalBankURL = 'https://kapitalbank.uz/ru/services/exchange-rates/';

        $client = new Client();
        $response = $client->get($kapitalBankURL);

        $body = $response->getBody();
        $html = $body->getContents();
    
        $crawler = new Crawler($html);
        $buyUSDValue= $crawler->filter('div.item-usd div.item-rate-buy span.item-value')->each(function (Crawler $node, $i) {
            return $node->text();
        });
        $buyUSDVector = $crawler->filter('div.item-usd div.item-rate-buy span.item-value-vector')->each(
            function (Crawler $node, $i) {
                return $node->text();
            }
        );
        $sellUSDValue= $crawler->filter('div.item-usd div.item-rate-sale span.item-value')->each(function (Crawler $node, $i) {
            return $node->text();
        });
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
        $currentRates = new Rates(
            $buyUSDValue[0],
            $sellUSDValue[0],
            $buyUSDVector[0],
            $sellUSDVector[0]
        );
    
        $ratesRepository->storeRates(
            $currentRates
        );
        /**
         * @var TelegramPublisher $telegramHandler
         */
        $telegramHandler = Registry::get(Registry::TELEGRAM);
    
        $response = $telegramHandler->publish("Hello from PHP!\n USD Buying for =" . $currentRates->getBuyRate());
        
        MainPageView::render($data);
    }
}