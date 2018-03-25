<?php

namespace AcmeCompany\ApplicationProcess;

use function method_exists;
use function var_dump;

trait LooseEventApplication
{
    protected function apply(object $event)
    {
        $className = get_class($event);
        $handler = 'apply' . substr($className, strrpos($className, '\\') + 1);

        if (method_exists($this, $handler)) {
            $this->{$handler}($event);
        }
    }
}