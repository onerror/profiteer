<?php

namespace Registry;

/**
 * A registry class
 */
abstract class Registry
{
    public const LOGGER = 'logger';
    public const DB = 'db';
    public const TELEGRAM = 'telegram';
    public const APP = 'app';
    
    
    private static array $services = [];
    
    private static array $allowedKeys = [
        self::LOGGER,
        self::DB,
        self::TELEGRAM,
        self::APP,
    ];
    
    /**
     * @param string $key
     * @param        $value
     *
     * @return void
     */
    public static function set(string $key, $value): void
    {
        if (!in_array($key, self::$allowedKeys)) {
            throw new \InvalidArgumentException('Invalid key given');
        }
        
        self::$services[$key] = $value;
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function get(string $key)
    {
        if (!isset(self::$services[$key]) || !in_array($key, self::$allowedKeys)) {
            throw new \InvalidArgumentException('Invalid key given');
        }
        
        return self::$services[$key];
    }
    
    /**
     * @param string $key
     *
     * @return void
     */
    public static function unset(string $key): void
    {
        if (!isset(self::$services[$key]) || !in_array($key, self::$allowedKeys)) {
            throw new \InvalidArgumentException('Invalid key given');
        }
        
        unset(self::$services[$key]);
    }
}