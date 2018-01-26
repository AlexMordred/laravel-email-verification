<?php

namespace Voerro\SimplePackage;

use Illuminate\Support\Facades\Facade;

class SimplePackageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'simple-package';
    }
}
