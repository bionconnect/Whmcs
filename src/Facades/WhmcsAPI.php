<?php

namespace BionConnection\WhmcsAPI\Facades;

use Illuminate\Support\Facades\Facade;

class WhmcsAPI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'whmcsapi';
    }
}
