<?php

namespace ValueObjects;

class Rates
{
    /**
     * @return int
     */
    public function getBuyRate(): int
    {
        return $this->buyRate;
    }
    
    /**
     * @return int
     */
    public function getSellRate(): int
    {
        return $this->sellRate;
    }
    
    /**
     * @return int
     */
    public function getBuyRateVector(): int
    {
        return $this->buyRateVector;
    }
    
    /**
     * @return int
     */
    public function getSellRateVector(): int
    {
        return $this->sellRateVector;
    }
    
    private int $buyRate;
    private int $sellRate;
    private int $buyRateVector;
    private int $sellRateVector;
    
    public function __construct(int $buyRate, int $sellRate, int $buyRateVector, int $sellRateVector)
    {
        $this->buyRate = $buyRate;
        $this->sellRate = $sellRate;
        $this->buyRateVector = $buyRateVector;
        $this->sellRateVector = $sellRateVector;
    }
    
    /**
     * @param Rates $ratesToCompareWith
     *
     * @return bool
     */
    public function isDifferentFrom(Rates $ratesToCompareWith): bool
    {
        return ($this->getBuyRate() != $ratesToCompareWith->getBuyRate()
            || $this->getSellRate() != $ratesToCompareWith->getSellRate()
            || $this->getBuyRateVector() != $ratesToCompareWith->getBuyRateVector()
            || $this->getSellRateVector() != $ratesToCompareWith->getSellRateVector()
        );
    }
    
    /**
     * @param Rates $anotherRate
     *
     * @return int
     */
    public function getBuyRateDifferenceTo (Rates $anotherRate):int{
        return $this->getBuyRate() - $anotherRate->getBuyRate();
    }
    
    /**
     * @param Rates $anotherRate
     *
     * @return int
     */
    public function getSellRateDifferenceTo (Rates $anotherRate):int{
        return $this->getSelRate() - $anotherRate->getSellRate();
    }
    
}