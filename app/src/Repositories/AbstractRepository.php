<?php

namespace Repositories;

class AbstractRepository implements RepositoryInterface
{
    /**
     * @var \PDO
     */
    protected $db;
    
    public function __construct (\PDO $db){
        $this->db = $db;
    }
}