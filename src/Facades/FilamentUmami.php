<?php

namespace Schmeits\FilamentUmami\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Schmeits\FilamentUmami\FilamentUmami
 */
class FilamentUmami extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Schmeits\FilamentUmami\FilamentUmami::class;
    }
}
