<?php

namespace Repositories;

use ValueObjects\Rates;

class RatesRepository extends AbstractRepository
{
    
    public function storeRates(Rates $rates)
    {
        $stmt = $this->db->prepare(
            "
            INSERT INTO usd_data (rate_buy, rate_sell, vector_rate_buy, vector_rate_sell) VALUES (?,?,?,?);"
        );
        $stmt->execute(
            [$rates->getBuyRate(), $rates->getSellRate(), $rates->getBuyRateVector(), $rates->getSellRateVector()]
        );
    }
    
    public function getLastRates(): Rates
    {
        $stmt = $this->db->prepare(
            "
            SELECT rate_buy, rate_sell, vector_rate_buy, vector_rate_sell FROM usd_data ORDER BY timestamp_recorded DESC LIMIT 1;"
        );
        $stmt->execute();
        $ratesArray = (array)$stmt->fetch(\PDO::FETCH_ASSOC);
        $lastRates = new Rates(
            $ratesArray['rate_buy'] ?? 0, $ratesArray['rate_sell'] ?? 0,
            $ratesArray['vector_rate_buy'] ?? 0, $ratesArray['vector_rate_sell'] ?? 0
        );
        return $lastRates;
    }
}