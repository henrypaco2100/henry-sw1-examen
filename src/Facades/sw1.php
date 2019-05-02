<?php

namespace henry\sw1\Facades;

use Illuminate\Support\Facades\Facade;

class sw1 extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sW1';
    }
}
