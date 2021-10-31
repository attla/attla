<?php

namespace App\Http\Middleware;

use Attla\Middleware\XssProtection as BaseXssProtection;

class XssProtection extends BaseXssProtection
{
    /**
     * The names of the attributes that should not be cleared
     *
     * @var array
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
