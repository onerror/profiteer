<?php

namespace AppConfigs;

class AppConfig
{
    private string $smallThreshold;
    private string $bigThreshold;
    
    private string $marginMinThreshold;
    
    public function __construct(string $smallThreshold, string $bigThresholld, string $marginMinThreshold){
        $this->smallThreshold = $smallThreshold;
        $this->bigThreshold = $bigThresholld;
        $this->marginMinThreshold = $marginMinThreshold;
    }
    
    /**
     * @return string
     */
    public function getSmallThreshold(): string
    {
        return $this->smallThreshold;
    }
    
    /**
     * @return string
     */
    public function getBigThreshold(): string
    {
        return $this->bigThreshold;
    }
    
    /**
     * @return string
     */
    public function getMarginMinThreshold(): string
    {
        return $this->marginMinThreshold;
    }
}