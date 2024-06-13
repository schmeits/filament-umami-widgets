<?php

namespace Schmeits\FilamentUmamiWidgets\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Schmeits\FilamentUmamiWidgets\FilamentUmamiWidgets
 */
class FilamentUmamiWidgets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Schmeits\FilamentUmamiWidgets\FilamentUmamiWidgets::class;
    }
}
