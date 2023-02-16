<?php

namespace Repositories;

use ValueObjects\Rates;

class RatesRepository extends AbstractRepository
{
    
    public function addRates(Rates $rates)
    {
        $stmt = $this->db->prepare(
            "
            INSERT INTO usd_data (rate_buy, rate_sell, vector_rate_buy, vector_rate_sell)
VALUES (?,?,?,?);
;"
        );
        $stmt->execute(
            [$rates->getBuyRate(), $rates->getSellRate(), $rates->getBuyRateVector(), $rates->getSellRateVector()]
        );
    }
    
    public function getLastRates(): Rates
    {
        $stmt = $this->db->prepare(
            "
            SELECT rate_buy, rate_sell, vector_rate_buy, vector_rate_sell FROM usd_data ORDER BY timestamp_recorded LIMIT 1;"
        );
        $stmt->execute();
        return (array)$stmt->fetch(\PDO::FETCH_ASSOC);
    }
}