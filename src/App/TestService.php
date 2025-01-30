<?php

declare(strict_types=1);

namespace App;

class TestService
{

    public function __construct(public TestAdapter $adapter)
    {

    }

    public function run(): string
    {
        return $this->adapter->execute();
    }

}
