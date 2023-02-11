<?php

namespace ApiControllers;

use Repositories\CustomerRepository;
use Repositories\DashboardRepository;
use Repositories\OrderRepository;

/**
 *
 */
class DashboardDataController
{
    public static function get(
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
        DashboardRepository $dashboard_repository,
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository
    ) {
        $result = [];
        $result['orders_count'] = $orderRepository->countBetweenDates($start, $end);
        $result['customers_count'] = $customerRepository->countBetweenDates($start, $end);
        $result['revenue'] = $orderRepository->revenueBetweenDates($start, $end);
        $graphData = $dashboard_repository->getDashboardDataBetweenDates($start, $end);
        $result['graph_dates'] = array_keys($graphData);
        $result['orders_tally_graph'] = array_column($graphData, 'orders_tally');
        $result['customers_tally_graph'] = array_column($graphData, 'customers_tally');
        header('Content-type: application/json');
        echo(json_encode($result));
    }
}