<?php

namespace Modules\CustomFields\Services;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class Validation extends Validator
{
    public function __call($method, $parameters)
    {
        $rule = Str::snake(substr($method, 8));

        if (isset($this->extensions[$rule])) {
            return $this->callExtension($rule, $parameters);
        }

        return call_user_func_array([$this, $method], $parameters); // Allow to call any orginal Validator class methods
    }
}
