<?php

namespace Api\src\Database;

interface DbConnectionInterface
{
    /**
     * Connect to a database.
     *
     * @return object
     */
    public function connect(array $connectionDetails): object;
}