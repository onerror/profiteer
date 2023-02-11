<?php

namespace Views;

class ErrorPageView implements ViewInterface
{
    private static $template = __DIR__ . '/../templates/error.php';
    
    public static function render(array $data)
    {
        $error = $data['error']??'';
        require(self::$template);
    }
}