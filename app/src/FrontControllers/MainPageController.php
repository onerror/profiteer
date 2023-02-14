<?php

namespace FrontControllers;

use Symfony\Component\DomCrawler\Crawler;
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
        
        $data = ['usd' => $buyUSDValue[0],
            'usd_vector' => $buyUSDVector[0]];
        
        MainPageView::render($data);
    }
}