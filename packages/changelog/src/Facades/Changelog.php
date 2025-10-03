<?php

namespace Neondigital\Changelog\Facades;

use Illuminate\Support\Facades\Facade;

class Changelog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'changelog';
    }
}
