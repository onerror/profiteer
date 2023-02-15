<?php

namespace Repositories;

class RatesRepository extends AbstractRepository
{
    
    public function addRates($usdBuy, $usdSell, $usdBuyVector, $usdSellVector)
    {
        $stmt = $this->db->prepare(
            "
            INSERT INTO usd_data (rate_buy, rate_sell, vector_rate_buy, vector_rate_sell)
VALUES (?,?,?,?);
;"
        );
        $stmt->execute([$usdBuy, $usdSell, $usdBuyVector, $usdSellVector]);
    }
}