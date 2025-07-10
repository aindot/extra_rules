<?php

namespace Aindot\ExtraRules\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Aindot\ExtraRules\ExtraRules
 */
class ExtraRules extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Aindot\ExtraRules\ExtraRules::class;
    }
}
