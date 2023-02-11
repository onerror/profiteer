<?php

namespace Repositories;

interface RepositoryInterface
{
    public function __construct(\PDO $db);
}