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
        $data = [];
        MainPageView::render($data);
    }
}