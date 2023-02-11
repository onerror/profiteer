<?php

namespace Views;

class MainPageView implements ViewInterface
{
    private static $template = __DIR__ . '/../templates/dashboard.php';
    
    public static function render(array $data)
    {
        require(self::$template);
    }
}