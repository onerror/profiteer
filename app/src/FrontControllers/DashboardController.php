<?php

namespace FrontControllers;

use Views\MainPageView;

/**
 *
 */
class DashboardController
{
    
    private static $dateFormatForDiagrams = 'm/d/Y';
    
    public static function mainPage()
    {
        $endDate = (new \DateTimeImmutable('today'))->sub(new \DateInterval('P30D'));
        $data = [
            'start' => $endDate->format(self::$dateFormatForDiagrams),
            'end' => (new \DateTimeImmutable('today'))->format(self::$dateFormatForDiagrams),
        ];
        MainPageView::render($data);
    }
}