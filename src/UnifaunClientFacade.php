<?php
namespace Infab\UnifanWebTA;

use Illuminate\Support\Facades\Facade;

class UnifaunClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'unifaun-client';
    }
}