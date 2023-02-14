<?php

namespace ApiControllers;

use Repositories\CustomerRepository;
use Repositories\ExhangeRatesRepository;
use Repositories\OrderRepository;

/**
 *
 */
class MainPageDataController
{
    public static function get(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end
    ) {
        $result = [];
        header('Content-type: application/json');
        echo(json_encode($result));
    }
}